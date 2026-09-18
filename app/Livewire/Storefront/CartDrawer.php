<?php

namespace App\Livewire\Storefront;

use App\Services\Cart\CartService;
use Livewire\Component;
use Exception;

class CartDrawer extends Component
{
    public $cartItems;
    public $cartCount = 0;

    protected $listeners = ['cart-updated' => 'loadCart'];

    public function mount(CartService $service): void
    {
        $this->loadCart($service);
    }

    public function loadCart(CartService $service): void
    {
        $cart = $service->getOrCreateCart();
        $this->cartCount = $service->count();

        $this->cartItems = $cart->items()
            ->with('sku.product', 'sku.variant')
            ->get();
    }

    public function updateQty($itemId, $qty, CartService $service): void
    {
        try {
            $service->updateQuantity($itemId, $qty);
            $this->dispatch('cart-updated');
        } catch (Exception $e) {
            $this->dispatch('cart-error', message: $e->getMessage());
        }
    }

    public function removeItem($itemId, CartService $service): void
    {
        $service->remove($itemId);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.storefront.cart-drawer');
    }
}
