<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class FactoryChat extends Component
{
    public Conversation $conversation;
    public string $newMessage = '';

    public function mount(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    #[On('echo-private:conversation.{conversation.id},MessageSent')]
    public function refreshMessages()
    {
        // Livewire automatically re-renders the component when receiving this event.
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '') {
            return;
        }

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => Auth::id(), // Factory User
            'type' => 'user',
            'content_original' => $this->newMessage,
            'source_language' => 'EN',
        ]);

        broadcast(new MessageSent($message));

        if ($this->conversation->status !== 'resolved') {
            $this->conversation->update(['status' => 'resolved']);
        }
        
        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.factory-chat', [
            'messages' => $this->conversation->messages()->oldest()->get(),
        ]);
    }
}
