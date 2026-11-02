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

        if ($stockOffer->factory_id) {
            $offerTitle = $stockOffer->getTranslation('title', 'en');
            $conversation = \App\Models\Conversation::create([
                'subject' => 'Stock Deal Request: ' . $offerTitle,
                'type' => 'stock_offer_inquiry',
                'status' => 'human_required',
                'factory_id' => $stockOffer->factory_id,
            ]);

            if (auth()->check()) {
                $conversation->participants()->create([
                    'user_id' => auth()->id(),
                    'role' => 'buyer',
                ]);
            }

            $details = [
                'buyer_name' => $validated['name'],
                'buyer_email' => $validated['email'],
                'buyer_phone' => $validated['phone'],
                'company_name' => $validated['company_name'] ?? null,
                'quantity' => $validated['quantity_requested'] . ' units',
                'product_name' => 'Stock Deal: ' . $offerTitle,
                'product_url' => route('stock-offers.show', $stockOffer),
            ];

            $msgText = !empty($validated['message'])
                ? $validated['message']
                : sprintf('Requested %d units of "%s".', $validated['quantity_requested'], $offerTitle);

            $message = \App\Models\Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => auth()->id(),
                'type' => 'user',
                'content_original' => $msgText,
                'source_language' => strtoupper(app()->getLocale()),
                'metadata' => array_filter($details),
            ]);

            \App\Services\Communication\ConversationManager::notifyFactoryByEmail($conversation, $message, $details);
        }

        return redirect()->route('stock-offers.show', $stockOffer)
            ->with('success', __('Your request and message have been sent directly to :factory! They have been notified on their account and by email.', ['factory' => $stockOffer->factory?->official_name ?? __('the manufacturer')]));
    }
}
