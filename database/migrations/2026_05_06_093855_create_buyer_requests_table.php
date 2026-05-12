<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buyer_requests', function (Blueprint $col) {
            $col->id();
            $col->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');
            $col->foreignId('category_id')->constrained()->onDelete('cascade');
            $col->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
            $col->string('title');
            $col->text('description')->nullable();
            $col->string('quantity')->nullable();
            $col->decimal('target_price', 15, 2)->nullable();
            $col->string('currency')->default('USD');
            $col->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $col->string('status')->default('pending'); // pending, approved, sent_to_suppliers, closed, cancelled
            $col->string('contact_name');
            $col->string('contact_email');
            $col->string('contact_phone')->nullable();
            $col->text('admin_notes')->nullable();
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyer_requests');
    }
};
