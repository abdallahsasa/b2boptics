<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactoryBankDetail extends Model
{
    protected $fillable = [
        'factory_id', 'account_name', 'holder_account_name', 'iban_number',
        'account_number', 'bank_name', 'branch_name', 'branch_code',
        'bank_address', 'swift_code'
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }
}
