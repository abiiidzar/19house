<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login(): void
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

        $response = $this->post('/login', [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect('/');
    }
}
