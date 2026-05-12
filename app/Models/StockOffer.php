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
}
