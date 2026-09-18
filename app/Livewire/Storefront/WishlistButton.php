<?php

namespace App\Livewire\Storefront;

use App\Models\Product;
use App\Models\Wishlist;
use Livewire\Component;

class WishlistButton extends Component
{
    public Product $product;

    public $isWishlisted = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
        if (auth()->user()?->hasRole('customer')) {
            $this->isWishlisted = Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }
    }

    public function toggle(): void
    {
        if (! auth()->check()) {
            $this->dispatch('auth-required', action: 'wishlist');

            return;
        }

        if (! auth()->user()->hasRole('customer')) {
            $this->dispatch('storefront-notice', message: 'Wishlist hanya tersedia untuk akun customer.');

            return;
        }

        if ($this->product->status !== Product::STATUS_ACTIVE) {
            $this->dispatch('storefront-notice', message: 'Produk ini sudah tidak tersedia.');

            return;
        }

        if ($this->isWishlisted) {
            Wishlist::where('user_id', auth()->id())->where('product_id', $this->product->id)->delete();
            $this->isWishlisted = false;
        } else {
            Wishlist::create(['user_id' => auth()->id(), 'product_id' => $this->product->id]);
            $this->isWishlisted = true;
        }
    }

    public function render()
    {
        return view('livewire.storefront.wishlist-button');
    }
}
