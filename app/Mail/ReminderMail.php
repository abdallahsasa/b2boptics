<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientName;
    public string $reminderTemplate;
    public ?string $customSubject;
    public ?string $customBody;
    public string $mailLocale;
    public bool $isSupplier;

    public function __construct(
        string $recipientName,
        string $reminderTemplate = 'complete_profile',
        ?string $locale = null,
        ?string $customSubject = null,
        ?string $customBody = null,
        bool $isSupplier = true
    ) {
        $this->recipientName = $recipientName;
        $this->reminderTemplate = $reminderTemplate;
        $this->customSubject = $customSubject;
        $this->customBody = $customBody;
        $this->isSupplier = $isSupplier;

        $supported = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];
        $lang = $locale ?: app()->getLocale();
        $this->mailLocale = in_array($lang, $supported, true) ? $lang : 'en';
        $this->locale($this->mailLocale);
    }

    public function resolvedSubject(): string
    {
        if (!empty($this->customSubject)) {
            return $this->customSubject;
        }

        return match ($this->reminderTemplate) {
            'add_products' => __('[OpticB2B] Reminder: Publish Your First 10 Optical Products for Free', [], $this->mailLocale),
            'check_messages' => __('[OpticB2B] Reminder: Check New Buyer Tenders & Messages on OpticB2B', [], $this->mailLocale),
            default => __('[OpticB2B] Reminder: Complete Your Supplier Showroom Profile', [], $this->mailLocale),
        };
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'support@opticb2b.com'), config('mail.from.name', 'OpticB2B')),
            replyTo: [new Address('support@opticb2b.com', 'OpticB2B Support')],
            subject: $this->resolvedSubject(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder',
        );
    }
}
