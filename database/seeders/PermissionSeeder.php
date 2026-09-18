<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'View Admin Dashboard', 'slug' => 'admin.dashboard.view'],

            ['name' => 'View Products', 'slug' => 'products.view'],
            ['name' => 'Create Products', 'slug' => 'products.create'],
            ['name' => 'Update Products', 'slug' => 'products.update'],

            ['name' => 'View Inventory', 'slug' => 'inventory.view'],
            ['name' => 'Adjust Inventory', 'slug' => 'inventory.adjust'],

            ['name' => 'View Orders', 'slug' => 'orders.view'],
            ['name' => 'Process Orders', 'slug' => 'orders.process'],

            ['name' => 'Access POS', 'slug' => 'pos.access'],
            ['name' => 'Create POS Transactions', 'slug' => 'pos.transaction.create'],
            ['name' => 'Apply Limited POS Discounts', 'slug' => 'pos.discount.apply'],

            ['name' => 'View Reports', 'slug' => 'reports.view'],
            ['name' => 'Export Reports', 'slug' => 'reports.export'],

            ['name' => 'Manage Users', 'slug' => 'users.manage'],
            ['name' => 'Manage Settings', 'slug' => 'settings.manage'],

            [
                'name' => 'View Categories',
                'slug' => 'categories.view',
            ],
            [
                'name' => 'Create Categories',
                'slug' => 'categories.create',
            ],
            [
                'name' => 'Update Categories',
                'slug' => 'categories.update',
            ],

            [
                'name' => 'View Collections',
                'slug' => 'collections.view',
            ],
            [
                'name' => 'Create Collections',
                'slug' => 'collections.create',
            ],
            [
                'name' => 'Update Collections',
                'slug' => 'collections.update',
            ],

            [
                'name' => 'View Sizes',
                'slug' => 'sizes.view',
            ],
            [
                'name' => 'Create Sizes',
                'slug' => 'sizes.create',
            ],
            [
                'name' => 'Update Sizes',
                'slug' => 'sizes.update',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
