<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Country;
use App\Models\Factory;
use App\Models\Language;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $this->call(RoleSeeder::class);

        // 2. Initial Data
        $country = Country::create([
            'name' => 'Turkey',
            'code' => 'TR',
            'status' => 'active',
        ]);

        Language::create([
            'name' => 'English',
            'code' => 'en',
            'direction' => 'ltr',
            'status' => 'active',
        ]);

        // 3. Categories
        $category = Category::create([
            'name' => ['en' => 'Lenses', 'tr' => 'Mercekler'],
            'slug' => 'lenses',
            'type' => 'product',
            'status' => 'active',
        ]);

        $subcategory = Subcategory::create([
            'category_id' => $category->id,
            'name' => ['en' => 'Resin', 'tr' => 'Reçine'],
            'slug' => 'resin',
            'status' => 'active',
        ]);

        // 4. Users & Factories
        $owner = User::create([
            'name' => 'Factory Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
        ]);
        $owner->assignRole('factory_owner');

        $factory = Factory::create([
            'user_id' => $owner->id,
            'official_name' => 'Optic Lens Factory',
            'email' => 'factory@example.com',
            'country_id' => $country->id,
            'category_id' => $category->id,
            'status' => 'approved',
            'description' => ['en' => 'We produce high quality lenses.'],
        ]);

        // 5. Products
        Product::create([
            'factory_id' => $factory->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => ['en' => 'Premium Lens X1'],
            'slug' => 'premium-lens-x1',
            'starting_price' => 50.00,
            'currency' => 'USD',
            'status' => 'approved',
        ]);
    }
}
