<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('factories', function (Blueprint $table) {
            $table->string('address')->nullable()->after('language');
            $table->string('business_type')->nullable()->default('Manufacturer / Factory')->after('address');
            $table->string('member_since')->nullable()->default('2017')->after('business_type');
            $table->decimal('rating_score', 3, 1)->default(5.0)->after('member_since');
            $table->json('main_products')->nullable()->after('rating_score');
            $table->json('solutions')->nullable()->after('main_products');
            $table->string('video_url')->nullable()->after('solutions');
            $table->string('vr_url')->nullable()->after('video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factories', function (Blueprint $table) {
            $table->dropColumn([
                'address', 'business_type', 'member_since', 'rating_score',
                'main_products', 'solutions', 'video_url', 'vr_url'
            ]);
        });
    }
};
