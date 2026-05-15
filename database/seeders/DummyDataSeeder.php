<?php

namespace Database\Seeders;

use App\Models\BuyerRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Factory;
use App\Models\Language;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\StockOffer;
use App\Models\SupplierOffer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Countries & Languages
        $turkey = Country::firstOrCreate(['code' => 'TR'], [
            'name' => 'Turkey',
            'status' => 'active',
        ]);
        
        $china = Country::firstOrCreate(['code' => 'CN'], [
            'name' => 'China',
            'status' => 'active',
        ]);

        Language::firstOrCreate(['code' => 'en'], [
            'name' => 'English',
            'direction' => 'ltr',
            'status' => 'active',
        ]);

        // 2. Categories & Subcategories
        $categories = [
            'Lenses' => ['RX Lenses', 'Stock Lenses', 'Contact Lenses'],
            'Frames' => ['Optical Frames', 'Sunglasses', 'Sports Frames'],
            'Machinery' => ['Edgers', 'Generators', 'Blocking Machines'],
        ];

        foreach ($categories as $catName => $subs) {
            $category = Category::create([
                'name' => ['en' => $catName],
                'slug' => Str::slug($catName),
                'type' => 'product',
                'status' => 'active',
            ]);

            foreach ($subs as $subName) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => ['en' => $subName],
                    'slug' => Str::slug($subName),
                    'status' => 'active',
                ]);
            }
        }

        // 3. Users & Factories
        $factoryOwner = User::create([
            'name' => 'Optic Pro Factory',
            'email' => 'factory@opticpro.com',
            'password' => bcrypt('password'),
        ]);
        $factoryOwner->assignRole('factory_owner');

        $factory = Factory::create([
            'user_id' => $factoryOwner->id,
            'official_name' => 'Optic Pro Manufacturing Ltd',
            'slug' => Str::slug('Optic Pro Manufacturing Ltd'),
            'email' => 'info@opticpro.com',
            'phone' => '+90 555 123 4567',
            'country_id' => $turkey->id,
            'category_id' => Category::where('slug', 'lenses')->first()->id,
            'status' => 'approved',
            'description' => ['en' => 'Specialized in high-index RX lenses and coating technologies.'],
        ]);

        // 4. Products
        $lensSub = Subcategory::where('slug', 'rx-lenses')->first();
        Product::create([
            'factory_id' => $factory->id,
            'category_id' => $lensSub->category_id,
            'subcategory_id' => $lensSub->id,
            'name' => ['en' => 'Blue Cut RX 1.67'],
            'slug' => 'blue-cut-rx-167',
            'description' => ['en' => 'Premium blue light filtering lenses with AR coating.'],
            'starting_price' => 45.00,
            'currency' => 'USD',
            'status' => 'approved',
        ]);

        // 5. Buyer Requests
        $buyer = User::create([
            'name' => 'Retail Optician',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password'),
        ]);
        $buyer->assignRole('buyer');

        $request = BuyerRequest::create([
            'buyer_id' => $buyer->id,
            'category_id' => $lensSub->category_id,
            'subcategory_id' => $lensSub->id,
            'title' => 'Need 1000 pairs of CR-39 Clear',
            'description' => 'Looking for bulk supplier of CR-39 clear lenses, uncoated.',
            'quantity' => '1000',
            'target_price' => 2000.00,
            'currency' => 'USD',
            'status' => 'pending',
            'contact_name' => 'John Buyer',
            'contact_email' => 'buyer@example.com',
        ]);

        // 6. Stock Offers
        StockOffer::create([
            'factory_id' => $factory->id,
            'category_id' => $lensSub->category_id,
            'title' => ['en' => 'Clearance: Fashion Frames 2025'],
            'description' => ['en' => 'Overstock of 2025 model acetate frames.'],
            'price' => 12.50,
            'currency' => 'USD',
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(10),
        ]);

        // 7. Supplier Offers
        SupplierOffer::create([
            'buyer_request_id' => $request->id,
            'factory_id' => $factory->id,
            'offered_price' => 1800.00,
            'currency' => 'USD',
            'message' => 'We can provide these lenses within 15 days. High quality guaranteed.',
            'delivery_time' => '15 days',
            'status' => 'sent',
        ]);
    }
}
