<div class="space-y-6">
    @if (count($sessions) > 0)
        <div class="space-y-4">
            @foreach ($sessions as $session)
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 p-2 bg-gray-100 rounded-lg dark:bg-gray-800">
                        <x-heroicon-o-computer-desktop class="fi-icon {{ $session->is_current_device ? 'text-primary-600' : 'text-gray-400' }}" style="width: 24px; height: 24px;" />
                    </div>

                    <div class="flex-grow min-w-0">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                            {{ $session->agent }}
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $session->ip_address }} • 
                            @if ($session->is_current_device)
                                <span class="font-semibold text-success-600 dark:text-success-400">{{ __('This device') }}</span>
                            @else
                                {{ __('Last active') }} {{ $session->last_active }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-sm text-gray-500">
            {{ __('No other active sessions found.') }}
        </div>
    @endif
</div>
