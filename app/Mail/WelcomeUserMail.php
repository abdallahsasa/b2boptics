<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $accountType;

    public function __construct(User $user, string $accountType = 'buyer')
    {
        $this->user = $user;
        $this->accountType = $accountType;
    }

    public function envelope(): Envelope
    {
        $subject = $this->accountType === 'factory'
            ? __('Welcome to OpticB2B — Your Factory Portal & Free Starter Guide')
            : __('Welcome to OpticB2B — Your Global Optical B2B Sourcing Guide');

        return new Envelope(
            from: new Address(config('mail.from.address', 'support@opticb2b.com'), config('mail.from.name', 'OpticB2B')),
            replyTo: [new Address('support@opticb2b.com', 'OpticB2B Support')],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-user',
        );
    }
}

