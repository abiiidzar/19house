<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LoginEditorialImageTest extends TestCase
{
    use RefreshDatabase;

    private function account(string $role): User
    {
        return User::factory()->create(['role_id' => Role::factory()->create(['slug' => $role])->id, 'status' => 'ACTIVE']);
    }

    public function test_only_admin_can_upload_login_editorial_image_and_guest_sees_it(): void
    {
        Storage::fake('public');

        $this->actingAs($this->account('customer'))
            ->put(route('admin.settings.login-editorial.update'), ['alt' => 'Customer image'])
            ->assertForbidden();

        $this->actingAs($this->account('admin'))
            ->put(route('admin.settings.login-editorial.update'), [
                'alt' => 'Editorial fashion portrait',
                'image' => UploadedFile::fake()->image('login.jpg', 1200, 1600),
            ])->assertRedirect();

        $path = Setting::valueFor('auth_login_image');
        Storage::disk('public')->assertExists($path);
        $this->assertSame('Editorial fashion portrait', Setting::valueFor('auth_login_image_alt'));
        $this->assertDatabaseHas('activity_logs', ['action' => 'system.setting.created']);

        $this->post(route('logout'))->assertRedirect();
        $this->get(route('login'))->assertOk()
            ->assertSee(Storage::disk('public')->url($path))
            ->assertSee('Editorial fashion portrait');
    }

    public function test_image_can_be_replaced_and_removed_without_affecting_login_form(): void
    {
        Storage::fake('public');
        $this->actingAs($this->account('admin'));

        $this->put(route('admin.settings.login-editorial.update'), [
            'image' => UploadedFile::fake()->image('first.jpg', 1200, 1600),
        ])->assertRedirect();
        $old = Setting::valueFor('auth_login_image');

        $this->put(route('admin.settings.login-editorial.update'), [
            'image' => UploadedFile::fake()->image('second.jpg', 1200, 1600),
            'alt' => 'Second portrait',
        ])->assertRedirect();
        Storage::disk('public')->assertMissing($old);
        $new = Setting::valueFor('auth_login_image');
        Storage::disk('public')->assertExists($new);

        $this->put(route('admin.settings.login-editorial.update'), ['remove_image' => 1])->assertRedirect();
        Storage::disk('public')->assertMissing($new);
        $this->assertNull(Setting::valueFor('auth_login_image'));
        $this->post(route('logout'));
        $this->get(route('login'))->assertOk()->assertSee('Style for everyday movement.')->assertSee('SIGN IN');
    }

    public function test_invalid_upload_is_rejected(): void
    {
        Storage::fake('public');
        $this->actingAs($this->account('admin'))
            ->put(route('admin.settings.login-editorial.update'), [
                'image' => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
            ])->assertSessionHasErrors('image');

        $this->assertNull(Setting::valueFor('auth_login_image'));
    }
}
