<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcategories', function (Blueprint $col) {
            $col->id();
            $col->foreignId('category_id')->constrained()->onDelete('cascade');
            $col->json('name'); // Translatable
            $col->string('slug')->unique();
            $col->string('status')->default('active');
            $col->integer('sort_order')->default(0);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
