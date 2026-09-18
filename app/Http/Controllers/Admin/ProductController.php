<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->withCount('collections')

            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $query->where(
                        'name',
                        'like',
                        '%'.$request->search.'%'
                    );
                }
            )

            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->status
                )
            )

            ->when(
                $request->filled('category'),
                fn ($query) => $query->where(
                    'category_id',
                    $request->category
                )
            )

            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.index',
            compact(
                'products',
                'categories'
            )
        );
    }

    public function create(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

        $collections = Collection::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view(
            'admin.products.create',
            compact(
                'categories',
                'collections'
            )
        );
    }

    public function store(
        StoreProductRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        $collections =
            $data['collections'] ?? [];

        unset($data['collections']);

        $data['is_featured'] =
            $request->boolean('is_featured');

        $product = Product::create($data);

        $product
            ->collections()
            ->sync($collections);

        return redirect()
            ->route(
                'admin.products.show',
                $product
            )
            ->with(
                'success',
                'Product berhasil dibuat.'
            );
    }

    public function show(
        Product $product
    ): View {

        $product->load([
            'category',
            'collections',
        ]);

        return view(
            'admin.products.show',
            compact('product')
        );
    }

    public function edit(
        Product $product
    ): View {

        $categories = Category::query()
            ->orderBy('display_order')
            ->get();

        $collections = Collection::query()
            ->orderBy('display_order')
            ->get();

        $product->load('collections');

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'collections'
            )
        );
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {

        $data = $request->validated();

        $collections =
            $data['collections'] ?? [];

        unset($data['collections']);

        $data['is_featured'] =
            $request->boolean('is_featured');

        $product->update($data);

        $product
            ->collections()
            ->sync($collections);

        return redirect()
            ->route(
                'admin.products.show',
                $product
            )
            ->with(
                'success',
                'Product berhasil diperbarui.'
            );
    }

    public function archive(
        Product $product
    ): RedirectResponse {

        $product->update([
            'status' => Product::STATUS_ARCHIVED,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product berhasil diarsipkan.'
            );
    }
}
