@php
    $configuredUrl = rtrim(config('app.url', 'https://opticb2b.com'), '/');
    $baseUrl = (str_contains($configuredUrl, 'localhost') || str_contains($configuredUrl, '127.0.0.1'))
        ? 'https://opticb2b.com'
        : $configuredUrl;

    $locale = $mailLocale ?? ($factory->language ?: 'en');
    $isRtl = $locale === 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
    $textAlign = $isRtl ? 'right' : 'left';
    $factoryName = $factory->getTranslation('official_name', $locale) ?: $factory->official_name;
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('New Customer Inquiry - OpticB2B', [], $locale) }}</title>
</head>
<body dir="{{ $dir }}" style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #0f172a; text-align: {{ $textAlign }};">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f1f5f9; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 620px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06); border: 1px solid #e2e8f0; text-align: {{ $textAlign }};">
                    <!-- Top Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); padding: 28px 32px; color: #ffffff; text-align: {{ $textAlign }};">
                            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #38bdf8; margin-bottom: 6px;">
                                {{ __('OpticB2B Direct Inquiry Notification', [], $locale) }}
                            </div>
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; line-height: 1.3; color: #ffffff;">
                                {{ __('You Have a New Customer Message', [], $locale) }}
                            </h1>
                            <p style="margin: 6px 0 0 0; font-size: 13px; color: #cbd5e1;">
                                {{ __('Sent directly to :factory', ['factory' => $factoryName], $locale) }}
                            </p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.6; color: #334155;">
                                {!! __('Hello <strong>:factory</strong> team,<br>A buyer has sent you a new message on OpticB2B. Below are the full customer and inquiry details:', ['factory' => e($factoryName)], $locale) !!}
                            </p>

                            <!-- Details Table -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; width: 36%;">
                                        {{ __('Subject / Topic', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 700; color: #0f172a;">
                                        {{ $conversation->subject ?: __('Direct Factory Inquiry', [], $locale) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ __('Customer Name', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #0f172a;">
                                        {{ $details['buyer_name'] ?? $conversation->buyer?->name ?? __('Verified Buyer', [], $locale) }}
                                    </td>
                                </tr>
                                @if(!empty($details['buyer_email']) || $conversation->buyer?->email)
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ __('Customer Email', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; color: #2254c5;">
                                        <a href="mailto:{{ $details['buyer_email'] ?? $conversation->buyer?->email }}" style="color: #2254c5; text-decoration: none; font-weight: 600;">
                                            {{ $details['buyer_email'] ?? $conversation->buyer?->email }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($details['buyer_phone']))
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ __('Phone / WhatsApp', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #0f172a;">
                                        {{ $details['buyer_phone'] }}
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($details['company_name']))
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ __('Company Name', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #0f172a;">
                                        {{ $details['company_name'] }}
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($details['product_name']))
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ __('Product / Item', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #0f172a;">
                                        @if(!empty($details['product_url']))
                                            <a href="{{ $details['product_url'] }}" style="color: #2254c5; text-decoration: underline;">{{ $details['product_name'] }}</a>
                                        @else
                                            {{ $details['product_name'] }}
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($details['quantity']))
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ __('Quantity / MOQ', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 700; color: #0f172a;">
                                        {{ $details['quantity'] }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ __('Received At', [], $locale) }}
                                    </td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: #475569;">
                                        {{ $chatMessage->created_at?->format('M d, Y H:i') ?? now()->format('M d, Y H:i') }} UTC
                                    </td>
                                </tr>
                            </table>

                            <!-- Message Content Box -->
                            <div style="margin-bottom: 28px;">
                                <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px;">
                                    {{ __('Customer Message', [], $locale) }}
                                </div>
                                <div style="background-color: #eff6ff; border-{{ $isRtl ? 'right' : 'left' }}: 4px solid #2254c5; padding: 18px 20px; border-radius: 8px; font-size: 15px; line-height: 1.65; color: #1e293b; white-space: pre-line;">{{ $chatMessage->content_original }}</div>
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0 10px 0;">
                                <a href="{{ $baseUrl }}/factory/conversations/{{ $conversation->id }}/edit"
                                   style="display: inline-block; background-color: #2254c5; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 28px; border-radius: 12px;">
                                    {{ __('View & Reply in Factory Portal', [], $locale) }} &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 32px; text-align: center; font-size: 12px; color: #64748b;">
                            {!! __('You received this email because a buyer contacted your factory profile on <strong>OpticB2B</strong>.', [], $locale) !!}<br>
                            {!! __('Log in to your <a href=":url" style="color: #2254c5; text-decoration: none; font-weight: 600;">Factory Portal Inbox</a> to manage all customer messages.', ['url' => $baseUrl . '/factory/conversations'], $locale) !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
