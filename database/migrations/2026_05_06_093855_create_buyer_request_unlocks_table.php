<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buyer_request_unlocks', function (Blueprint $col) {
            $col->id();
            $col->foreignId('buyer_request_id')->constrained()->onDelete('cascade');
            $col->foreignId('factory_id')->constrained()->onDelete('cascade');
            $col->foreignId('unlocked_by')->nullable()->constrained('users')->onDelete('set null');
            $col->string('unlock_method')->default('manual'); // manual, credit, payment
            $col->decimal('amount', 15, 2)->nullable();
            $col->string('currency')->default('USD');
            $col->string('status')->default('unlocked'); // pending, unlocked, refunded
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyer_request_unlocks');
    }
};
