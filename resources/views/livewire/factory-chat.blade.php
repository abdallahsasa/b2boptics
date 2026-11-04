@php
    $firstMessageMeta = $messages->firstWhere('metadata', '!=', null)?->metadata ?? [];
    $buyerName = $firstMessageMeta['buyer_name'] ?? $conversation->buyer?->name ?? __('Buyer');
    $buyerEmail = $firstMessageMeta['buyer_email'] ?? $conversation->buyer?->email ?? null;
    $buyerPhone = $firstMessageMeta['buyer_phone'] ?? null;
    $companyName = $firstMessageMeta['company_name'] ?? null;
    $quantity = $firstMessageMeta['quantity'] ?? null;
@endphp

<div>
    <style>
        .ob-chat-card {
            display: flex;
            flex-direction: column;
            height: 680px;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }
        .dark .ob-chat-card { background: #111827; border-color: #1f2937; }
        .ob-chat-hdr {
            padding: 18px 22px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .dark .ob-chat-hdr { background: #1f2937; border-bottom-color: #374151; }
        .ob-chat-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 8px;
            font-size: 12.5px;
            color: #475569;
        }
        .dark .ob-chat-meta { color: #cbd5e1; }
        .ob-chat-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .dark .ob-chat-body { background: #0f172a; }
        .ob-chat-row { display: flex; }
        .ob-chat-row.ob-me { justify-content: flex-end; }
        .ob-chat-row.ob-them { justify-content: flex-start; }
        .ob-chat-bubble {
            max-width: 75%;
            padding: 13px 16px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.55;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
        }
        .ob-chat-row.ob-me .ob-chat-bubble {
            background: #2254c5;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .ob-chat-row.ob-them .ob-chat-bubble {
            background: #ffffff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        .dark .ob-chat-row.ob-them .ob-chat-bubble {
            background: #1f2937;
            color: #f8fafc;
            border-color: #374151;
        }
        .ob-chat-footer {
            padding: 16px 20px;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }
        .dark .ob-chat-footer { background: #111827; border-top-color: #1f2937; }
        .ob-chat-form { display: flex; gap: 10px; align-items: center; }
        .ob-chat-input {
            flex: 1;
            padding: 11px 16px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            outline: none;
        }
        .dark .ob-chat-input { background: #1f2937; border-color: #374151; color: #ffffff; }
    </style>

    <div class="ob-chat-card">
        <!-- Chat Header with Full Customer Details -->
        <div class="ob-chat-hdr">
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">
                    {{ $conversation->subject ?: ('Chat with ' . $buyerName) }}
                </h3>
                <span style="padding: 3px 10px; font-size: 11px; font-weight: 700; border-radius: 9999px; {{ $conversation->status === 'resolved' ? 'background:#d1fae5;color:#047857;' : 'background:#fef3c7;color:#92400e;' }}">
                    {{ $conversation->status === 'resolved' ? __('Replied') : __('Needs Reply') }}
                </span>
            </div>

            <div class="ob-chat-meta">
                <span><strong>{{ __('Customer') }}:</strong> {{ $buyerName }}</span>
                @if($buyerEmail)
                    <span><strong>{{ __('Email') }}:</strong> <a href="mailto:{{ $buyerEmail }}" style="color:#2254c5;font-weight:600;text-decoration:none;">{{ $buyerEmail }}</a></span>
                @endif
                @if($buyerPhone)
                    <span><strong>{{ __('Phone') }}:</strong> <a href="tel:{{ $buyerPhone }}" style="color:#2254c5;font-weight:600;text-decoration:none;">{{ $buyerPhone }}</a></span>
                @endif
                @if($companyName)
                    <span><strong>{{ __('Company') }}:</strong> {{ $companyName }}</span>
                @endif
                @if($quantity)
                    <span><strong>{{ __('Quantity') }}:</strong> {{ $quantity }}</span>
                @endif
            </div>

            @if($conversation->product)
                <div style="font-size: 12px; color: #64748b; margin-top: 6px;">
                    <strong>{{ __('Product') }}:</strong>
                    {{ is_array($conversation->product->name) ? ($conversation->product->name['en'] ?? reset($conversation->product->name)) : $conversation->product->getTranslation('name', 'en') }}
                </div>
            @endif
        </div>

        <!-- Messages Area -->
        <div class="ob-chat-body" id="chat-messages">
            @foreach($messages as $message)
                @php
                    $isMe = $message->sender_id === auth()->id();
                    $isSystem = is_null($message->sender_id);
                @endphp

                @if($isSystem)
                    <div style="display:flex;justify-content:center;margin:6px 0;">
                        <span style="background:#eff6ff;color:#1e40af;font-size:12px;font-weight:600;padding:4px 12px;border-radius:9999px;border:1px solid #bfdbfe;">
                            {{ $message->content_original }}
                        </span>
                    </div>
                @else
                    <div class="ob-chat-row {{ $isMe ? 'ob-me' : 'ob-them' }}">
                        <div class="ob-chat-bubble">
                            <div style="white-space: pre-line;">{{ $message->content_original }}</div>
                            <div style="font-size: 10px; opacity: 0.7; margin-top: 6px; text-align: right;">
                                {{ $message->created_at->format('M d, H:i') }}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Input Area -->
        <div class="ob-chat-footer">
            <form wire:submit="sendMessage" class="ob-chat-form">
                <input
                    type="text"
                    wire:model="newMessage"
                    placeholder="{{ __('Type your reply to the customer...') }}"
                    class="ob-chat-input"
                    required
                >
                <x-filament::button type="submit" size="lg" icon="heroicon-m-paper-airplane">
                    {{ __('Send Reply') }}
                </x-filament::button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const messagesContainer = document.getElementById('chat-messages');
            const scrollToBottom = () => {
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            };
            scrollToBottom();
            Livewire.hook('morph.updated', () => {
                scrollToBottom();
            });
        });
    </script>
</div>
