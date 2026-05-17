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

    public function show($locale, StockOffer $stockOffer)
    {
        if ($stockOffer->status !== 'active' || $stockOffer->ends_at <= now()) {
            abort(404);
        }

        $stockOffer->load(['factory.country', 'category']);
        
        return view('stock-offers.show', compact('stockOffer'));
    }

    public function request(Request $request, $locale, StockOffer $stockOffer)
    {
        if ($stockOffer->status !== 'active' || $stockOffer->ends_at <= now()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'quantity_requested' => 'required|integer|min:' . ($stockOffer->moq ?? 1),
            'message' => 'nullable|string',
        ]);

        $validated['stock_offer_id'] = $stockOffer->id;
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        \App\Models\StockOfferRequest::create($validated);

        return redirect()->route('stock-offers.show', $stockOffer)
            ->with('success', 'Your request has been submitted successfully! Our team will contact you shortly to connect you with the manufacturer.');
    }
}
