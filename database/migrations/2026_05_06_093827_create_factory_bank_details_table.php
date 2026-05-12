<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factory_bank_details', function (Blueprint $col) {
            $col->id();
            $col->foreignId('factory_id')->constrained()->onDelete('cascade');
            $col->string('account_name')->nullable();
            $col->string('holder_account_name')->nullable();
            $col->string('iban_number')->nullable();
            $col->string('account_number')->nullable();
            $col->string('bank_name')->nullable();
            $col->string('branch_name')->nullable();
            $col->string('branch_code')->nullable();
            $col->string('bank_address')->nullable();
            $col->string('swift_code')->nullable();
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factory_bank_details');
    }
};
