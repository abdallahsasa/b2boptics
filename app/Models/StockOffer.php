<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class StockOffer extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'factory_id', 'category_id', 'title', 'description', 'price',
        'currency', 'image', 'pdf_file', 'duration_days', 'price_for_duration',
        'status', 'starts_at', 'ends_at'
    ];

    public $translatable = ['title', 'description'];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset('uploads/' . $this->image);
        }

        if (method_exists($this, 'getFirstMedia')) {
            $media = $this->getFirstMedia('stock_offer_images');
            if ($media) {
                return $media->getUrl();
            }
        }

        return null;
    }

    public function getImagesAttribute(): array
    {
        $url = $this->image_url;
        return $url ? [$url] : [];
    }
}
