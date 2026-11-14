<x-filament-widgets::widget>
    @php
        $stats = $this->getStats();
    @endphp

    <x-filament::section>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            <div>
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 4px 0;">
                    Email Center &amp; Logs
                </h2>
                <p style="font-size: 13px; color: #6b7280; margin: 0;">
                    Send welcome, approval, reminder, or custom emails in each recipient's language and track delivery logs.
                </p>
                <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 10px; font-size: 13px;">
                    <span><strong>{{ $stats['sent'] }}</strong> Total Sent</span>
                    <span><strong>{{ $stats['today'] }}</strong> Sent Today</span>
                    <span style="color: {{ $stats['failed'] > 0 ? '#dc2626' : '#6b7280' }};">
                        <strong>{{ $stats['failed'] }}</strong> Failed
                    </span>
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                {{ $this->sendEmailAction }}

                <x-filament::button
                    tag="a"
                    :href="$this->getLogsUrl()"
                    color="gray"
                    icon="heroicon-o-envelope"
                >
                    View Email Logs
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
