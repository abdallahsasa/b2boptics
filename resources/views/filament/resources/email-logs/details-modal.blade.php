<div class="space-y-3 text-sm">
    <div>
        <span class="font-semibold text-gray-600 dark:text-gray-400">Recipient:</span>
        <span>{{ $record->recipient_name ? "{$record->recipient_name} <{$record->recipient_email}>" : $record->recipient_email }}</span>
    </div>
    <div>
        <span class="font-semibold text-gray-600 dark:text-gray-400">Type:</span>
        <span>{{ \App\Models\EmailLog::typeOptions()[$record->email_type] ?? $record->email_type }}</span>
    </div>
    <div>
        <span class="font-semibold text-gray-600 dark:text-gray-400">Language:</span>
        <span>{{ strtoupper($record->locale ?? 'en') }}</span>
    </div>
    <div>
        <span class="font-semibold text-gray-600 dark:text-gray-400">Subject:</span>
        <span>{{ $record->subject }}</span>
    </div>
    <div>
        <span class="font-semibold text-gray-600 dark:text-gray-400">Status:</span>
        <span style="font-weight: 600; color: {{ $record->status === 'sent' ? '#16a34a' : '#dc2626' }};">
            {{ strtoupper($record->status) }}
        </span>
    </div>
    <div>
        <span class="font-semibold text-gray-600 dark:text-gray-400">Sent At:</span>
        <span>{{ $record->created_at?->format('Y-m-d H:i:s') }}</span>
    </div>
    @if($record->error_message)
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 10px; color: #991b1b;">
            <div style="font-weight: 600; margin-bottom: 4px;">Error Message:</div>
            <pre style="white-space: pre-wrap; margin: 0; font-size: 12px;">{{ $record->error_message }}</pre>
        </div>
    @endif
</div>
