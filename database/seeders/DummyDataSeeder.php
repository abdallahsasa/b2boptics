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
            'specifications' => [
                'Transmittance' => '>95%',
                'Material' => 'Sr55',
                'Certification' => 'ISO9001, CE, FDA',
                'Coating' => 'HMC',
                'Coating Color' => 'Green Blue',
                'Abbe Value' => '35',
                'Specification' => '70/28mm',
                'Origin' => 'Danyang, Jiangsu, China',
                'Production Capacity' => '20000PCS/Day',
                'Shape' => 'Ball, Hemisphere',
                'Lenses Color' => 'Clear',
                'Customized' => 'Customized',
                'Power Range' => 'Sph -0.25~-2.00, +0.00~+3.00,Add +1.00~+3.00',
                'Specific Gravity' => '1.28',
                'Transport Package' => 'White Envelopes or as Customers Requirement',
                'Trademark' => 'BORIS',
                'HS Code' => '900150',
            ],
        ]);

        $borisOwner = User::create([
            'name' => 'Kay (Boris Optical)',
            'email' => 'sales@borisoptical.com',
            'password' => bcrypt('password'),
        ]);
        $borisOwner->assignRole('factory_owner');

        $borisFactory = Factory::create([
            'user_id' => $borisOwner->id,
            'official_name' => 'Danyang Boris Optical Co., Ltd.',
            'slug' => 'danyang-boris-optical',
            'email' => 'info@borisoptical.com',
            'phone' => '+86 511 8655 8888',
            'contact_person_first_name' => 'Kay',
            'contact_person_last_name' => '(Sales Director)',
            'country_id' => $china->id,
            'category_id' => Category::where('slug', 'lenses')->first()->id,
            'status' => 'approved',
            'is_verified' => true,
            'address' => 'Situ Glasses Industrial Zone, Danyang, Zhenjiang, Jiangsu, China',
            'business_type' => 'Manufacturer / Factory & Trading Company',
            'member_since' => '2017',
            'rating_score' => 5.0,
            'banner' => 'factories/boris_banner.jpg',
            'logo' => 'products/boris_optical_lens.jpg',
            'main_products' => [
                'Optical Lens', 'Cr39 Lens', '1.56 Lens', 'Blue Cut Lens',
                'Progressive Lens', 'Photochromic Lens', 'Bifocal Lens',
                'Polycarbonate Lens', 'Semi Finished Lens'
            ],
            'solutions' => [
                'OEM/ODM Service', 'Sample Available', 'Spanish Speaker', 'Factory Tour'
            ],
            'video_url' => 'https://v.made-in-china.com/ucv/sbr/8d9004a479a69c26e7e7f4e29e277b/b4a399ac7110208459751884092570_h264_def.mp4',
            'vr_url' => 'https://world-port.made-in-china.com/viewVR?comId=MqWErhSTgtHo',
            'description' => [
                'en' => "BORIS OPTICAL is one of the original and leading optical lens manufacturers in China, providing a full stock lens collection across 1.49, 1.56, 1.60, 1.67, and 1.74 indexes. We cover single vision, bifocal, and progressive designs, with specialized focus on photochromic series (photo blue cut, photo bifocal, photo progressive, and Rx lab lenses).\n\nAll lenses are produced from premium monomer materials including CR-39, MR-8, and MR-7. Our production workshops feature advanced high-vacuum ARC and HC coating lines operated by experienced technicians following strict ISO9001, CE, and FDA management standards. Every single lens undergoes comprehensive inspection through UC, HC, and HMC stages to guarantee uncompromised optical clarity and durability.",
            ],
        ]);

        Product::create([
            'factory_id' => $borisFactory->id,
            'category_id' => $lensSub->category_id,
            'subcategory_id' => $lensSub->id,
            'name' => ['en' => 'BORIS Sr55 HMC Optical Lens'],
            'slug' => 'boris-sr55-hmc-optical-lens',
            'description' => ['en' => 'Premium high-transmittance optical lens manufactured with Sr55 material and multi-layer green-blue HMC coating.'],
            'starting_price' => 52.00,
            'currency' => 'USD',
            'image' => 'products/boris_optical_lens.jpg',
            'country_of_origin_id' => $china->id,
            'status' => 'approved',
            'is_featured' => true,
            'specifications' => [
                'Transmittance' => '>95%',
                'Material' => 'Sr55',
                'Certification' => 'ISO9001, CE, FDA',
                'Coating' => 'HMC',
                'Coating Color' => 'Green Blue',
                'Abbe Value' => '35',
                'Specification' => '70/28mm',
                'Origin' => 'Danyang, Jiangsu, China',
                'Production Capacity' => '20000PCS/Day',
                'Shape' => 'Ball, Hemisphere',
                'Lenses Color' => 'Clear',
                'Customized' => 'Customized',
                'Power Range' => 'Sph -0.25~-2.00, +0.00~+3.00,Add +1.00~+3.00',
                'Specific Gravity' => '1.28',
                'Transport Package' => 'White Envelopes or as Customers Requirement',
                'Trademark' => 'BORIS',
                'HS Code' => '900150',
            ],
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
