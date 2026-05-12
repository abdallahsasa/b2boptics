<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $col) {
            $col->id();
            $col->foreignId('factory_id')->constrained()->onDelete('cascade');
            $col->foreignId('category_id')->constrained()->onDelete('cascade');
            $col->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
            $col->json('name'); // Translatable
            $col->string('slug')->unique();
            $col->json('description')->nullable(); // Translatable
            $col->decimal('starting_price', 15, 2)->nullable();
            $col->string('currency')->default('USD');
            $col->foreignId('country_of_origin_id')->nullable()->constrained('countries')->onDelete('set null');
            $col->string('status')->default('draft'); // draft, pending, approved, rejected, inactive
            $col->string('image')->nullable();
            $col->boolean('is_featured')->default(false);
            $col->timestamp('published_at')->nullable();
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
