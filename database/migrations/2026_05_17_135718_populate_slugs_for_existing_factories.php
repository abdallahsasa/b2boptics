<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Factory;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any missing slugs in the factories table
        Factory::whereNull('slug')->orWhere('slug', '')->get()->each(function ($factory) {
            $factory->slug = Str::slug($factory->official_name);
            $factory->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed
    }
};
