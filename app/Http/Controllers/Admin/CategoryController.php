<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('slug', 'like', '%'.$search.'%')))
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(
        StoreCategoryRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request
                ->file('image')
                ->store(
                    'categories',
                    'public'
                );
        }

        $data['is_active'] =
            $request->boolean('is_active');

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category berhasil dibuat.'
            );
    }

    public function edit(Category $category): View
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($category->image) {
                Storage::disk('public')
                    ->delete($category->image);
            }

            $data['image'] = $request
                ->file('image')
                ->store(
                    'categories',
                    'public'
                );
        }

        $data['is_active'] =
            $request->boolean('is_active');

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category berhasil diperbarui.'
            );
    }

    public function toggleStatus(
        Category $category
    ): RedirectResponse {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        return back()->with(
            'success',
            'Status category berhasil diperbarui.'
        );
    }

    public function destroy(
        Category $category
    ): RedirectResponse {

        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'Category masih digunakan oleh product.'
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'Category berhasil diarsipkan.'
        );
    }
}
