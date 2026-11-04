<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPackage extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'billing_cycle',
        'product_limit',
        'max_images_per_product',
        'features',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'float',
        'product_limit' => 'integer',
        'max_images_per_product' => 'integer',
        'features' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function factories(): HasMany
    {
        return $this->hasMany(Factory::class, 'subscription_package_id');
    }

    public function isFree(): bool
    {
        return (float) $this->price <= 0;
    }

    public function formattedPrice(): string
    {
        if ($this->isFree()) {
            return __('FREE ($0)');
        }

        $cycle = match ($this->billing_cycle) {
            'monthly' => '/' . __('month'),
            'yearly' => '/' . __('year'),
            default => '',
        };

        return $this->currency . ' ' . number_format((float) $this->price, 2) . $cycle;
    }

    public static function defaultPackage(): ?self
    {
        return static::where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->first();
    }
}

