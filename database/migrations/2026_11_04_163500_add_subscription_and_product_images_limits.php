<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factories', function (Blueprint $table) {
            if (!Schema::hasColumn('factories', 'subscription_plan')) {
                $table->string('subscription_plan', 50)->default('free')->after('status');
            }
            if (!Schema::hasColumn('factories', 'product_limit')) {
                $table->unsignedInteger('product_limit')->default(10)->after('subscription_plan');
            }
            if (!Schema::hasColumn('factories', 'max_images_per_product')) {
                $table->unsignedInteger('max_images_per_product')->default(10)->after('product_limit');
            }
            if (!Schema::hasColumn('factories', 'subscription_expires_at')) {
                $table->timestamp('subscription_expires_at')->nullable()->after('max_images_per_product');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'images')) {
                $table->json('images')->nullable()->after('image');
            }
        });

        // Backfill existing products so `images` contains [`image`] if `image` is present
        DB::table('products')
            ->whereNotNull('image')
            ->whereNull('images')
            ->orderBy('id')
            ->chunk(100, function ($products) {
                foreach ($products as $product) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['images' => json_encode([$product->image])]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('factories', function (Blueprint $table) {
            $cols = array_filter(
                ['subscription_plan', 'product_limit', 'max_images_per_product', 'subscription_expires_at'],
                fn ($c) => Schema::hasColumn('factories', $c)
            );
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'images')) {
                $table->dropColumn('images');
            }
        });
    }
};

