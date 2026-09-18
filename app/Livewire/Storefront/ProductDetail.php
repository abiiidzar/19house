<?php

namespace App\Livewire\Storefront;

use App\Models\Product;
use App\Services\Cart\CartService;
use App\Services\Catalog\PriceResolver;
use Exception;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;

    public ?int $selectedVariantId = null;

    public ?int $selectedSizeId = null;

    public ?int $selectedSkuId = null;

    public $price = 0;

    public $stockAvailable = 0;

    public function mount(Product $product): void
    {
        $this->product = $product;

        $firstVariant = $product->variants->firstWhere('is_active', true);

        if ($firstVariant) {
            $this->selectedVariantId = $firstVariant->id;
            $this->updateVariantState();
        }
    }

    // Method sekarang menerima parameter $variantId
    public function selectVariant(int $variantId): void
    {
        abort_unless($this->product->variants()->where('is_active', true)->whereKey($variantId)->exists(), 404);
        $this->selectedVariantId = $variantId;
        $this->selectedSizeId = null;
        $this->selectedSkuId = null;
        $this->updateVariantState();
    }

    // Method sekarang menerima parameter $sizeId
    public function selectSize(int $sizeId): void
    {
        $this->selectedSizeId = $sizeId;
        $this->updateSkuState();
    }

    protected function updateVariantState(): void
    {
        $variant = $this->product->variants->find($this->selectedVariantId);
        if ($variant && $variant->skus->isNotEmpty()) {
            $firstSku = $variant->skus->first();
            $this->price = app(PriceResolver::class)->resolve($firstSku);
            $this->stockAvailable = 0;
        } else {
            $this->price = $this->product->base_price;
            $this->stockAvailable = 0;
        }
    }

    protected function updateSkuState(): void
    {
        $variant = $this->product->variants->find($this->selectedVariantId);
        $sku = $variant?->skus->where('size_id', $this->selectedSizeId)->first();

        if ($sku) {
            $this->selectedSkuId = $sku->id;
            $this->calculatePriceAndStock($sku);
        } else {
            $this->selectedSkuId = null;
            $this->stockAvailable = 0;
        }
    }

    protected function calculatePriceAndStock($sku): void
    {
        $resolver = app(PriceResolver::class);
        $this->price = $resolver->resolve($sku);
        $this->stockAvailable = $sku->stock?->available ?? 0;
    }

    public function render()
    {
        return view('livewire.storefront.product-detail');
    }

    public function addToCart(CartService $service): void
    {
        if (! auth()->check()) {
            $this->dispatch('auth-required', action: 'cart');

            return;
        }

        if (! auth()->user()->hasRole('customer')) {
            $this->dispatch('storefront-notice', message: 'Keranjang hanya tersedia untuk akun customer.');

            return;
        }

        if (! $this->selectedSkuId) {
            $this->dispatch('storefront-notice', message: 'Pilih ukuran terlebih dahulu.');

            return;
        }

        try {
            $service->add($this->selectedSkuId, 1);
            $this->dispatch('cart-updated');
        } catch (Exception $e) {
            $this->dispatch('storefront-notice', message: $e->getMessage());
        }
    }
}
