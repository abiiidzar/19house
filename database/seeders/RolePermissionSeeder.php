<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('slug', 'admin')->firstOrFail();
        $cashier = Role::where('slug', 'cashier')->firstOrFail();
        $management = Role::where('slug', 'management')->firstOrFail();

        $admin->permissions()->sync(
            Permission::pluck('id')->all()
        );

        $cashier->permissions()->sync(
            Permission::whereIn('slug', [
                'pos.access',
                'pos.transaction.create',
                'pos.discount.apply',
            ])->pluck('id')->all()
        );

        $management->permissions()->sync(
            Permission::whereIn('slug', [
                'reports.view',
                'reports.export',
            ])->pluck('id')->all()
        );
    }
}
