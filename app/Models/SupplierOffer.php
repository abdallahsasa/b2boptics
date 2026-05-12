<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierOffer extends Model
{
    protected $fillable = [
        'buyer_request_id', 'factory_id', 'offered_price', 'currency',
        'message', 'delivery_time', 'status', 'admin_visible', 'buyer_visible'
    ];

    public function buyerRequest(): BelongsTo
    {
        return $this->belongsTo(BuyerRequest::class);
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }
}
