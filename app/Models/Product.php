<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'factory_id', 'category_id', 'subcategory_id', 'name', 'slug',
        'description', 'starting_price', 'currency', 'country_of_origin_id',
        'status', 'image', 'is_featured', 'published_at'
    ];

    public $translatable = ['name', 'description'];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function countryOfOrigin(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_of_origin_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function getFirstMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset('uploads/' . $this->image);
        }

        if (method_exists($this, 'getFirstMedia')) {
            $media = $this->getFirstMedia($collectionName);
            if ($media) {
                return $media->getUrl($conversionName);
            }
        }

        return '';
    }
}
