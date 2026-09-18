<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->firstOrFail();
        $cashierRole = Role::where('slug', 'cashier')->firstOrFail();
        $managementRole = Role::where('slug', 'management')->firstOrFail();
        $customerRole = Role::where('slug', 'customer')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@19house.com'],
            [
                'role_id' => $adminRole->id,
                'name' => '19HOUSE Admin',
                'password' => 'password',
                'status' => 'ACTIVE',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cashier@19house.com'],
            [
                'role_id' => $cashierRole->id,
                'name' => '19HOUSE Cashier',
                'password' => 'password',
                'status' => 'ACTIVE',
            ]
        );

        User::updateOrCreate(
            ['email' => 'management@19house.com'],
            [
                'role_id' => $managementRole->id,
                'name' => '19HOUSE Management',
                'password' => 'password',
                'status' => 'ACTIVE',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@19house.com'],
            [
                'role_id' => $customerRole->id,
                'name' => '19HOUSE Customer',
                'password' => 'password',
                'status' => 'ACTIVE',
            ]
        );
    }
}
