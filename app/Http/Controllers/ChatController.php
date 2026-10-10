<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Product;
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
            'status' => 'ai_active',
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
}
