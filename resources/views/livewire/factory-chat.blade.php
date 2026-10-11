<div class="flex flex-col h-[600px] bg-white rounded-xl shadow-sm border border-gray-200">
    <!-- Chat Header -->
    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-xl flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-900">Chat with {{ $conversation->buyer?->name ?? __('Buyer') }}</h3>
            <p class="text-sm text-gray-500">Product: {{ $conversation->product ? (is_array($conversation->product->name) ? ($conversation->product->name['en'] ?? '') : $conversation->product->name) : __('General Inquiry') }}</p>
        </div>
        <div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                {{ $conversation->status === 'human_required' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                {{ str_replace('_', ' ', Str::title($conversation->status)) }}
            </span>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50/50" id="chat-messages">
        @foreach($messages as $message)
            @php
                // Is this message from the current factory owner?
                $isMe = $message->sender_id === auth()->id();
                // Is this message from the system/AI?
                $isSystem = is_null($message->sender_id);
            @endphp

            @if($isSystem)
                <!-- System / AI Message -->
                <div class="flex justify-center my-2">
                    <span class="bg-blue-50 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">
                        🤖 AI Assistant: {{ $message->content_original }}
                    </span>
                </div>
            @else
                <!-- Normal Message -->
                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] {{ $isMe ? 'bg-primary-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-white border border-gray-200 text-gray-900 rounded-r-xl rounded-tl-xl' }} p-3 shadow-sm relative">
                        <p class="text-sm">{{ $message->content_original }}</p>
                        <span class="text-[10px] {{ $isMe ? 'text-primary-100' : 'text-gray-400' }} block mt-1 text-right">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Input Area -->
    <div class="p-4 border-t border-gray-200 bg-white rounded-b-xl">
        <form wire:submit="sendMessage" class="flex gap-2">
            <input 
                type="text" 
                wire:model="newMessage" 
                placeholder="Type your reply to the buyer..." 
                class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                required
            >
            <button 
                type="submit" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                wire:loading.attr="disabled"
            >
                Send
            </button>
        </form>
    </div>

    <!-- Auto-scroll script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const messagesContainer = document.getElementById('chat-messages');
            
            const scrollToBottom = () => {
                if(messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            };
            
            scrollToBottom();
            
            Livewire.hook('morph.updated', ({ component, el }) => {
                scrollToBottom();
            });
        });
    </script>
</div>
