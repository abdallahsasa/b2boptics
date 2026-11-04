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
        'status', 'image', 'images', 'is_featured', 'published_at', 'specifications'
    ];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public $translatable = ['name', 'description'];

    protected static function booted()
    {
        static::saving(function ($product) {
            if (empty($product->slug) && !empty($product->name)) {
                $nameStr = is_array($product->name) ? ($product->name['en'] ?? reset($product->name)) : $product->name;
                $baseSlug = \Illuminate\Support\Str::slug($nameStr) ?: 'product-' . time();
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $product->id ?? 0)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }
                $product->slug = $slug;
            }

            $maxImages = $product->factory ? $product->factory->maxImagesPerProduct() : 10;

            if (is_array($product->images)) {
                $cleanImages = array_values(array_filter($product->images));
                $cleanImages = array_slice($cleanImages, 0, $maxImages);
                $product->images = $cleanImages;
                $product->image = $cleanImages[0] ?? null;
            } elseif (!empty($product->image) && empty($product->images)) {
                $product->images = [$product->image];
            }
        });
    }

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
        $firstImage = $this->image;
        if (!$firstImage && is_array($this->images) && !empty($this->images)) {
            $firstImage = reset($this->images);
        }

        if ($firstImage) {
            if (str_starts_with($firstImage, 'http://') || str_starts_with($firstImage, 'https://')) {
                return $firstImage;
            }
            return asset('uploads/' . $firstImage);
        }

        if (method_exists($this, 'getFirstMedia')) {
            $media = $this->getFirstMedia($collectionName);
            if ($media) {
                return $media->getUrl($conversionName);
            }
        }

        return '';
    }

    public function getGalleryUrlsAttribute(): array
    {
        $rawList = [];

        if (is_array($this->images) && !empty($this->images)) {
            $rawList = $this->images;
        } elseif (!empty($this->image)) {
            $rawList = [$this->image];
        }

        $urls = [];
        foreach ($rawList as $img) {
            if (!$img) {
                continue;
            }
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                $urls[] = $img;
            } else {
                $urls[] = asset('uploads/' . $img);
            }
        }

        if (empty($urls) && method_exists($this, 'getMedia')) {
            foreach ($this->getMedia('images') as $media) {
                $urls[] = $media->getUrl();
            }
        }

        return array_slice(array_values(array_unique($urls)), 0, 10);
    }
}
