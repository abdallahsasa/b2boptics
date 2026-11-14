<?php

namespace App\Mail;

use App\Models\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FactoryApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Factory $factory;
    public string $mailLocale;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        $supported = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];
        $lang = $factory->language ?: app()->getLocale();
        $this->mailLocale = in_array($lang, $supported, true) ? $lang : 'en';
        $this->locale($this->mailLocale);
    }

    public function envelope(): Envelope
    {
        $type = __(match ($this->factory->businessTypeKey()) {
            'Brand' => 'Brand',
            'Wholesaler' => 'Wholesaler',
            default => 'Factory',
        }, [], $this->mailLocale);

        $subject = __('[OpticB2B] Great News! Your :type Profile is Approved & Published', ['type' => $type], $this->mailLocale);

        return new Envelope(
            from: new Address(config('mail.from.address', 'support@opticb2b.com'), config('mail.from.name', 'OpticB2B')),
            replyTo: [new Address('support@opticb2b.com', 'OpticB2B Support')],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.factory-approved',
        );
    }
}
