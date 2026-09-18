<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SizeController extends Controller
{
    public function index(Request $request): View
    {
        $sizes = Size::query()
            ->with([
                'skus' => fn ($query) => $query
                    ->with(['product:id,name,slug,base_price', 'variant:id,name', 'stock:product_sku_id,on_hand,reserved'])
                    ->orderBy('product_id')
                    ->orderBy('product_variant_id'),
            ])
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('slug', 'like', '%'.$search.'%')
                ->orWhereHas('skus', fn ($query) => $query
                    ->where('sku', 'like', '%'.$search.'%')
                    ->orWhereHas('product', fn ($query) => $query->where('name', 'like', '%'.$search.'%')))))
            ->orderBy('display_order')
            ->get();

        return view(
            'admin.sizes.index',
            compact('sizes')
        );
    }

    public function create(): View
    {
        return view('admin.sizes.create');
    }

    public function store(
        Request $request
    ): RedirectResponse {

        $request->merge([
            'slug' => Str::slug(
                $request->name
            ),
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:20',
            ],

            'slug' => [
                'required',
                'unique:sizes,slug',
            ],

            'display_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        Size::create($validated);

        return redirect()
            ->route('admin.sizes.index')
            ->with(
                'success',
                'Size berhasil dibuat.'
            );
    }

    public function edit(Size $size): View
    {
        return view(
            'admin.sizes.edit',
            compact('size')
        );
    }

    public function update(
        Request $request,
        Size $size
    ): RedirectResponse {

        $request->merge([
            'slug' => Str::slug(
                $request->name
            ),
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:20',
            ],

            'slug' => [
                'required',

                Rule::unique('sizes', 'slug')
                    ->ignore($size->id),
            ],

            'display_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        $size->update($validated);

        return redirect()
            ->route('admin.sizes.index')
            ->with(
                'success',
                'Size berhasil diperbarui.'
            );
    }
}
