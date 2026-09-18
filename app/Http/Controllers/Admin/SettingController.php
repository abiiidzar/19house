<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const FIELDS = ['store_name', 'support_email', 'support_phone', 'store_address', 'inventory_low_stock_threshold', 'shipping_regular_cost', 'shipping_express_cost', 'payment_expiry_minutes'];

    private const DEFAULTS = [
        'store_name' => '19HOUSE',
        'inventory_low_stock_threshold' => '5',
        'shipping_regular_cost' => '20000',
        'shipping_express_cost' => '40000',
        'payment_expiry_minutes' => '60',
    ];

    public function index(): View
    {
        $settings = Setting::whereIn('key', self::FIELDS)->pluck('value', 'key');

        return view('admin.settings.index', [
            'settings' => array_merge(self::DEFAULTS, $settings->all()),
            'loginEditorialImage' => Setting::valueFor('auth_login_image'),
            'loginEditorialAlt' => Setting::valueFor('auth_login_image_alt', '19HOUSE editorial photograph'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:30'],
            'store_address' => ['nullable', 'string', 'max:500'],
            'inventory_low_stock_threshold' => ['sometimes', 'required', 'integer', 'between:1,100'],
            'shipping_regular_cost' => ['sometimes', 'required', 'integer', 'between:0,10000000'],
            'shipping_express_cost' => ['sometimes', 'required', 'integer', 'between:0,10000000'],
            'payment_expiry_minutes' => ['sometimes', 'required', 'integer', 'between:5,1440'],
        ]);

        foreach (self::FIELDS as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }
            $setting = Setting::firstOrNew(['key' => $key]);
            $setting->value = $data[$key] ?? null;
            $setting->save();
        }

        return back()->with('success', 'Settings updated.');
    }

    public function updateLoginEditorial(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'alt' => ['nullable', 'string', 'max:160'],
            'remove_image' => ['sometimes', 'boolean'],
        ]);

        $current = Setting::firstOrNew(['key' => 'auth_login_image']);
        $oldPath = $current->value;
        $newPath = null;

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('auth/login', 'public');
        }

        try {
            DB::transaction(function () use ($current, $request, $data, $newPath) {
                if ($newPath || $request->boolean('remove_image')) {
                    $current->value = $newPath;
                    $current->save();
                }

                $alt = Setting::firstOrNew(['key' => 'auth_login_image_alt']);
                $alt->value = $data['alt'] ?? '19HOUSE editorial photograph';
                $alt->save();
            });
        } catch (\Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }
            throw $exception;
        }

        if ($oldPath && ($newPath || $request->boolean('remove_image')) && $oldPath !== $newPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('success', 'Login editorial image updated.');
    }
}
