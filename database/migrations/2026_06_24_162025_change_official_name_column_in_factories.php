<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convert existing plain string names to JSON format
        $factories = DB::table('factories')->get();
        foreach ($factories as $factory) {
            $name = $factory->official_name;
            if (is_string($name) && !(str_starts_with(trim($name), '{') && str_ends_with(trim($name), '}'))) {
                $jsonName = json_encode(['en' => $name]);
                DB::table('factories')->where('id', $factory->id)->update([
                    'official_name' => $jsonName
                ]);
            }
        }

        // 2. Change official_name to json
        Schema::table('factories', function (Blueprint $table) {
            $table->json('official_name')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factories', function (Blueprint $table) {
            $table->string('official_name')->change();
        });
    }
};
