<?php

namespace App\Http\Controllers;

use App\Models\BannerAd;
use App\Models\Conversation;
use App\Models\Factory;
use App\Models\Category;
use App\Models\Country;
use App\Models\Message;
use App\Models\Rating;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Factory::where('status', 'approved')
            ->with(['country', 'category']);

        // Search by name / description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $locale = app()->getLocale();
                $q->where('official_name->' . $locale, 'like', '%' . $request->search . '%')
                  ->orWhere('description->' . $locale, 'like', '%' . $request->search . '%')
                  ->orWhere('official_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by Business Type (Factory, Brand, Wholesaler)
        if ($request->filled('type') && in_array($request->type, ['Factory', 'Brand', 'Wholesaler'], true)) {
            $type = $request->type;
            $query->where(function ($q) use ($type) {
                if ($type === 'Brand') {
                    $q->where('business_type', 'like', '%Brand%');
                } elseif ($type === 'Wholesaler') {
                    $q->where(function ($sq) {
                        $sq->where('business_type', 'like', '%Wholesal%')
                           ->orWhere('business_type', 'like', '%Distributor%');
                    })->where('business_type', 'not like', '%Factory%')
                      ->where('business_type', 'not like', '%Manufacturer%');
                } else {
                    $q->where('business_type', 'Factory')
                      ->orWhere('business_type', 'like', '%Factory%')
                      ->orWhere('business_type', 'like', '%Manufacturer%')
                      ->orWhereNull('business_type');
                }
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Country
        if ($request->filled('country')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('code', $request->country);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->withCount('ratings')->orderBy('ratings_count', 'desc');
                break;
            case 'most_deals':
                $query->withCount('stockOffers')->orderBy('stock_offers_count', 'desc');
                break;
            case 'rating':
                $query->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating', 'desc');
                break;
            case 'az':
                $query->orderBy('official_name->' . app()->getLocale(), 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $factories = $query->paginate(12)->withQueryString();
        $categories = Category::where('type', 'product')->get();
        $countries = Country::where('status', 'active')->get();
        $bannerAds = BannerAd::forPage('factories')->get();

        return view('factories.index', compact('factories', 'categories', 'countries', 'bannerAds'));
    }

    public function show($locale, Factory $factory)
    {
        if ($factory->status !== 'approved') {
            abort(404);
        }

        $factory->load([
            'country',
            'category',
            'products' => function ($q) {
                $q->where('status', 'approved')->with('category')->latest();
            },
            'stockOffers' => function ($q) {
                $q->where('status', 'active')->latest();
            },
            'approvedRatings.buyer',
        ]);

        $canRate = $factory->canBeRatedBy(auth()->user());
        $userRating = auth()->check()
            ? $factory->ratings()->where('buyer_id', auth()->id())->first()
            : null;

        return view('factories.show', compact('factory', 'canRate', 'userRating'));
    }

    public function rate(Request $request, $locale, Factory $factory)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('rating_error', __('Please log in to submit a rating.'));
        }

        if (!$factory->canBeRatedBy(auth()->user())) {
            return redirect()->back()->with('rating_error', __('Only buyers who have contacted or requested a deal from this factory can submit a review.'));
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1500',
        ]);

        Rating::updateOrCreate(
            [
                'buyer_id' => auth()->id(),
                'factory_id' => $factory->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'status' => 'approved',
            ]
        );

        return redirect()
            ->to(route('factories.show', $factory) . '#reviews-section')
            ->with('rating_success', __('Thank you! Your factory rating and review have been published.'));
    }

    public function inquire(Request $request, $locale, Factory $factory)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('inquiry_error', __('Please log in to send a direct inquiry.'));
        }

        $validated = $request->validate([
            'topic' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|string|max:100',
            'product_id' => 'nullable|exists:products,id',
            'message' => 'required|string|min:5|max:4000',
        ]);

        $user = auth()->user();
        $product = !empty($validated['product_id']) ? \App\Models\Product::find($validated['product_id']) : null;
        $subject = !empty($validated['topic'])
            ? $validated['topic'] . ' - ' . $factory->official_name
            : ($product ? 'Product Inquiry: ' . $product->getTranslation('name', 'en') : 'Direct Inquiry for ' . $factory->official_name);

        $conversation = Conversation::create([
            'subject' => $subject,
            'type' => $product ? 'product_inquiry' : 'factory_inquiry',
            'status' => 'human_required',
            'factory_id' => $factory->id,
            'product_id' => $product?->id,
        ]);

        $conversation->participants()->create([
            'user_id' => $user->id,
            'role' => 'buyer',
        ]);

        $details = array_filter([
            'buyer_name' => $user->name,
            'buyer_email' => $user->email,
            'buyer_phone' => $validated['phone'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'quantity' => $validated['quantity'] ?? null,
            'topic' => $validated['topic'] ?? null,
            'product_name' => $product?->getTranslation('name', 'en'),
            'product_url' => $product ? route('marketplace.product.show', $product) : null,
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'type' => 'user',
            'content_original' => $validated['message'],
            'source_language' => strtoupper(app()->getLocale()),
            'metadata' => $details,
        ]);

        \App\Services\Communication\ConversationManager::notifyFactoryByEmail($conversation, $message, $details);

        if ($request->filled('redirect_to_product') && $product) {
            return redirect()
                ->route('marketplace.product.show', $product)
                ->with('inquiry_success', __('Your message has been sent directly to :name! Their sales team has been notified by email and in their Factory Portal.', ['name' => $factory->official_name]));
        }

        return redirect()
            ->to(route('factories.show', $factory) . '#inquiry-section')
            ->with('inquiry_success', __('Your message has been sent directly to :name! They have been notified on their account and by email.', ['name' => $factory->official_name]));
    }
}
