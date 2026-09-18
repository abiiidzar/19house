<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminSearchTest extends TestCase
{
    use RefreshDatabase;

    public static function searchableAdminPages(): array
    {
        return [
            ['admin.products.index'],
            ['admin.categories.index'],
            ['admin.collections.index'],
            ['admin.sizes.index'],
            ['admin.inventory.index'],
            ['admin.inventory.movements.index'],
            ['admin.orders.index'],
            ['admin.payments.index'],
            ['admin.cancellations.index'],
            ['admin.customers.index'],
            ['admin.users.index'],
            ['admin.vouchers.index'],
            ['admin.notifications.index'],
            ['admin.activity-logs.index'],
            ['admin.roles.index'],
        ];
    }

    #[DataProvider('searchableAdminPages')]
    public function test_admin_list_pages_accept_a_search_query(string $route): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $role->id,
            'status' => 'ACTIVE',
        ]);

        $this->actingAs($admin)
            ->get(route($route, ['search' => 'not-found']))
            ->assertOk()
            ->assertSee('value="not-found"', false);
    }
}
