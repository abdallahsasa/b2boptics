@php
    $configuredUrl = rtrim(config('app.url', 'https://opticb2b.com'), '/');
    $baseUrl = (str_contains($configuredUrl, 'localhost') || str_contains($configuredUrl, '127.0.0.1'))
        ? 'https://opticb2b.com'
        : $configuredUrl;

    $locale = $mailLocale ?? (app()->getLocale() ?: 'en');
    $isRtl = $locale === 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
    $textAlign = $isRtl ? 'right' : 'left';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Welcome to OpticB2B', [], $locale) }}</title>
</head>
<body dir="{{ $dir }}" style="margin:0;padding:0;background-color:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;text-align:{{ $textAlign }};">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background-color:#ffffff;border-radius:20px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 10px 25px rgba(15,23,42,0.06);text-align:{{ $textAlign }};">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 100%);padding:34px 32px;color:#ffffff;text-align:{{ $textAlign }};">
                            <div style="font-size:11px;font-weight:700;letter-spacing:1.6px;text-transform:uppercase;color:#38bdf8;margin-bottom:8px;">
                                {{ __('OPTICB2B • GLOBAL OPTICAL SOURCING NETWORK', [], $locale) }}
                            </div>
                            <h1 style="margin:0;font-size:24px;font-weight:800;line-height:1.3;color:#ffffff;">
                                {{ __('Welcome to OpticB2B, :name!', ['name' => $user->name], $locale) }}
                            </h1>
                            <p style="margin:10px 0 0;font-size:14px;color:#cbd5e1;line-height:1.6;">
                                @if($accountType === 'factory')
                                    {!! __('Your Supplier account is ready — including our <strong>Free Starter Package</strong> with 10 free product listings and up to 10 photos per product.', [], $locale) !!}
                                @else
                                    {{ __('Your account is now active. Connect directly with verified optical manufacturers, explore wholesale products, and post sourcing tenders.', [], $locale) }}
                                @endif
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            <!-- What is OpticB2B Section -->
                            <div style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:20px;margin-bottom:26px;">
                                <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:#2563eb;margin-bottom:6px;">
                                    {{ __('What is OpticB2B?', [], $locale) }}
                                </div>
                                <p style="margin:0;font-size:14px;line-height:1.65;color:#334155;">
                                    {!! __('<strong>OpticB2B</strong> is the dedicated global B2B marketplace for the optical industry. We connect optical retailers, distributors, chains, and prescription labs <strong>directly with verified factories</strong> manufacturing optical frames, sunglasses, ophthalmic lenses, contact lenses, and lab machinery — with zero middlemen and multi-language support across 9 languages.', [], $locale) !!}
                                </p>
                            </div>

                            @if($accountType === 'factory')
                                <!-- Factory Free Plan Highlight -->
                                <div style="background-color:#ecfdf5;border:1px solid #a7f3d0;border-radius:14px;padding:18px 20px;margin-bottom:26px;">
                                    <div style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;color:#047857;margin-bottom:4px;">
                                        ✓ {{ __('Free Starter Package Activated ($0.00 / Free)', [], $locale) }}
                                    </div>
                                    <p style="margin:0;font-size:13px;line-height:1.6;color:#065f46;">
                                        {!! __('Your supplier account includes <strong>10 Free Product Listings</strong> and up to <strong>10 High-Resolution Images per Product</strong>, plus a full Showroom and direct buyer messaging.', [], $locale) !!}
                                    </p>
                                </div>

                                <!-- Factory Step-by-Step Instructions -->
                                <h2 style="margin:0 0 16px;font-size:17px;font-weight:800;color:#0f172a;">
                                    {{ __('Quick-Start Guide for Your Factory', [], $locale) }}
                                </h2>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                1. {{ __('Complete Your Factory Showroom Profile', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Open <a href=":url" style="color:#2563eb;font-weight:600;text-decoration:none;">My Factory Profile</a> in your portal to add your company logo, cover banner, workshop video (MP4), 360° VR tour link, main product lines, and contact details.', ['url' => $baseUrl . '/factory/my-factory'], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                2. {{ __('List Your First 10 Products for Free (Up to 10 Photos Each)', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Go to <a href=":url" style="color:#2563eb;font-weight:600;text-decoration:none;">My Products → Add Product</a> to upload up to 10 products for free. Each product supports up to 10 gallery photos, wholesale starting price, and technical specifications.', ['url' => $baseUrl . '/factory/products/create'], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                3. {{ __('Respond to Direct Customer Messages', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('When buyers contact your factory or inquire about your products, you receive an instant email notification and a message thread inside <a href=":url" style="color:#2563eb;font-weight:600;text-decoration:none;">Customer Messages</a>.', ['url' => $baseUrl . '/factory/conversations'], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:12px 0;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                4. {{ __('Bid on Buyer Tenders & Post Stock Deals', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Browse open buyer sourcing requests under <a href=":tendersUrl" style="color:#2563eb;font-weight:600;text-decoration:none;">Tenders</a> to submit quotes, or publish ready-to-ship clearance lots under <a href=":stockUrl" style="color:#2563eb;font-weight:600;text-decoration:none;">Stock Deals</a>.', ['tendersUrl' => $baseUrl . '/factory/tenders', 'stockUrl' => $baseUrl . '/factory/stock-offers'], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <!-- CTA Button for Factory -->
                                <div style="text-align:center;margin:28px 0 12px;">
                                    <a href="{{ $baseUrl }}/factory"
                                       style="display:inline-block;background-color:#2563eb;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;padding:14px 30px;border-radius:12px;">
                                        {{ __('Open Your Factory Portal', [], $locale) }}
                                    </a>
                                </div>
                            @else
                                <!-- Buyer Step-by-Step Instructions -->
                                <h2 style="margin:0 0 16px;font-size:17px;font-weight:800;color:#0f172a;">
                                    {{ __('How to Use OpticB2B (Quick Guide)', [], $locale) }}
                                </h2>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                1. {{ __('Browse Wholesale Optical Products', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Visit <a href=":url" style="color:#2563eb;font-weight:600;text-decoration:none;">Products</a> to filter optical frames, sunglasses, lenses, contact lenses, and lab machinery by category, country of origin, and specifications.', ['url' => $baseUrl . '/' . $locale . '/marketplace'], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                2. {{ __('Explore Verified Factory Showrooms', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Check <a href=":url" style="color:#2563eb;font-weight:600;text-decoration:none;">Suppliers</a> to inspect manufacturer showrooms, watch workshop videos, take 360° virtual tours, and read verified buyer reviews.', ['url' => $baseUrl . '/' . $locale . '/factories'], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                3. {{ __('Contact Factories Directly', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Click <strong>Contact Factory Directly</strong> on any product or supplier page to send your quote request, target quantity, and specifications straight to the supplier account and email.', [], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:12px 0;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                4. {{ __('Post Sourcing Tenders & Claim Stock Deals', [], $locale) }}
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                {!! __('Have a custom order? Post a <a href=":tenderUrl" style="color:#2563eb;font-weight:600;text-decoration:none;">Tender</a> to receive competitive bids from multiple suppliers, or browse <a href=":stockUrl" style="color:#2563eb;font-weight:600;text-decoration:none;">Stock Deals</a> for ready-to-ship wholesale lots.', ['tenderUrl' => $baseUrl . '/' . $locale . '/find-deal', 'stockUrl' => $baseUrl . '/' . $locale . '/stock-deals'], $locale) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <!-- CTA Buttons for Buyer -->
                                <div style="text-align:center;margin:28px 0 16px;">
                                    <a href="{{ $baseUrl }}/{{ $locale }}/marketplace"
                                       style="display:inline-block;background-color:#2563eb;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;padding:14px 28px;border-radius:12px;margin:4px;">
                                        {{ __('Explore Optical Products', [], $locale) }}
                                    </a>
                                    <a href="{{ $baseUrl }}/{{ $locale }}/factories"
                                       style="display:inline-block;background-color:#0f172a;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;padding:14px 28px;border-radius:12px;margin:4px;">
                                        {{ __('Browse Verified Suppliers', [], $locale) }}
                                    </a>
                                </div>

                                <div style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px 18px;margin-top:20px;font-size:12px;color:#475569;text-align:center;">
                                    {{ __('Are you an optical manufacturer?', [], $locale) }} <a href="{{ $baseUrl }}/{{ $locale }}/packages" style="color:#2563eb;font-weight:700;text-decoration:none;">{{ __('View our Free Factory Package (10 Free Product Listings) →', [], $locale) }}</a>
                                </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f8fafc;border-top:1px solid #e2e8f0;padding:22px 32px;text-align:center;font-size:12px;color:#64748b;line-height:1.6;">
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
