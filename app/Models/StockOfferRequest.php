<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOfferRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_offer_id',
        'user_id',
        'name',
        'email',
        'phone',
        'company_name',
        'quantity_requested',
        'message',
        'status',
    ];

    public function stockOffer(): BelongsTo
    {
        return $this->belongsTo(StockOffer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
