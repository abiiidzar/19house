<?php

namespace Tests\Feature\Admin;

use App\Models\HomepageSection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageContentTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::factory()->create(['role_id' => Role::factory()->create(['slug' => $role])->id, 'status' => 'ACTIVE']);
    }

    private function fields(array $overrides = []): array
    {
        return array_merge([
            'eyebrow' => 'New season',
            'title' => 'Summer Collection',
            'subtitle' => 'A new editorial story.',
            'button_label' => 'Discover',
            'button_path' => '/shop',
            'text_position' => 'left',
            'text_color' => 'light',
            'sort_order' => 0,
            'is_active' => 1,
        ], $overrides);
    }

    public function test_only_admin_can_manage_homepage_content(): void
    {
        $this->get(route('admin.homepage.index'))->assertRedirect(route('login'));
        $this->actingAs($this->user('customer'))->get(route('admin.homepage.index'))->assertForbidden();
        $this->actingAs($this->user('admin'))->get(route('admin.homepage.index'))->assertOk()->assertSee('Homepage Content');
    }

    public function test_hero_upload_is_visible_and_replacement_removes_previous_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user('admin'));
        $this->put(route('admin.homepage.hero.update'), $this->fields([
            'image' => UploadedFile::fake()->image('hero.jpg', 1600, 1000),
            'mobile_image' => UploadedFile::fake()->image('mobile.jpg', 900, 1200),
        ]))->assertRedirect();

        $hero = HomepageSection::where('slot', 'hero')->firstOrFail();
        Storage::disk('public')->assertExists([$hero->image_path, $hero->mobile_image_path]);
        $this->get(route('home'))->assertOk()->assertSee('Summer Collection')->assertSee(Storage::disk('public')->url($hero->image_path));

        $oldPath = $hero->image_path;
        $this->put(route('admin.homepage.hero.update'), $this->fields([
            'title' => 'Autumn Collection',
            'image' => UploadedFile::fake()->image('new-hero.jpg', 1600, 1000),
        ]))->assertRedirect();
        Storage::disk('public')->assertMissing($oldPath);
        $this->get(route('home'))->assertSee('Autumn Collection');
        $this->assertDatabaseHas('activity_logs', ['action' => 'homepage.section.updated']);
    }

    public function test_editorials_can_be_ordered_hidden_and_links_are_internal(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user('admin'));
        $this->post(route('admin.homepage.editorials.store'), $this->fields([
            'title' => 'Editorial One', 'sort_order' => 2,
            'image' => UploadedFile::fake()->image('one.jpg', 1200, 1200),
        ]))->assertRedirect();
        $this->post(route('admin.homepage.editorials.store'), $this->fields([
            'title' => 'Editorial Two', 'sort_order' => 1,
            'image' => UploadedFile::fake()->image('two.jpg', 1200, 1200),
        ]))->assertRedirect();

        $this->get(route('home'))->assertSeeInOrder(['Editorial Two', 'Editorial One']);
        $first = HomepageSection::where('title', 'Editorial One')->firstOrFail();
        $this->put(route('admin.homepage.editorials.update', $first), $this->fields([
            'title' => 'Editorial One', 'sort_order' => 2, 'is_active' => 0,
        ]))->assertRedirect();
        $this->get(route('home'))->assertDontSee('Editorial One')->assertSee('Editorial Two');

        $this->post(route('admin.homepage.editorials.store'), $this->fields([
            'button_path' => 'https://example.com',
            'image' => UploadedFile::fake()->image('bad.jpg', 1200, 1200),
        ]))->assertSessionHasErrors('button_path');
    }
}
