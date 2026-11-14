@php
    $configuredUrl = rtrim(config('app.url', 'https://opticb2b.com'), '/');
    $baseUrl = (str_contains($configuredUrl, 'localhost') || str_contains($configuredUrl, '127.0.0.1') || empty($configuredUrl))
        ? 'https://opticb2b.com'
        : $configuredUrl;

    $locale = $mailLocale ?? ($factory->language ?: 'en');
    $isRtl = $locale === 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
    $textAlign = $isRtl ? 'right' : 'left';

    $liveShowroomUrl = $baseUrl . '/' . $locale . '/factories/' . $factory->slug;
    $portalUrl = $baseUrl . '/factory';
    $addProductUrl = $baseUrl . '/factory/products/create';
    $messagesUrl = $baseUrl . '/factory/conversations';
    $businessType = __(match ($factory->businessTypeKey()) {
        'Brand' => 'Brand',
        'Wholesaler' => 'Wholesaler',
        default => 'Factory',
    }, [], $locale);

    $factoryName = $factory->getTranslation('official_name', $locale) ?: $factory->official_name;
    $contactName = trim(($factory->contact_person_first_name ?? '') . ' ' . ($factory->contact_person_last_name ?? ''));
    $recipientName = !empty($contactName) ? $contactName : ($factory->owner?->name ?? $factoryName);
    $planName = __((string) ($factory->activePackage()?->name ?? 'Free Starter Package'), [], $locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Your Supplier Profile is Approved & Published', [], $locale) }}</title>
</head>
<body dir="{{ $dir }}" style="margin:0;padding:0;background-color:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;text-align:{{ $textAlign }};">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:36px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background-color:#ffffff;border-radius:22px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(15,23,42,0.08);text-align:{{ $textAlign }};">
                    
                    <!-- Top Brand Header Banner -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#064e3b 0%,#0f172a 100%);padding:38px 36px 32px;color:#ffffff;text-align:{{ $textAlign }};">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <div style="font-size:11px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase;color:#34d399;margin-bottom:12px;">
                                            {{ __('OPTICB2B • GLOBAL OPTICAL B2B NETWORK', [], $locale) }}
                                        </div>
                                        <div style="display:inline-block;background-color:rgba(16,185,129,0.2);border:1px solid rgba(52,211,153,0.4);border-radius:9999px;padding:6px 14px;margin-bottom:16px;">
                                            <span style="font-size:12px;font-weight:800;color:#6ee7b7;letter-spacing:0.5px;text-transform:uppercase;">
                                                ✓ {{ __('Application Approved & Published', [], $locale) }}
                                            </span>
                                        </div>
                                        <h1 style="margin:0 0 10px;font-size:25px;font-weight:800;line-height:1.3;color:#ffffff;">
                                            {{ __('Congratulations, :name!', ['name' => $recipientName], $locale) }}
                                        </h1>
                                        <p style="margin:0;font-size:15px;color:#cbd5e1;line-height:1.6;">
                                            {!! __('Our administration team has reviewed and <strong>approved your :type account</strong>. Your digital showroom is now officially published and visible to buyers worldwide on OpticB2B.', ['type' => e($businessType)], $locale) !!}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Body Content -->
                    <tr>
                        <td style="padding:34px 36px;">
                            
                            <!-- Published Profile Spotlight Card -->
                            <div style="background-color:#f0fdf4;border:1px solid #bbf7d0;border-radius:16px;padding:22px 24px;margin-bottom:30px;">
                                <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:#15803d;margin-bottom:8px;">
                                    {{ __('Live Directory Status', [], $locale) }}
                                </div>
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">{{ __('Company Name:', [], $locale) }}</strong> {{ $factoryName }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">{{ __('Business Type:', [], $locale) }}</strong> {{ $businessType }}
                                        </td>
                                    </tr>
                                    @if($factory->category)
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">{{ __('Product Category:', [], $locale) }}</strong> {{ $factory->category->getTranslation('name', $locale) ?? $factory->category->name }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if($factory->country)
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">{{ __('Country:', [], $locale) }}</strong> {{ $factory->country->name }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="padding:8px 0 0;font-size:14px;color:#15803d;font-weight:700;">
                                            ✓ {{ __('Public Status: Active & Published Globally', [], $locale) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Two Main Action Buttons -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="center">
                                                    <a href="{{ $liveShowroomUrl }}" style="display:inline-block;width:100%;max-width:320px;background:linear-gradient(135deg,#059669 0%,#047857 100%);color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;padding:14px 24px;border-radius:12px;text-align:center;box-shadow:0 6px 18px rgba(5,150,105,0.3);margin-bottom:10px;">
                                                        {{ __('View Your Live Showroom', [], $locale) }} &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <a href="{{ $portalUrl }}" style="display:inline-block;width:100%;max-width:320px;background-color:#0f172a;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;padding:13px 24px;border-radius:12px;text-align:center;">
                                                        {{ __('Access Supplier Portal', [], $locale) }} &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- What Happens Next Guide -->
                            <div style="border-top:1px solid #f1f5f9;padding-top:28px;margin-bottom:28px;">
                                <h2 style="margin:0 0 16px;font-size:17px;font-weight:800;color:#0f172a;">
                                    {{ __('What You Can Do Now on OpticB2B', [], $locale) }}
                                </h2>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <!-- Step 1: Products -->
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                1. {{ __('List Up to :limit Products for Free (:images Photos Each)', ['limit' => $factory->productLimit(), 'images' => $factory->maxImagesPerProduct()], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Your current package includes :limit free product slots with drag-and-drop photo galleries and technical specifications. Go to <a href=":url" style="color:#2563eb;font-weight:600;text-decoration:none;">Add New Product</a> to populate your catalog.', ['limit' => $factory->productLimit(), 'url' => $addProductUrl], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Step 2: Stock Deals -->
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                2. {{ __('Post Clearance & Ready-to-Ship Stock Deals', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {{ __('Do you have ready inventory, surplus stock, or factory overruns? Post them under Stock Deals to get instant cash flow from buyers looking for fast dispatch.', [], $locale) }}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Step 3: Direct Messages -->
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                3. {{ __('Direct Customer Messaging & Email Notifications', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('When optical retailers, chains, or lab buyers send inquiries about your products, you will receive real-time email alerts with their buyer details, and can converse directly in <a href=":url" style="color:#2563eb;font-weight:600;text-decoration:none;">Customer Messages</a>.', ['url' => $messagesUrl], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Step 4: RFQs & Tenders -->
                                    <tr>
                                        <td style="padding:12px 0;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                4. {{ __('Quote on Buyer Sourcing Tenders (RFQs)', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {{ __('Browse global optical buyer tenders in your portal, view custom product requirements, and submit competitive supplier offers directly to interested buyers.', [], $locale) }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Package Quota Summary Badge -->
                            <div style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:18px 20px;margin-bottom:28px;">
                                <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;color:#64748b;margin-bottom:10px;">
                                    {{ __('Active Package Highlights: :plan', ['plan' => $planName], $locale) }}
                                </div>
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ {!! __('<strong>:limit Product Listings</strong> with multi-image gallery support', ['limit' => $factory->productLimit()], $locale) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ {!! __('<strong>Up to :images High-Resolution Images</strong> per product', ['images' => $factory->maxImagesPerProduct()], $locale) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ {!! __('<strong>Verified Showroom Profile</strong> with Workshop Video (MP4) &amp; 360° VR Tour', [], $locale) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ {!! __('<strong>Direct Buyer Messaging</strong> &amp; 9-Language Platform Translation', [], $locale) !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Need Assistance Help Desk -->
                            <div style="background-color:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:18px 20px;text-align:{{ $textAlign }};">
                                <div style="font-size:13px;font-weight:700;color:#1e40af;margin-bottom:4px;">
                                    {{ __('Need help setting up your showroom or products?', [], $locale) }}
                                </div>
                                <div style="font-size:13px;color:#1e3a8a;line-height:1.55;">
                                    {!! __('Our dedicated supplier support team is here to assist you with photography guidelines, catalog imports, or translation questions. Contact us anytime at <a href="mailto:support@opticb2b.com" style="color:#2563eb;font-weight:700;text-decoration:none;">support@opticb2b.com</a>.', [], $locale) !!}
                                </div>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f8fafc;padding:24px 36px;border-top:1px solid #e2e8f0;text-align:center;font-size:12px;color:#64748b;line-height:1.6;">
                            <p style="margin:0 0 6px;">
                                <strong>OpticB2B</strong> — {{ __('Global B2B Optical Sourcing Marketplace', [], $locale) }}
                            </p>
                            <p style="margin:0 0 10px;">
                                {{ __('Connect directly with verified optical retailers, distributors, brands, and factories worldwide.', [], $locale) }}
                            </p>
                            <p style="margin:0;font-size:11px;color:#94a3b8;">
                                &copy; {{ date('Y') }} OpticB2B. {{ __('All rights reserved.', [], $locale) }} &bull; <a href="{{ $baseUrl }}/{{ $locale }}/privacy-policy" style="color:#64748b;text-decoration:underline;">{{ __('Privacy Policy', [], $locale) }}</a> &bull; <a href="{{ $baseUrl }}/{{ $locale }}/contact-us" style="color:#64748b;text-decoration:underline;">{{ __('Contact Support', [], $locale) }}</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
