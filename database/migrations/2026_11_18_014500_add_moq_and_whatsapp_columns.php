<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_offers') && !Schema::hasColumn('stock_offers', 'moq')) {
            Schema::table('stock_offers', function (Blueprint $table) {
                $table->unsignedInteger('moq')->nullable()->default(1)->after('price');
            });
        }

        if (Schema::hasTable('factories') && !Schema::hasColumn('factories', 'whatsapp')) {
            Schema::table('factories', function (Blueprint $table) {
                $table->string('whatsapp')->nullable()->after('phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stock_offers') && Schema::hasColumn('stock_offers', 'moq')) {
            Schema::table('stock_offers', function (Blueprint $table) {
                $table->dropColumn('moq');
            });
        }

        if (Schema::hasTable('factories') && Schema::hasColumn('factories', 'whatsapp')) {
            Schema::table('factories', function (Blueprint $table) {
                $table->dropColumn('whatsapp');
            });
        }
    }
};
