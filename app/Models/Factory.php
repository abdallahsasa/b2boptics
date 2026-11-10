<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Storage;

class Factory extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'official_name', 'slug', 'tax_number', 'contact_person_first_name',
        'contact_person_last_name', 'phone', 'email', 'country_id', 'language',
        'address', 'business_type', 'member_since', 'rating_score', 'main_products',
        'solutions', 'video_url', 'vr_url',
        'logo', 'banner', 'description', 'category_id', 'status', 'is_verified',
        'approved_at', 'approved_by',
        'subscription_package_id', 'subscription_plan', 'product_limit', 'max_images_per_product', 'subscription_expires_at',
    ];

    protected $casts = [
        'main_products' => 'array',
        'solutions' => 'array',
        'rating_score' => 'float',
        'is_verified' => 'boolean',
        'approved_at' => 'datetime',
        'product_limit' => 'integer',
        'max_images_per_product' => 'integer',
        'subscription_expires_at' => 'datetime',
    ];

    public function subscriptionPackage(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPackage::class, 'subscription_package_id');
    }

    public function activePackage(): ?SubscriptionPackage
    {
        return $this->subscriptionPackage ?: SubscriptionPackage::defaultPackage();
    }

    public function productLimit(): int
    {
        if (!empty($this->product_limit)) {
            return (int) $this->product_limit;
        }

        return (int) ($this->activePackage()?->product_limit ?: 10);
    }

    public function maxImagesPerProduct(): int
    {
        if (!empty($this->max_images_per_product)) {
            return (int) $this->max_images_per_product;
        }

        return (int) ($this->activePackage()?->max_images_per_product ?: 10);
    }

    public function productsCount(): int
    {
        return $this->products()->count();
    }

    public function remainingProductSlots(): int
    {
        return max(0, $this->productLimit() - $this->productsCount());
    }

    public function canCreateMoreProducts(): bool
    {
        return $this->productsCount() < $this->productLimit();
    }

    public function subscriptionPlanLabel(): string
    {
        if ($pkg = $this->activePackage()) {
            return __((string) $pkg->name);
        }

        return match ($this->subscription_plan ?: 'free') {
            'pro' => __('Pro Factory Plan'),
            'enterprise' => __('Enterprise Plan'),
            default => __('Free Starter Package'),
        };
    }

    public function subscriptionPriceLabel(): string
    {
        if ($pkg = $this->activePackage()) {
            return $pkg->formattedPrice();
        }

        return __('FREE ($0)');
    }

    public static function businessTypeOptions(): array
    {
        return [
            'Factory' => __('Factory'),
            'Brand' => __('Brand'),
            'Wholesaler' => __('Wholesaler'),
        ];
    }

    public function businessTypeKey(): string
    {
        $raw = strtolower(trim((string) $this->business_type));

        if (str_contains($raw, 'brand')) {
            return 'Brand';
        }

        if (str_contains($raw, 'wholesal') || str_contains($raw, 'distributor') || str_contains($raw, 'trading')) {
            if (!str_contains($raw, 'manufacturer') && !str_contains($raw, 'factory')) {
                return 'Wholesaler';
            }
        }

        return 'Factory';
    }

    public function businessTypeLabel(): string
    {
        return __(match ($this->businessTypeKey()) {
            'Brand' => 'Brand',
            'Wholesaler' => 'Wholesaler',
            default => 'Factory',
        });
    }

    public function businessTypeBadgeClasses(): string
    {
        return match ($this->businessTypeKey()) {
            'Brand' => 'bg-purple-50 text-purple-700 border-purple-200',
            'Wholesaler' => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }

    public $translatable = ['official_name', 'description'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bankDetails(): HasOne
    {
        return $this->hasOne(FactoryBankDetail::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function stockOffers(): HasMany
    {
        return $this->hasMany(StockOffer::class);
    }

    public function supplierOffers(): HasMany
    {
        return $this->hasMany(SupplierOffer::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function approvedRatings(): HasMany
    {
        return $this->hasMany(Rating::class)->where('status', 'approved')->latest();
    }

    public function canBeRatedBy(?User $user): bool
    {
        if (!$user || (int) $this->user_id === (int) $user->id) {
            return false;
        }

        // 1. Contacted factory via product chat or direct factory inquiry
        $hasConversation = Conversation::where('factory_id', $this->id)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->exists();

        if ($hasConversation) {
            return true;
        }

        // 2. Requested a stock deal from this factory
        $hasStockRequest = StockOfferRequest::where('user_id', $user->id)
            ->whereHas('stockOffer', fn ($q) => $q->where('factory_id', $this->id))
            ->exists();

        if ($hasStockRequest) {
            return true;
        }

        // 3. Received a supplier offer from this factory on a sourcing request
        return SupplierOffer::where('factory_id', $this->id)
            ->whereHas('buyerRequest', fn ($q) => $q->where('buyer_id', $user->id))
            ->exists();
    }

    public function recalculateRatingScore(): void
    {
        $avg = $this->ratings()->where('status', 'approved')->avg('rating');
        $this->update([
            'rating_score' => $avg ? round((float) $avg, 1) : 5.0,
        ]);
    }

    protected static function booted()
    {
        static::saving(function ($factory) {
            if (empty($factory->business_type)) {
                $factory->business_type = 'Factory';
            }

            if (empty($factory->getTranslations('official_name'))) {
                $fallbackName = $factory->owner?->name ?: 'Factory';
                $factory->setTranslation('official_name', 'en', $fallbackName);
            }

            if (empty($factory->slug)) {
                $nameForSlug = (string) ($factory->official_name ?: $factory->getTranslation('official_name', 'en', false));
                $factory->slug = \Illuminate\Support\Str::slug($nameForSlug) ?: 'factory-' . time();
            }

            // Ensure slug uniqueness
            $baseSlug = $factory->slug;
            $counter = 1;
            while (
                static::where('slug', $factory->slug)
                    ->when($factory->id, fn ($q) => $q->where('id', '!=', $factory->id))
                    ->exists()
            ) {
                $factory->slug = $baseSlug . '-' . $counter++;
            }

            if (empty($factory->subscription_package_id)) {
                $defaultPkg = SubscriptionPackage::defaultPackage();
                if ($defaultPkg) {
                    $factory->subscription_package_id = $defaultPkg->id;
                    $factory->subscription_plan = $defaultPkg->slug;
                    $factory->product_limit = $factory->product_limit ?: $defaultPkg->product_limit;
                    $factory->max_images_per_product = $factory->max_images_per_product ?: $defaultPkg->max_images_per_product;
                }
            } elseif ($factory->isDirty('subscription_package_id')) {
                $pkg = SubscriptionPackage::find($factory->subscription_package_id);
                if ($pkg) {
                    $factory->subscription_plan = $pkg->slug;
                    $factory->product_limit = $pkg->product_limit;
                    $factory->max_images_per_product = $pkg->max_images_per_product;
                }
            }
        });
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
                return $this->logo;
            }
            return asset('uploads/' . $this->logo);
        }
        if ($this->hasMedia('logos')) {
            return $this->getFirstMediaUrl('logos');
        }
        if ($this->hasMedia('factory_logos')) {
            return $this->getFirstMediaUrl('factory_logos');
        }
        return null;
    }

    public function getBannerUrlAttribute(): ?string
    {
        if ($this->banner) {
            if (str_starts_with($this->banner, 'http://') || str_starts_with($this->banner, 'https://')) {
                return $this->banner;
            }
            return asset('uploads/' . $this->banner);
        }
        if ($this->hasMedia('banners')) {
            return $this->getFirstMediaUrl('banners');
        }
        if ($this->hasMedia('factory_banners')) {
            return $this->getFirstMediaUrl('factory_banners');
        }
        return null;
    }
}
