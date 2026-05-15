<?php

namespace App\Http\Controllers;

use App\Models\StockOffer;
use App\Models\Category;
use Illuminate\Http\Request;

class StockOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOffer::with(['factory.country', 'category'])
            ->where('status', 'active')
            ->where('ends_at', '>', now());

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $offers = $query->latest()->paginate(12);
        $categories = Category::where('type', 'product')->get();

        return view('stock-offers.index', compact('offers', 'categories'));
    }
}
