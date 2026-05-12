<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call RoleSeeder first
        $this->call([
            RoleSeeder::class,
            DummyDataSeeder::class,
        ]);
    }
}
