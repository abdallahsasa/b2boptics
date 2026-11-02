<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\Rating;
use App\Services\Communication\ConversationManager;

class ChatController extends Controller
{
    public function start(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);

        $conversation = Conversation::create([
            'subject' => 'Inquiry for ' . $product->getTranslation('name', 'en'),
            'type' => 'product_inquiry',
            'status' => 'human_required',
            'factory_id' => $product->factory_id,
            'product_id' => $product->id,
        ]);

        $conversation->participants()->create([
            'user_id' => auth()->id(),
            'role' => 'buyer'
        ]);

        return response()->json([
            'conversation_id' => $conversation->id
        ]);
    }

    public function message(Request $request, ConversationManager $manager)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        
        // Authorization
        $isParticipant = $conversation->participants()->where('user_id', auth()->id())->exists();

        if (!$isParticipant) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $manager->sendMessage($conversation, auth()->id(), $request->message);

        return response()->json(['status' => 'sent']);
    }

    public function rate(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'helpful' => 'required|integer|min:1|max:5',
            'fast' => 'required|integer|min:1|max:5',
            'clear' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($validated['conversation_id']);
        $isParticipant = $conversation->participants()->where('user_id', auth()->id())->exists();

        if (!$isParticipant) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $avgRating = (int) round(($validated['helpful'] + $validated['fast'] + $validated['clear']) / 3);

        Rating::updateOrCreate(
            [
                'buyer_id' => auth()->id(),
                'factory_id' => $conversation->factory_id,
            ],
            [
                'product_id' => $conversation->product_id,
                'rating' => $avgRating,
                'comment' => $validated['comment'] ?? sprintf(
                    'Helpful: %d/5, Fast Response: %d/5, Clear Info: %d/5',
                    $validated['helpful'],
                    $validated['fast'],
                    $validated['clear']
                ),
                'status' => 'approved',
            ]
        );

        return response()->json(['status' => 'rated', 'rating' => $avgRating]);
    }
}
