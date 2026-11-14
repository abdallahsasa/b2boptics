<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Your Supplier Profile is Approved & Published') }}</title>
</head>
@php
    $configuredUrl = rtrim(config('app.url', 'https://opticb2b.com'), '/');
    $baseUrl = (str_contains($configuredUrl, 'localhost') || str_contains($configuredUrl, '127.0.0.1') || empty($configuredUrl))
        ? 'https://opticb2b.com'
        : $configuredUrl;

    $locale = $factory->language ?: 'en';
    $liveShowroomUrl = $baseUrl . '/' . $locale . '/factories/' . $factory->slug;
    $portalUrl = $baseUrl . '/factory';
    $productsUrl = $baseUrl . '/factory/products';
    $addProductUrl = $baseUrl . '/factory/products/create';
    $messagesUrl = $baseUrl . '/factory/conversations';
    $businessType = $factory->businessTypeLabel() ?: 'Factory';

    $contactName = trim(($factory->contact_person_first_name ?? '') . ' ' . ($factory->contact_person_last_name ?? ''));
    $recipientName = !empty($contactName) ? $contactName : ($factory->owner?->name ?? $factory->official_name);
@endphp
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:36px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background-color:#ffffff;border-radius:22px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(15,23,42,0.08);">
                    
                    <!-- Top Brand Header Banner -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#064e3b 0%,#0f172a 100%);padding:38px 36px 32px;color:#ffffff;text-align:left;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <div style="font-size:11px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase;color:#34d399;margin-bottom:12px;">
                                            OPTICB2B • GLOBAL OPTICAL B2B NETWORK
                                        </div>
                                        <div style="display:inline-block;background-color:rgba(16,185,129,0.2);border:1px solid rgba(52,211,153,0.4);border-radius:9999px;padding:6px 14px;margin-bottom:16px;">
                                            <span style="font-size:12px;font-weight:800;color:#6ee7b7;letter-spacing:0.5px;text-transform:uppercase;">
                                                ✓ Application Approved &amp; Published
                                            </span>
                                        </div>
                                        <h1 style="margin:0 0 10px;font-size:25px;font-weight:800;line-height:1.3;color:#ffffff;">
                                            Congratulations, {{ $recipientName }}!
                                        </h1>
                                        <p style="margin:0;font-size:15px;color:#cbd5e1;line-height:1.6;">
                                            Our administration team has reviewed and <strong>approved your {{ $businessType }} account</strong>. Your digital showroom is now officially published and visible to buyers worldwide on OpticB2B.
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
                                    Live Directory Status
                                </div>
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">Company Name:</strong> {{ $factory->official_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">Business Type:</strong> {{ $businessType }}
                                        </td>
                                    </tr>
                                    @if($factory->category)
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">Product Category:</strong> {{ $factory->category->getTranslation('name', $locale) ?? $factory->category->name }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if($factory->country)
                                    <tr>
                                        <td style="padding:6px 0;font-size:14px;color:#334155;">
                                            <strong style="color:#0f172a;">Country:</strong> {{ $factory->country->name }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="padding:8px 0 0;font-size:14px;color:#15803d;font-weight:700;">
                                            ✓ Public Status: Active &amp; Published Globally
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
                                                        View Your Live Showroom &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <a href="{{ $portalUrl }}" style="display:inline-block;width:100%;max-width:320px;background-color:#0f172a;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;padding:13px 24px;border-radius:12px;text-align:center;">
                                                        Access Supplier Portal &rarr;
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
                                    What You Can Do Now on OpticB2B
                                </h2>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <!-- Step 1: Products -->
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                1. List Up to 10 Products for Free (10 Photos Each)
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                Your current package includes 10 free product slots with drag-and-drop photo galleries and technical specifications. Go to <a href="{{ $addProductUrl }}" style="color:#2563eb;font-weight:600;text-decoration:none;">Add New Product</a> to populate your catalog.
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Step 2: Stock Deals -->
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                2. Post Clearance &amp; Ready-to-Ship Stock Deals
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                Do you have ready inventory, surplus stock, or factory overruns? Post them under Stock Deals to get instant cash flow from buyers looking for fast dispatch.
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Step 3: Direct Messages -->
                                    <tr>
                                        <td style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                3. Direct Customer Messaging &amp; Email Notifications
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                When optical retailers, chains, or lab buyers send inquiries about your products, you'll receive real-time email alerts with their buyer details, and can converse directly in <a href="{{ $messagesUrl }}" style="color:#2563eb;font-weight:600;text-decoration:none;">Customer Messages</a>.
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Step 4: RFQs & Tenders -->
                                    <tr>
                                        <td style="padding:12px 0;">
                                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                                4. Quote on Buyer Sourcing Tenders (RFQs)
                                            </div>
                                            <div style="font-size:13px;color:#475569;line-height:1.55;">
                                                Browse global optical buyer tenders in your portal, view custom product requirements, and submit competitive supplier offers directly to interested buyers.
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Package Quota Summary Badge -->
                            <div style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:18px 20px;margin-bottom:28px;">
                                <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;color:#64748b;margin-bottom:10px;">
                                    Active Package Highlights: {{ $factory->subscriptionPlanLabel() }}
                                </div>
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ <strong>{{ $factory->productLimit() }} Product Listings</strong> with multi-image gallery support
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ <strong>Up to {{ $factory->maxImagesPerProduct() }} High-Resolution Images</strong> per product
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ <strong>Verified Showroom Profile</strong> with Workshop Video (MP4) &amp; 360° VR Tour
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:13px;color:#334155;padding:3px 0;">
                                            ✓ <strong>Direct Buyer Messaging</strong> &amp; 9-Language Platform Translation
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Need Assistance Help Desk -->
                            <div style="background-color:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:18px 20px;text-align:left;">
                                <div style="font-size:13px;font-weight:700;color:#1e40af;margin-bottom:4px;">
                                    Need help setting up your showroom or products?
                                </div>
                                <div style="font-size:13px;color:#1e3a8a;line-height:1.55;">
                                    Our dedicated supplier support team is here to assist you with photography guidelines, catalog imports, or translation questions. Contact us anytime at <a href="mailto:support@opticb2b.com" style="color:#2563eb;font-weight:700;text-decoration:none;">support@opticb2b.com</a>.
                                </div>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f8fafc;padding:24px 36px;border-top:1px solid #e2e8f0;text-align:center;font-size:12px;color:#64748b;line-height:1.6;">
                            <p style="margin:0 0 6px;">
                                <strong>OpticB2B</strong> — Global B2B Optical Sourcing Marketplace
                            </p>
                            <p style="margin:0 0 10px;">
                                Connect directly with verified optical retailers, distributors, brands, and factories worldwide.
                            </p>
                            <p style="margin:0;font-size:11px;color:#94a3b8;">
                                &copy; {{ date('Y') }} OpticB2B. All rights reserved. &bull; <a href="{{ $baseUrl }}/privacy-policy" style="color:#64748b;text-decoration:underline;">Privacy Policy</a> &bull; <a href="{{ $baseUrl }}/contact-us" style="color:#64748b;text-decoration:underline;">Contact Support</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
