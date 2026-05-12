<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factories', function (Blueprint $col) {
            $col->id();
            $col->foreignId('user_id')->constrained()->onDelete('cascade'); // owner_id
            $col->string('official_name');
            $col->string('tax_number')->nullable();
            $col->string('contact_person_first_name')->nullable();
            $col->string('contact_person_last_name')->nullable();
            $col->string('phone')->nullable();
            $col->string('email')->nullable();
            $col->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
            $col->string('language')->default('en');
            $col->string('logo')->nullable();
            $col->string('banner')->nullable();
            $col->json('description')->nullable(); // Translatable
            $col->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $col->string('status')->default('pending'); // pending, approved, rejected, suspended
            $col->boolean('is_verified')->default(false);
            $col->timestamp('approved_at')->nullable();
            $col->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factories');
    }
};
