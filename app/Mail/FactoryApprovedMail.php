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

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function envelope(): Envelope
    {
        $type = $this->factory->businessTypeLabel() ?: 'Factory';
        $subject = "[OpticB2B] Great News! Your {$type} Profile is Approved & Published";

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
