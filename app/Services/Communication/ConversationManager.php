<?php

namespace App\Services\Communication;

use App\Mail\NewFactoryMessageMail;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Escalation;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConversationManager
{
    protected TranslationManager $translator;

    public function __construct(TranslationManager $translator)
    {
        $this->translator = $translator;
    }

    public function sendMessage(Conversation $conversation, $senderId, string $content, string $type = 'text', array $details = [])
    {
        $translationResult = $this->translator->translateWithDetection($content, 'EN-US');

        $conversation->update(['status' => 'human_required']);

        $sender = $senderId ? \App\Models\User::find($senderId) : null;
        $meta = array_filter(array_merge([
            'buyer_name' => $sender?->name,
            'buyer_email' => $sender?->email,
        ], $details));

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
            'metadata' => !empty($meta) ? $meta : null,
        ]);

        // Broadcast Event
        broadcast(new MessageSent($message));

        // Notify the factory directly via email
        if ($senderId !== null) {
            self::notifyFactoryByEmail($conversation, $message, $meta);
        }

        return $message;
    }

    public static function notifyFactoryByEmail(Conversation $conversation, Message $message, array $details = []): void
    {
        try {
            $conversation->loadMissing(['factory.owner', 'product', 'buyer']);
            $factory = $conversation->factory;
            if (!$factory) {
                return;
            }

            $recipients = array_values(array_unique(array_filter([
                $factory->email,
                $factory->owner?->email,
            ])));

            if (empty($recipients)) {
                return;
            }

            if ($conversation->product && empty($details['product_name'])) {
                $details['product_name'] = $conversation->product->getTranslation('name', 'en');
                $details['product_url'] = url('/en/marketplace/product/' . $conversation->product->slug);
            }

            $primaryTo = array_shift($recipients);
            $locale = \App\Services\EmailService::resolveUserLocale($factory->owner, $factory);
            $mailable = new NewFactoryMessageMail($factory, $conversation, $message, $details);

            try {
                $pendingMail = Mail::to($primaryTo);
                if (!empty($recipients)) {
                    $pendingMail->cc($recipients);
                }

                $pendingMail->send($mailable);

                \App\Models\EmailLog::record([
                    'user_id' => $factory->user_id,
                    'factory_id' => $factory->id,
                    'recipient_email' => $primaryTo,
                    'recipient_name' => $factory->owner?->name ?? $factory->getTranslation('official_name', $locale),
                    'email_type' => 'new_message',
                    'locale' => $locale,
                    'subject' => $mailable->envelope()->subject ?? 'New Buyer Inquiry',
                    'status' => 'sent',
                    'meta' => ['conversation_id' => $conversation->id],
                ]);
            } catch (\Throwable $e) {
                \App\Models\EmailLog::record([
                    'user_id' => $factory->user_id,
                    'factory_id' => $factory->id,
                    'recipient_email' => $primaryTo,
                    'recipient_name' => $factory->owner?->name ?? $factory->getTranslation('official_name', $locale),
                    'email_type' => 'new_message',
                    'locale' => $locale,
                    'subject' => $mailable->envelope()->subject ?? 'New Buyer Inquiry',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'meta' => ['conversation_id' => $conversation->id],
                ]);
                throw $e;
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send factory inquiry email: ' . $e->getMessage());
        }
    }
}
