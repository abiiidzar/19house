<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('customer.profile.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_profile_photo' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $oldPhotoPath = $user->profile_photo_path;
        $newPhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $newPhotoPath = $request->file('profile_photo')->store('profiles', 'public');
        }

        $shouldRemovePhoto = $request->boolean('remove_profile_photo') && ! $newPhotoPath;

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'profile_photo_path' => $newPhotoPath ?? ($shouldRemovePhoto ? null : $oldPhotoPath),
        ]);

        if ($oldPhotoPath && ($newPhotoPath || $shouldRemovePhoto)) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'different:current_password', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update(['password' => $validated['password']]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
