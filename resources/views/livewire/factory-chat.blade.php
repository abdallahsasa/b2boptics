@php
    $firstMessageMeta = $messages->firstWhere('metadata', '!=', null)?->metadata ?? [];
    $buyerName = $firstMessageMeta['buyer_name'] ?? $conversation->buyer?->name ?? __('Buyer');
    $buyerEmail = $firstMessageMeta['buyer_email'] ?? $conversation->buyer?->email ?? null;
    $buyerPhone = $firstMessageMeta['buyer_phone'] ?? null;
    $companyName = $firstMessageMeta['company_name'] ?? null;
    $quantity = $firstMessageMeta['quantity'] ?? null;
@endphp

<div class="flex flex-col h-[680px] bg-white rounded-xl shadow-sm border border-gray-200">
    <!-- Chat Header with Full Customer Details -->
    <div class="p-4 sm:p-5 border-b border-gray-200 bg-gray-50 rounded-t-xl flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5 flex-wrap">
                <h3 class="font-bold text-gray-900 text-base">{{ $conversation->subject ?: ('Chat with ' . $buyerName) }}</h3>
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $conversation->status === 'resolved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                    {{ $conversation->status === 'resolved' ? __('Replied') : __('Needs Reply') }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-600">
                <span><strong>{{ __('Customer') }}:</strong> {{ $buyerName }}</span>
                @if($buyerEmail)
                    <span><strong>{{ __('Email') }}:</strong> <a href="mailto:{{ $buyerEmail }}" class="text-primary-600 hover:underline">{{ $buyerEmail }}</a></span>
                @endif
                @if($buyerPhone)
                    <span><strong>{{ __('Phone') }}:</strong> <a href="tel:{{ $buyerPhone }}" class="text-primary-600 hover:underline">{{ $buyerPhone }}</a></span>
                @endif
                @if($companyName)
                    <span><strong>{{ __('Company') }}:</strong> {{ $companyName }}</span>
                @endif
                @if($quantity)
                    <span><strong>{{ __('Quantity') }}:</strong> {{ $quantity }}</span>
                @endif
            </div>
            @if($conversation->product)
                <p class="text-xs text-gray-500">
                    <strong>{{ __('Product') }}:</strong>
                    {{ is_array($conversation->product->name) ? ($conversation->product->name['en'] ?? reset($conversation->product->name)) : $conversation->product->getTranslation('name', 'en') }}
                </p>
            @endif
        </div>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50/50" id="chat-messages">
        @foreach($messages as $message)
            @php
                $isMe = $message->sender_id === auth()->id();
                $isSystem = is_null($message->sender_id);
            @endphp

            @if($isSystem)
                <div class="flex justify-center my-2">
                    <span class="bg-blue-50 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">
                        {{ $message->content_original }}
                    </span>
                </div>
            @else
                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] {{ $isMe ? 'bg-primary-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-white border border-gray-200 text-gray-900 rounded-r-xl rounded-tl-xl' }} p-3.5 shadow-sm relative">
                        <p class="text-sm whitespace-pre-line">{{ $message->content_original }}</p>
                        <span class="text-[10px] {{ $isMe ? 'text-primary-100' : 'text-gray-400' }} block mt-1.5 text-right">
                            {{ $message->created_at->format('M d, H:i') }}
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
                placeholder="{{ __('Type your reply to the customer...') }}" 
                class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                required
            >
            <button 
                type="submit" 
                class="inline-flex items-center px-5 py-2.5 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                wire:loading.attr="disabled"
            >
                {{ __('Send Reply') }}
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
