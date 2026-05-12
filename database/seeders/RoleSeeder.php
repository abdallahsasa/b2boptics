<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super_admin',
            'admin',
            'moderator',
            'factory_owner',
            'factory_staff',
            'buyer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create a default super_admin
        $admin = User::firstOrCreate([
            'email' => 'admin@opticb2b.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('super_admin');
    }
}
