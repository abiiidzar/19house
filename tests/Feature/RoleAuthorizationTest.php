<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $role = Role::create([
            'name' => 'Customer',
            'slug' => 'customer',
        ]);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => 'password123',
            'status' => 'ACTIVE',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }
}
