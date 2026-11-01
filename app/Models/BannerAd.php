<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BannerAd extends Model
{
    use HasTranslations;

    protected $fillable = [
        'page',
        'badge',
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_url',
        'order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    public $translatable = [
        'badge',
        'title',
        'subtitle',
        'button_text',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query
            ->where('is_active', true)
            ->whereIn('page', [$page, 'both'])
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderBy('order')
            ->latest();
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset('uploads/' . $this->image);
        }
        return null;
    }

    public function getResolvedButtonUrlAttribute(): ?string
    {
        if (!$this->button_url) {
            return null;
        }

        if (str_starts_with($this->button_url, 'http://') || str_starts_with($this->button_url, 'https://')) {
            return $this->button_url;
        }

        $locales = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];
        $trimmed = ltrim($this->button_url, '/');
        $firstSegment = explode('/', $trimmed)[0] ?? '';

        if (in_array($firstSegment, $locales, true)) {
            return url('/' . $trimmed);
        }

        return url('/' . app()->getLocale() . '/' . $trimmed);
    }
}

