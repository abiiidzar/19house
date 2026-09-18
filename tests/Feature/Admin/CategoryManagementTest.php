<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $permissions = collect(['categories.create', 'categories.update'])
            ->map(fn (string $slug) => Permission::create(['name' => $slug, 'slug' => $slug]));
        $role->permissions()->attach($permissions->pluck('id'));

        return User::create([
            'role_id' => $role->id,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'status' => 'ACTIVE',
        ]);
    }

    public function test_admin_can_access_category_page(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.categories.index'));

        $response->assertOk()->assertViewIs('admin.categories.index');
    }

    public function test_customer_cannot_access_category_page(): void
    {
        $role = Role::create(['name' => 'Customer', 'slug' => 'customer']);
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => 'password',
            'status' => 'ACTIVE',
        ]);

        $this->actingAs($user)->get(route('admin.categories.index'))->assertForbidden();
    }

    public function test_admin_can_create_category_with_generated_slug(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.categories.store'), [
            'name' => 'Summer Collection',
            'description' => 'Seasonal products',
            'display_order' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Summer Collection', 'slug' => 'summer-collection']);
    }

    public function test_category_slug_must_be_unique(): void
    {
        Category::create(['name' => 'Existing', 'slug' => 'existing', 'display_order' => 1, 'is_active' => true]);

        $response = $this->actingAs($this->admin())->post(route('admin.categories.store'), [
            'name' => 'Another',
            'slug' => 'existing',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_inactive_category_is_persisted(): void
    {
        $this->actingAs($this->admin())->post(route('admin.categories.store'), [
            'name' => 'Hidden Category',
            'is_active' => 0,
        ]);

        $this->assertDatabaseHas('categories', ['slug' => 'hidden-category', 'is_active' => 0]);
    }
}
