<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $col) {
            $col->id();
            $col->json('name'); // Translatable
            $col->string('slug')->unique();
            $col->string('type')->default('product'); // product, stock_offer, request
            $col->string('status')->default('active'); // active, inactive
            $col->integer('sort_order')->default(0);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
