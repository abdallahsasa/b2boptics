<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $col) {
            $col->id();
            $col->string('name');
            $col->string('code', 10)->unique();
            $col->string('flag')->nullable();
            $col->string('status')->default('active'); // active, inactive
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
