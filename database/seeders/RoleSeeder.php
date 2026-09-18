<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
            ],
            [
                'name' => 'Management',
                'slug' => 'management',
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
