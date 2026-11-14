<?php

namespace App\Http\Controllers;

use App\Models\BannerAd;
use App\Models\Product;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['factory.country', 'countryOfOrigin', 'category'])
            ->where('status', 'approved');

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Country of Origin (falling back to supplier country when unset)
        if ($request->filled('country')) {
            $countryCode = $request->country;
            $query->where(function ($q) use ($countryCode) {
                $q->whereHas('countryOfOrigin', fn ($cq) => $cq->where('code', $countryCode))
                    ->orWhere(function ($fallback) use ($countryCode) {
                        $fallback->whereNull('country_of_origin_id')
                            ->whereHas('factory.country', fn ($fcq) => $fcq->where('code', $countryCode));
                    });
            });
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('starting_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('starting_price', '<=', $request->max_price);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('starting_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('starting_price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('type', 'product')->get();
        $countries = Country::where('status', 'active')->get();
        $bannerAds = BannerAd::forPage('products')->get();

        return view('marketplace.index', compact('products', 'categories', 'countries', 'bannerAds'));
    }

    public function show($locale, Product $product)
    {
        if ($product->status !== 'approved') {
            abort(404);
        }

        $product->load(['factory.country', 'countryOfOrigin', 'category', 'subcategory']);
        
        // Similar products
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'approved')
            ->take(4)
            ->get();

        return view('marketplace.show', compact('product', 'similarProducts'));
    }
}
