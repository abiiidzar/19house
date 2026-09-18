<?php

namespace Tests\Feature\Admin;

use App\Models\Collection;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);

        return User::create([
            'role_id' => $role->id,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'status' => 'ACTIVE',
        ]);
    }

    private function product(string $name, string $slug): Product
    {
        return Product::create([
            'name' => $name,
            'slug' => $slug,
            'base_price' => 199000,
            'status' => Product::STATUS_DRAFT,
        ]);
    }

    public function test_collection_can_be_created_with_multiple_products(): void
    {
        $products = [$this->product('Tee', 'tee'), $this->product('Shirt', 'shirt')];

        $this->actingAs($this->admin())->post(route('admin.collections.store'), [
            'name' => 'Summer 26',
            'products' => collect($products)->pluck('id')->all(),
        ])->assertRedirect(route('admin.collections.index'));

        $this->assertDatabaseHas('collections', ['slug' => 'summer-26']);
        $this->assertCount(2, Collection::firstOrFail()->products);
    }

    public function test_product_can_exist_in_multiple_collections(): void
    {
        $product = $this->product('Tee', 'tee');
        $first = Collection::create(['name' => 'Summer 26', 'slug' => 'summer-26']);
        $second = Collection::create(['name' => 'Essentials', 'slug' => 'essentials']);

        $first->products()->attach($product);
        $second->products()->attach($product);

        $this->assertCount(2, $product->fresh()->collections);
    }

    public function test_removing_product_from_collection_does_not_delete_product(): void
    {
        $admin = $this->admin();
        $product = $this->product('Tee', 'tee');
        $collection = Collection::create(['name' => 'Summer 26', 'slug' => 'summer-26']);
        $collection->products()->attach($product);

        $this->actingAs($admin)->put(route('admin.collections.update', $collection), [
            'name' => $collection->name,
            'products' => [],
        ])->assertRedirect(route('admin.collections.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('collection_product', ['collection_id' => $collection->id, 'product_id' => $product->id]);
    }

    public function test_deleting_collection_does_not_delete_products(): void
    {
        $product = $this->product('Tee', 'tee');
        $collection = Collection::create(['name' => 'Summer 26', 'slug' => 'summer-26']);
        $collection->products()->attach($product);

        $this->actingAs($this->admin())->delete(route('admin.collections.destroy', $collection))->assertRedirect();

        $this->assertSoftDeleted('collections', ['id' => $collection->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
}
