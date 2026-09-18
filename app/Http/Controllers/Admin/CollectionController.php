<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $collections = Collection::query()
            ->withCount('products')
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('slug', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')))
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $products = Product::query()
            ->orderBy('name')
            ->get();

        return view('admin.collections.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'slug' => $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name),
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'unique:collections,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'max:4096',
            ],

            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'products' => [
                'nullable',
                'array',
            ],

            'products.*' => [
                'exists:products,id',
            ],
        ]);

        $products =
            $validated['products'] ?? [];

        unset($validated['products']);

        $validated['is_active'] =
            $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] =
                $request
                    ->file('image')
                    ->store(
                        'collections',
                        'public'
                    );
        }

        $collection =
            Collection::create($validated);

        $collection
            ->products()
            ->sync($products);

        return redirect()
            ->route(
                'admin.collections.index'
            )
            ->with(
                'success',
                'Collection berhasil dibuat.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection): View
    {
        $collection->load('products');

        return view('admin.collections.show', compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection): View
    {
        $products = Product::query()
            ->orderBy('name')
            ->get();

        $collection->load('products');

        return view(
            'admin.collections.edit',
            compact('collection', 'products')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection): RedirectResponse
    {
        $request->merge([
            'slug' => $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('collections', 'slug')->ignore($collection),
            ],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'products' => ['nullable', 'array'],
            'products.*' => ['exists:products,id'],
        ]);

        // Di dalam method update(CollectionController.php)

        $products = $validated['products'] ?? [];
        unset($validated['products']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($collection->image) {
                Storage::disk('public')->delete($collection->image);
            }
            $validated['image'] = $request->file('image')->store('collections', 'public');
        }

        $collection->update($validated);

        // Perbarui bagian sync ini:
        if (! empty($products)) {
            // Ambil urutan yang sudah ada, jika tidak ada default 0
            $syncData = [];
            foreach ($products as $id) {
                $existingOrder = $collection->products()->where('product_id', $id)->first()?->pivot->display_order ?? 0;
                $syncData[$id] = ['display_order' => $existingOrder];
            }
            $collection->products()->sync($syncData);
        } else {
            $collection->products()->sync([]);
        }

        return redirect()->route('admin.collections.index')->with('success', 'Collection berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection): RedirectResponse
    {
        $collection->delete();

        return back()->with('success', 'Collection berhasil diarsipkan. Product tetap tersimpan.');
    }
}
