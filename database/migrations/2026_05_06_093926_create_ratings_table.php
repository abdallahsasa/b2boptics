<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $col) {
            $col->id();
            $col->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $col->foreignId('factory_id')->nullable()->constrained()->onDelete('set null');
            $col->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $col->integer('rating'); // 1-5
            $col->text('comment')->nullable();
            $col->string('status')->default('pending'); // pending, approved, rejected
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
