<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    private function createUser(string $roleSlug): User
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();

        return User::factory()->create([
            'role_id' => $role->id,
            'status' => 'ACTIVE',
        ]);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $user = $this->createUser('admin');

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_cashier_cannot_access_admin_dashboard(): void
    {
        $user = $this->createUser('cashier');

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403); // Forbidden
    }

    public function test_management_can_access_management_dashboard(): void
    {
        $user = $this->createUser('management');

        $response = $this->actingAs($user)->get(route('management.dashboard'));

        $response->assertStatus(200);
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $user = $this->createUser('customer');

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $role = Role::where('slug', 'customer')->firstOrFail();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'status' => 'INACTIVE',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
