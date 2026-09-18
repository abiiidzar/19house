<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function index(): View
    {
        return view('admin.homepage.index', [
            'hero' => HomepageSection::where('slot', 'hero')->first(),
            'editorials' => HomepageSection::where('type', 'editorial')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function saveHero(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $hero = HomepageSection::firstOrNew(['slot' => 'hero']);
        $hero->type = 'hero';
        $this->persist($hero, $request, $data);

        return back()->with('success', 'Hero homepage berhasil disimpan.');
    }

    public function storeEditorial(Request $request): RedirectResponse
    {
        $data = $this->validated($request, true);
        $section = new HomepageSection(['type' => 'editorial']);
        $this->persist($section, $request, $data);

        return redirect()->route('admin.homepage.index')->with('success', 'Editorial berhasil ditambahkan.');
    }

    public function updateEditorial(Request $request, HomepageSection $section): RedirectResponse
    {
        abort_unless($section->type === 'editorial', 404);
        $data = $this->validated($request);
        $this->persist($section, $request, $data);

        return redirect()->route('admin.homepage.index')->with('success', 'Editorial berhasil diperbarui.');
    }

    private function validated(Request $request, bool $newEditorial = false): array
    {
        return $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'button_label' => ['nullable', 'required_with:button_path', 'string', 'max:60'],
            'button_path' => ['nullable', 'required_with:button_label', 'string', 'max:255', 'regex:~\A/(?!/)[a-zA-Z0-9_/.?&=%#-]*\z~'],
            'image' => [$newEditorial ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'remove_image' => ['sometimes', 'boolean'],
            'remove_mobile_image' => ['sometimes', 'boolean'],
            'text_position' => ['required', Rule::in(['left', 'center'])],
            'text_color' => ['required', Rule::in(['light', 'dark'])],
            'sort_order' => ['nullable', 'integer', 'between:0,999'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function persist(HomepageSection $section, Request $request, array $data): void
    {
        if ($section->type === 'editorial' && $request->boolean('remove_image') && ! $request->hasFile('image')) {
            throw ValidationException::withMessages(['image' => 'Editorial harus memiliki gambar utama.']);
        }
        $oldImage = $section->image_path;
        $oldMobileImage = $section->mobile_image_path;
        $uploaded = [];
        unset($data['image'], $data['mobile_image'], $data['remove_image'], $data['remove_mobile_image']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $section->fill($data);

        if ($request->hasFile('image')) {
            $section->image_path = $request->file('image')->store('homepage', 'public');
            $uploaded[] = $section->image_path;
        } elseif ($request->boolean('remove_image')) {
            $section->image_path = null;
        }
        if ($request->hasFile('mobile_image')) {
            $section->mobile_image_path = $request->file('mobile_image')->store('homepage', 'public');
            $uploaded[] = $section->mobile_image_path;
        } elseif ($request->boolean('remove_mobile_image')) {
            $section->mobile_image_path = null;
        }

        try {
            $section->save();
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($uploaded);
            throw $exception;
        }

        foreach ([[$oldImage, $section->image_path], [$oldMobileImage, $section->mobile_image_path]] as [$old, $new]) {
            if ($old && $old !== $new && ! in_array($old, [$section->image_path, $section->mobile_image_path], true)) {
                Storage::disk('public')->delete($old);
            }
        }
    }
}
