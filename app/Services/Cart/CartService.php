<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\User;
use App\Services\Catalog\PriceResolver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;

class CartService
{
    public function __construct(protected PriceResolver $priceResolver) {}

    public function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'ACTIVE'],
                ['session_id' => null]
            );
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(
            ['session_id' => $sessionId, 'status' => 'ACTIVE'],
            ['user_id' => null]
        );
    }

    public function add(int $skuId, int $quantity): void
    {
        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than 0.');
        }

        $sku = ProductSku::with('product', 'variant', 'stock')->find($skuId);

        if (!$sku || !$sku->is_active || !$sku->variant?->is_active || $sku->product?->status !== Product::STATUS_ACTIVE) {
            throw new Exception('Product or Variant is not available.');
        }

        $availableStock = $sku->stock?->available ?? 0;

        $cart = $this->getOrCreateCart();
        $item = $cart->items()->where('product_sku_id', $skuId)->first();

        $currentQty = $item?->quantity ?? 0;
        if (($currentQty + $quantity) > $availableStock) {
            throw new Exception('Total quantity exceeds available stock.');
        }

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_sku_id' => $skuId,
                'quantity' => $quantity,
            ]);
        }
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        if ($quantity < 0) {
            throw new Exception('Quantity cannot be negative.');
        }

        $cart = $this->getOrCreateCart();
        $item = $cart->items()->with('sku.product', 'sku.variant', 'sku.stock')->findOrFail($itemId);

        $sku = $item->sku;

        // Revalidate status
        if (!$sku || !$sku->is_active || !$sku->variant?->is_active || $sku->product?->status !== Product::STATUS_ACTIVE) {
            throw new Exception('This item is no longer available.');
        }

        $availableStock = $sku->stock?->available ?? 0;

        if ($quantity > $availableStock) {
            throw new Exception('Quantity exceeds available stock.');
        }

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $quantity]);
        }
    }

    public function remove(int $itemId): void
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->where('id', $itemId)->delete();
    }

    public function count(): int
    {
        $cart = $this->getOrCreateCart();
        return $cart->items()->sum('quantity');
    }

    public function getCartItems()
    {
        return $this->getOrCreateCart()->items()
            ->with('sku.product', 'sku.variant', 'sku.size', 'sku.stock')
            ->get();
    }

    // Merge Cart dengan transaksi dan validasi ketat
    public function mergeCart(User $user, string $oldSessionId): void
    {
        DB::transaction(function () use ($user, $oldSessionId) {
            $guestCart = Cart::where('session_id', $oldSessionId)->where('status', 'ACTIVE')->first();

            if (!$guestCart) {
                return;
            }

            $userCart = Cart::firstOrCreate(
                ['user_id' => $user->id, 'status' => 'ACTIVE'],
                ['session_id' => null]
            );

            foreach ($guestCart->items as $item) {
                $sku = ProductSku::with('product', 'variant', 'stock')->find($item->product_sku_id);

                // Revalidate SKU status
                if (!$sku || !$sku->is_active || !$sku->variant?->is_active || $sku->product?->status !== Product::STATUS_ACTIVE) {
                    $item->delete(); // Hapus item yang tidak valid
                    continue;
                }

                $availableStock = $sku->stock?->available ?? 0;
                $existingItem = $userCart->items()->where('product_sku_id', $item->product_sku_id)->first();

                if ($existingItem) {
                    $newQty = min($existingItem->quantity + $item->quantity, $availableStock);
                    if ($newQty > 0) {
                        $existingItem->update(['quantity' => $newQty]);
                    } else {
                        $existingItem->delete();
                    }
                    $item->delete();
                } else {
                    if ($item->quantity > $availableStock) {
                        $item->update(['quantity' => $availableStock]); // Clamp ke stok tersedia
                    }
                    if ($item->quantity > 0) {
                        $item->update(['cart_id' => $userCart->id]);
                    } else {
                        $item->delete();
                    }
                }
            }

            $guestCart->update(['status' => 'CONVERTED', 'session_id' => null]);
        });
    }
}
