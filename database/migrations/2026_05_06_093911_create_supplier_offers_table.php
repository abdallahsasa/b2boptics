<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_offers', function (Blueprint $col) {
            $col->id();
            $col->foreignId('buyer_request_id')->constrained()->onDelete('cascade');
            $col->foreignId('factory_id')->constrained()->onDelete('cascade');
            $col->decimal('offered_price', 15, 2)->nullable();
            $col->string('currency')->default('USD');
            $col->text('message');
            $col->string('delivery_time')->nullable();
            $col->string('status')->default('pending'); // pending, sent, accepted, rejected
            $col->boolean('admin_visible')->default(true);
            $col->boolean('buyer_visible')->default(false);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_offers');
    }
};
