<?php

namespace App\Livewire\Cashier;

use App\Models\Product;
use App\Models\ProductSku;
use App\Services\Catalog\PriceResolver;
use App\Services\Pos\PosService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class PosScreen extends Component
{
    use WithPagination;

    public string $search = '';

    public array $selectedVariants = [];

    public array $selectedSkus = [];

    public array $cart = [];

    public string $paymentMethod = 'CASH';

    public string $amountReceived = '';

    public string $discount = '0';

    public string $paymentReference = '';

    public bool $nonCashConfirmed = false;

    public int $subtotal = 0;

    public int $total = 0;

    public function mount(): void
    {
        $this->authorizeCashier();
    }

    public function updatedSearch(): void
    {
        $this->authorizeCashier();
        $this->resetPage();
    }

    public function selectVariant(int $productId, int $variantId): void
    {
        $this->authorizeCashier();
        if (! ProductSku::query()->where('product_id', $productId)->where('product_variant_id', $variantId)
            ->where('is_active', true)->whereHas('product', fn ($query) => $query->where('status', Product::STATUS_ACTIVE))
            ->whereHas('variant', fn ($query) => $query->where('is_active', true))
            ->whereHas('size', fn ($query) => $query->where('is_active', true))->exists()) {
            return;
        }

        $this->selectedVariants[$productId] = $variantId;
        unset($this->selectedSkus[$productId]);
    }

    public function selectSize(int $productId, int $skuId): void
    {
        $this->authorizeCashier();
        $sku = ProductSku::with(['product', 'variant', 'size'])->find($skuId);
        if (! $sku || $sku->product_id !== $productId || ! $sku->is_active
            || $sku->product?->status !== Product::STATUS_ACTIVE || ! $sku->variant?->is_active
            || ! $sku->size?->is_active) {
            return;
        }

        $this->selectedVariants[$productId] = $sku->product_variant_id;
        $this->selectedSkus[$productId] = $skuId;
    }

    public function addProductToCart(int $productId): void
    {
        $this->authorizeCashier();
        $skuId = (int) ($this->selectedSkus[$productId] ?? 0);
        $sku = ProductSku::find($skuId);
        if (! $sku || $sku->product_id !== $productId || $sku->product_variant_id !== (int) ($this->selectedVariants[$productId] ?? 0)) {
            $this->addError('cart', 'Choose a color and size first.');

            return;
        }

        $this->addToCart($skuId);
    }

    public function addToCart(int $skuId): void
    {
        $this->authorizeCashier();
        $sku = ProductSku::with(['product', 'variant', 'size', 'stock'])->find($skuId);
        if (! $sku || ! $sku->is_active || $sku->product?->status !== Product::STATUS_ACTIVE || ! $sku->variant?->is_active || ! $sku->size?->is_active) {
            $this->addError('cart', 'Product or variant is no longer available.');

            return;
        }

        $quantity = ($this->cart[$skuId]['quantity'] ?? 0) + 1;
        if ($quantity > ($sku->stock?->available ?? 0)) {
            $this->addError('cart', 'Quantity exceeds available stock.');

            return;
        }

        $this->cart[$skuId] = [
            'sku_id' => $skuId,
            'name' => $sku->product->name,
            'variant' => $sku->variant->name,
            'size' => $sku->size->name,
            'price' => app(PriceResolver::class)->resolve($sku),
            'quantity' => $quantity,
            'available' => $sku->stock?->available ?? 0,
        ];
        $this->recalculate();
    }

    public function updateQty(int $skuId, int $quantity): void
    {
        $this->authorizeCashier();
        if (! isset($this->cart[$skuId])) {
            return;
        }
        if ($quantity <= 0) {
            $this->removeFromCart($skuId);

            return;
        }
        $sku = ProductSku::with('stock')->find($skuId);
        if (! $sku || $quantity > ($sku->stock?->available ?? 0) || $quantity > 1000) {
            $this->addError('cart', 'Quantity exceeds available stock.');

            return;
        }
        $this->cart[$skuId]['quantity'] = $quantity;
        $this->cart[$skuId]['available'] = $sku->stock?->available ?? 0;
        $this->recalculate();
    }

    public function removeFromCart(int $skuId): void
    {
        $this->authorizeCashier();
        unset($this->cart[$skuId]);
        $this->recalculate();
    }

    public function updatedDiscount(): void
    {
        $this->recalculate();
    }

    private function recalculate(): void
    {
        $this->subtotal = array_sum(array_map(fn ($item) => (int) $item['price'] * (int) $item['quantity'], $this->cart));
        $this->total = max(0, $this->subtotal - max(0, (int) $this->discount));
    }

    public function checkout(PosService $service): void
    {
        $this->authorizeCashier();
        $this->resetErrorBag();
        $this->validate([
            'paymentMethod' => ['required', 'in:CASH,QRIS,TRANSFER'],
            'amountReceived' => ['nullable', 'integer', 'min:0'],
            'discount' => ['integer', 'min:0'],
            'paymentReference' => ['nullable', 'string', 'max:100'],
            'nonCashConfirmed' => ['boolean'],
        ]);

        try {
            $transaction = $service->processCheckout(
                auth()->user(),
                array_values($this->cart),
                $this->paymentMethod,
                $this->amountReceived === '' ? 0 : (int) $this->amountReceived,
                (int) $this->discount,
                $this->paymentReference,
                $this->nonCashConfirmed,
            );
            $this->redirect(route('cashier.receipt.show', $transaction));
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
        } catch (\Exception $exception) {
            report($exception);
            $this->addError('payment', 'Sale could not be completed. Check stock and payment reference, then try again.');
        }
    }

    public function render()
    {
        $this->authorizeCashier();

        $term = trim($this->search);
        $products = Product::query()
            ->where('status', Product::STATUS_ACTIVE)
            ->whereHas('variants', fn ($query) => $query->where('is_active', true)
                ->whereHas('skus', fn ($skuQuery) => $skuQuery->where('is_active', true)
                    ->whereHas('size', fn ($sizeQuery) => $sizeQuery->where('is_active', true))))
            ->when($term !== '', fn ($query) => $query->where(function ($query) use ($term) {
                $query->where('name', 'like', '%'.$term.'%')
                    ->orWhereHas('skus', fn ($skuQuery) => $skuQuery->where('is_active', true)
                        ->where('sku', 'like', '%'.$term.'%')
                        ->whereHas('variant', fn ($variantQuery) => $variantQuery->where('is_active', true)));
            }))
            ->with(['variants' => fn ($query) => $query->where('is_active', true)
                ->whereHas('skus', fn ($skuQuery) => $skuQuery->where('is_active', true)
                    ->whereHas('size', fn ($sizeQuery) => $sizeQuery->where('is_active', true)))
                ->orderBy('display_order')->with(['skus' => fn ($skuQuery) => $skuQuery
                ->where('is_active', true)
                ->whereHas('size', fn ($sizeQuery) => $sizeQuery->where('is_active', true))
                ->with(['size', 'stock'])])])
            ->orderBy('name')
            ->paginate(12);

        $priceResolver = app(PriceResolver::class);
        $productCards = $products->getCollection()->map(function (Product $product) use ($priceResolver): array {
            $selectedVariantId = (int) ($this->selectedVariants[$product->id] ?? $product->variants->first()?->id);
            $variant = $product->variants->firstWhere('id', $selectedVariantId);
            $selectedSku = $variant?->skus->firstWhere('id', (int) ($this->selectedSkus[$product->id] ?? 0));

            return [
                'product' => $product,
                'selectedVariantId' => $selectedVariantId,
                'variant' => $variant,
                'selectedSku' => $selectedSku,
                'available' => $product->variants->sum(fn ($color) => $color->skus->sum(fn ($sku) => $sku->stock?->available ?? 0)),
                'selectedPrice' => $selectedSku ? $priceResolver->resolve($selectedSku) : null,
            ];
        });

        return view('livewire.cashier.pos-screen', compact('products', 'productCards'));
    }

    private function authorizeCashier(): void
    {
        abort_unless(auth()->check() && auth()->user()->isActive() && auth()->user()->hasRole('cashier'), 403);
    }
}
