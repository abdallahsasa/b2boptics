<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscription_packages')) {
            Schema::create('subscription_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0.00);
                $table->string('currency', 10)->default('USD');
                $table->string('billing_cycle', 30)->default('forever'); // forever, monthly, yearly
                $table->unsignedInteger('product_limit')->default(10);
                $table->unsignedInteger('max_images_per_product')->default(10);
                $table->json('features')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('factories', function (Blueprint $table) {
            if (!Schema::hasColumn('factories', 'subscription_package_id')) {
                $table->foreignId('subscription_package_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('subscription_packages')
                    ->nullOnDelete();
            }
        });

        // Seed the default Free Package if none exists
        $freePkgId = DB::table('subscription_packages')->where('slug', 'free')->value('id');

        if (!$freePkgId) {
            $freePkgId = DB::table('subscription_packages')->insertGetId([
                'name' => 'Free Starter Package',
                'slug' => 'free',
                'description' => 'Free factory membership with up to 10 product listings and up to 10 images per product.',
                'price' => 0.00,
                'currency' => 'USD',
                'billing_cycle' => 'forever',
                'product_limit' => 10,
                'max_images_per_product' => 10,
                'features' => json_encode([
                    'Up to 10 Product Listings for Free',
                    'Up to 10 High-Resolution Images per Product',
                    'Direct Customer Messaging & Email Notifications',
                    'Verified Factory Showroom Profile (Video & 360 VR)',
                    'Access to Buyer Tenders & Stock Deals',
                    'Multi-Language Catalog Support (9 Languages)',
                ]),
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign the Free Package to all existing factories that don't have one
        DB::table('factories')
            ->whereNull('subscription_package_id')
            ->update([
                'subscription_package_id' => $freePkgId,
                'subscription_plan' => 'free',
                'product_limit' => 10,
                'max_images_per_product' => 10,
            ]);
    }

    public function down(): void
    {
        Schema::table('factories', function (Blueprint $table) {
            if (Schema::hasColumn('factories', 'subscription_package_id')) {
                $table->dropConstrainedForeignId('subscription_package_id');
            }
        });

        Schema::dropIfExists('subscription_packages');
    }
};

