<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $permissions = collect(['products.create', 'products.update'])
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

    private function productData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Oversized Tee',
            'base_price' => 199000,
            'status' => 'DRAFT',
        ], $overrides);
    }

    public function test_admin_can_create_draft_product(): void
    {
        $category = Category::create(['name' => 'Tops', 'slug' => 'tops', 'is_active' => true]);

        $response = $this->actingAs($this->admin())->post(route('admin.products.store'), $this->productData([
            'category_id' => $category->id,
        ]));

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'slug' => 'oversized-tee',
            'status' => Product::STATUS_DRAFT,
            'base_price' => 199000,
        ]);
    }

    public function test_product_name_is_required(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.products.store'), [
            'base_price' => 100000,
            'status' => 'DRAFT',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_product_price_must_be_a_non_negative_integer(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.products.store'), $this->productData(['base_price' => -1]));

        $response->assertSessionHasErrors('base_price');
    }

    public function test_product_rejects_invalid_category_and_duplicate_slug(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('admin.products.store'), $this->productData(['category_id' => 999]))->assertSessionHasErrors('category_id');

        Product::create($this->productData(['slug' => 'oversized-tee']));
        $this->actingAs($admin)->post(route('admin.products.store'), $this->productData())->assertSessionHasErrors('slug');
    }

    public function test_product_can_belong_to_collections(): void
    {
        $collections = Collection::create(['name' => 'Summer 26', 'slug' => 'summer-26', 'is_active' => true]);
        $secondCollection = Collection::create(['name' => 'Essentials', 'slug' => 'essentials', 'is_active' => true]);

        $this->actingAs($this->admin())->post(route('admin.products.store'), $this->productData([
            'collections' => [$collections->id, $secondCollection->id],
        ]))->assertRedirect();

        $product = Product::firstOrFail();
        $this->assertCount(2, $product->collections);
    }

    public function test_product_can_be_archived_without_being_deleted(): void
    {
        $product = Product::create($this->productData(['slug' => 'oversized-tee']));

        $this->actingAs($this->admin())->patch(route('admin.products.archive', $product))->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => Product::STATUS_ARCHIVED,
            'deleted_at' => null,
        ]);
    }
}
