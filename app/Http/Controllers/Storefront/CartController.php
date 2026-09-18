<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Cart\CartService;
use App\Services\Catalog\PriceResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $service, PriceResolver $resolver): View
    {
        $items = $service->getCartItems();
        $subtotal = 0;

        foreach ($items as $item) {
            $sku = $item->sku;
            $isAvailable = $sku
                && $sku->is_active
                && $sku->variant?->is_active
                && $sku->product?->status === Product::STATUS_ACTIVE;
            $availableStock = $sku?->stock?->available ?? 0;

            $item->is_unavailable = !$isAvailable || $availableStock <= 0;
            $item->max_stock = null;

            if (!$item->is_unavailable) {
                $subtotal += $resolver->resolve($sku) * $item->quantity;

                if ($item->quantity > $availableStock) {
                    $item->max_stock = $availableStock;
                }
            }
        }

        $canCheckout = $items->isNotEmpty()
            && $items->every(fn ($item) => ! $item->is_unavailable && $item->max_stock === null);

        return view('storefront.cart.index', compact('items', 'subtotal', 'canCheckout'));
    }

    public function update(Request $request, int $item, CartService $service): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $service->updateQuantity($item, $validated['quantity']);

        return redirect()->route('cart.index');
    }

    public function destroy(int $item, CartService $service): RedirectResponse
    {
        $service->remove($item);

        return redirect()->route('cart.index');
    }
}
