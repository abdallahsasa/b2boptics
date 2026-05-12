<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'flag', 'status'];

    public function factories(): HasMany
    {
        return $this->hasMany(Factory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'country_of_origin_id');
    }

    public function buyerRequests(): HasMany
    {
        return $this->hasMany(BuyerRequest::class);
    }
}
