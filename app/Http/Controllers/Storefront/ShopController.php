<?php

// app/Http/Controllers/Storefront/ShopController.php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        return $this->listing($request);
    }

    public function search(Request $request): View
    {
        return $this->listing($request, 'Search');
    }

    public function category(Request $request, Category $category): View
    {
        abort_unless($category->is_active, 404);
        $request->merge(['category' => $category->slug]);

        return $this->listing($request, $category->name);
    }

    public function collection(Request $request, Collection $collection): View
    {
        abort_unless($collection->is_active, 404);
        $request->merge(['collection' => $collection->slug]);

        return $this->listing($request, $collection->name);
    }

    private function listing(Request $request, string $heading = 'Shop All'): View
    {
        $query = Product::query()
            ->where('status', Product::STATUS_ACTIVE)
            ->with(['category', 'variants.images', 'variants.skus.stock', 'skus.stock']);

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%'));
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('collection')) {
            $query->whereHas('collections', fn ($q) => $q->where('slug', $request->collection));
        }

        match ($request->string('sort')->toString()) {
            'price_asc' => $query->orderBy('base_price'),
            'price_desc' => $query->orderByDesc('base_price'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();
        $collections = Collection::where('is_active', true)->orderBy('display_order')->get();

        return view('storefront.shop.index', compact('products', 'categories', 'collections', 'heading'));
    }
}
