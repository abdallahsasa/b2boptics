<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_offers', function (Blueprint $col) {
            $col->id();
            $col->foreignId('factory_id')->constrained()->onDelete('cascade');
            $col->foreignId('category_id')->constrained()->onDelete('cascade');
            $col->json('title'); // Translatable
            $col->json('description')->nullable(); // Translatable
            $col->decimal('price', 15, 2)->nullable();
            $col->string('currency')->default('USD');
            $col->string('image')->nullable();
            $col->string('pdf_file')->nullable();
            $col->integer('duration_days')->default(3);
            $col->decimal('price_for_duration', 15, 2)->default(5.00);
            $col->string('status')->default('pending'); // pending, active, expired, rejected
            $col->timestamp('starts_at')->nullable();
            $col->timestamp('ends_at')->nullable();
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_offers');
    }
};
