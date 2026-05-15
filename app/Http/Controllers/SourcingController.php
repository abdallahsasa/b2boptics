<?php

namespace App\Http\Controllers;

use App\Models\BuyerRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SourcingController extends Controller
{
    public function index()
    {
        $requests = BuyerRequest::with(['category', 'subcategory'])
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->paginate(10);

        return view('sourcing.index', compact('requests'));
    }

    public function create()
    {
        $categories = Category::where('type', 'product')->get();
        return view('sourcing.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|string',
            'target_price' => 'nullable|numeric',
            'currency' => 'required|string|size:3',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
        ]);

        $buyerRequest = new BuyerRequest($validated);
        $buyerRequest->buyer_id = Auth::id();
        $buyerRequest->status = 'pending';
        $buyerRequest->save();

        return redirect()->route('sourcing.show', $buyerRequest)
            ->with('success', 'Your sourcing request has been submitted and is awaiting approval.');
    }

    public function show(BuyerRequest $buyerRequest)
    {
        $buyerRequest->load(['category', 'subcategory', 'offers.factory.country']);
        
        // If the user is a supplier (factory owner), they might see "Unlock" logic
        // For MVP, we just show the details if approved
        
        return view('sourcing.show', compact('buyerRequest'));
    }
}
