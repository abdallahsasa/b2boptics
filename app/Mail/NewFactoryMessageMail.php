<?php

namespace App\Mail;

use App\Models\Conversation;
use App\Models\Factory;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewFactoryMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public Factory $factory;
    public Conversation $conversation;
    public Message $chatMessage;
    public array $details;

    public function __construct(Factory $factory, Conversation $conversation, Message $chatMessage, array $details = [])
    {
        $this->factory = $factory;
        $this->conversation = $conversation;
        $this->chatMessage = $chatMessage;
        $this->details = $details;
    }

    public function envelope(): Envelope
    {
        $replyTo = [];
        if (!empty($this->details['buyer_email'])) {
            $replyTo[] = new Address(
                $this->details['buyer_email'],
                $this->details['buyer_name'] ?? 'Customer'
            );
        }

        return new Envelope(
            subject: '[OpticB2B] New Customer Message: ' . ($this->conversation->subject ?: 'Direct Inquiry'),
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.factory-new-message',
        );
    }
}

