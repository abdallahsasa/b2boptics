<?php

namespace App\Services\Communication;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Escalation;
use App\Events\MessageSent;

class ConversationManager
{
    protected TranslationManager $translator;
    protected AIAssistantManager $ai;

    public function __construct(TranslationManager $translator, AIAssistantManager $ai)
    {
        $this->translator = $translator;
        $this->ai = $ai;
    }

    public function sendMessage(Conversation $conversation, $senderId, string $content, string $type = 'text')
    {
        // English is the target standard for Admins.
        $translationResult = $this->translator->translateWithDetection($content, 'EN-US');

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'type' => $type,
            'content_original' => $content,
            'content_translated' => $translationResult['translated_text'],
            'source_language' => $translationResult['detected_source_lang'],
            'target_language' => 'EN',
            'translation_provider' => 'deepl',
            'translation_confidence' => 0.99,
        ]);

        // Broadcast Event
        broadcast(new MessageSent($message))->toOthers();

        // If AI is active, try to answer
        if ($conversation->status === 'ai_active' && $senderId !== null) {
            $this->handleAIResponse($conversation, $translationResult['translated_text']);
        }

        return $message;
    }

    protected function handleAIResponse(Conversation $conversation, string $englishQuestion)
    {
        $aiResult = $this->ai->generateReply($englishQuestion);

        if ($aiResult['confidence'] < 0.5) {
            // Escalate
            $conversation->update(['status' => 'human_required']);
            Escalation::create([
                'conversation_id' => $conversation->id,
                'reason' => 'Low AI confidence / Knowledge not found',
                'confidence_score' => $aiResult['confidence'],
                'priority' => 'high'
            ]);
            
            // Notify user
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => null, // system/AI
                'type' => 'system',
                'content_original' => "I couldn't find an exact answer. I have escalated your request to a human representative.",
                'source_language' => 'EN',
            ]);
            
            broadcast(new MessageSent($message))->toOthers();
        } else {
            // Send AI Reply
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => null, // AI
                'type' => 'text',
                'content_original' => $aiResult['reply'], // This is in English, frontend should translate it back to user's lang if needed
                'source_language' => 'EN',
                'metadata' => ['sources' => $aiResult['sources']]
            ]);
            
            broadcast(new MessageSent($message))->toOthers();
        }
    }
}
