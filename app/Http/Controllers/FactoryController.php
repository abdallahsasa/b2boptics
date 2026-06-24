<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Factory::where('status', 'approved')
            ->with(['country', 'category']);

        // Search by name / description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $locale = app()->getLocale();
                $q->where('official_name->' . $locale, 'like', '%' . $request->search . '%')
                  ->orWhere('description->' . $locale, 'like', '%' . $request->search . '%')
                  ->orWhere('official_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Country
        if ($request->filled('country')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('code', $request->country);
            });
        }

        $factories = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::where('type', 'product')->get();
        $countries = Country::where('status', 'active')->get();

        return view('factories.index', compact('factories', 'categories', 'countries'));
    }

    public function show($locale, Factory $factory)
    {
        if ($factory->status !== 'approved') {
            abort(404);
        }

        $factory->load(['country', 'category', 'products' => function ($q) {
            $q->where('status', 'approved')->latest();
        }]);

        return view('factories.show', compact('factory'));
    }
}
