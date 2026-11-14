@php
    $configuredUrl = rtrim(config('app.url', 'https://opticb2b.com'), '/');
    $baseUrl = (str_contains($configuredUrl, 'localhost') || str_contains($configuredUrl, '127.0.0.1') || empty($configuredUrl))
        ? 'https://opticb2b.com'
        : $configuredUrl;

    $locale = $mailLocale ?? 'en';
    $isRtl = $locale === 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
    $textAlign = $isRtl ? 'right' : 'left';

    $ctaUrl = match ($reminderTemplate) {
        'add_products' => $baseUrl . '/factory/products/create',
        'check_messages' => $isSupplier ? $baseUrl . '/factory/conversations' : $baseUrl . '/' . $locale . '/marketplace',
        'custom' => $isSupplier ? $baseUrl . '/factory' : $baseUrl . '/' . $locale . '/marketplace',
        default => $baseUrl . '/factory/my-factory',
    };

    $ctaLabel = match ($reminderTemplate) {
        'add_products' => __('Add Your Products Now', [], $locale),
        'check_messages' => $isSupplier ? __('Open Messages & Tenders', [], $locale) : __('Explore OpticB2B', [], $locale),
        'custom' => $isSupplier ? __('Open Supplier Portal', [], $locale) : __('Visit OpticB2B', [], $locale),
        default => __('Complete Your Profile', [], $locale),
    };
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customSubject ?: __('OpticB2B Notification', [], $locale) }}</title>
</head>
<body dir="{{ $dir }}" style="margin:0;padding:0;background-color:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;text-align:{{ $textAlign }};">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:34px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background-color:#ffffff;border-radius:20px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 10px 25px rgba(15,23,42,0.06);text-align:{{ $textAlign }};">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 100%);padding:32px;color:#ffffff;text-align:{{ $textAlign }};">
                            <div style="font-size:11px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase;color:#38bdf8;margin-bottom:8px;">
                                {{ __('OPTICB2B • GLOBAL OPTICAL B2B NETWORK', [], $locale) }}
                            </div>
                            <h1 style="margin:0;font-size:23px;font-weight:800;line-height:1.3;color:#ffffff;">
                                {{ __('Hello, :name!', ['name' => $recipientName], $locale) }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;font-size:15px;line-height:1.7;color:#334155;">
                            @if($reminderTemplate === 'custom' && !empty($customBody))
                                <div style="white-space:pre-line;color:#1e293b;">{{ $customBody }}</div>
                            @elseif($reminderTemplate === 'add_products')
                                <p style="margin:0 0 16px;">
                                    {{ __('Your supplier account on OpticB2B includes 10 free product listings (up to 10 high-resolution photos each) on our Free Starter Package.', [], $locale) }}
                                </p>
                                <p style="margin:0 0 20px;">
                                    {{ __('Adding your optical frames, sunglasses, lenses, or machinery helps global buyers discover your factory and send direct wholesale inquiries.', [], $locale) }}
                                </p>
                            @elseif($reminderTemplate === 'check_messages')
                                <p style="margin:0 0 16px;">
                                    {{ __('Global optical buyers are actively browsing OpticB2B and posting new sourcing tenders (RFQs) every day.', [], $locale) }}
                                </p>
                                <p style="margin:0 0 20px;">
                                    {{ __('Log in to your portal to check for unread buyer messages, respond to inquiries, and submit competitive quotes on open tenders.', [], $locale) }}
                                </p>
                            @else
                                <p style="margin:0 0 16px;">
                                    {{ __('We noticed your OpticB2B supplier showroom profile is not yet complete. Completing your profile helps our team verify and publish your company faster.', [], $locale) }}
                                </p>
                                <p style="margin:0 0 20px;">
                                    {{ __('Please take 2 minutes to add your company logo, category, country, and contact details so global optical buyers can connect with you directly.', [], $locale) }}
                                </p>
                            @endif

                            <!-- CTA Button -->
                            <div style="text-align:center;margin:28px 0 8px;">
                                <a href="{{ $ctaUrl }}" style="display:inline-block;background-color:#2254c5;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;padding:14px 30px;border-radius:12px;">
                                    {{ $ctaLabel }} &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 32px;text-align:center;font-size:12px;color:#64748b;line-height:1.6;">
                            <div>{!! __('Need help getting started? Reply to this email or contact our team anytime at <a href="mailto:support@opticb2b.com" style="color:#2563eb;font-weight:600;text-decoration:none;">support@opticb2b.com</a>.', [], $locale) !!}</div>
                            <div style="margin-top:6px;">&copy; {{ date('Y') }} {{ __('OpticB2B Global Optical Sourcing Network. All rights reserved.', [], $locale) }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
