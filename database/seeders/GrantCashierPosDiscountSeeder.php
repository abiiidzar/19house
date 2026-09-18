<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class GrantCashierPosDiscountSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('slug', 'cashier')->first();
        if (! $role) {
            return;
        }

        $permission = Permission::firstOrCreate(
            ['slug' => 'pos.discount.apply'],
            ['name' => 'Apply Limited POS Discounts'],
        );
        $role->permissions()->syncWithoutDetaching([$permission->id]);
    }
}
