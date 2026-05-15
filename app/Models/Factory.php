<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Factory extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'official_name', 'slug', 'tax_number', 'contact_person_first_name',
        'contact_person_last_name', 'phone', 'email', 'country_id', 'language',
        'logo', 'banner', 'description', 'category_id', 'status', 'is_verified',
        'approved_at', 'approved_by'
    ];

    public $translatable = ['description'];

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
}
