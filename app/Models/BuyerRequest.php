<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuyerRequest extends Model
{
    protected $fillable = [
        'buyer_id', 'category_id', 'subcategory_id', 'title', 'description',
        'quantity', 'target_price', 'currency', 'country_id', 'status',
        'contact_name', 'contact_email', 'contact_phone', 'admin_notes'
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(SupplierOffer::class);
    }

    public function unlocks(): HasMany
    {
        return $this->hasMany(BuyerRequestUnlock::class);
    }
}
