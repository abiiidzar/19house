<?php

namespace App\Livewire\Checkout;

use App\Models\CustomerAddress;
use App\Services\Cart\CartService;
use App\Services\Catalog\PriceResolver;
use App\Services\Checkout\CheckoutService;
use App\Services\Promotion\VoucherService;
use App\Services\Shipping\ShippingService;
use Exception;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CheckoutForm extends Component
{
    public $addresses;

    public $selectedAddressId;

    public $shippingMethods;

    public $selectedShipping = 'REGULAR';

    public $paymentMethod = 'BANK_TRANSFER';

    public $cartItems;

    public $subtotal = 0;

    public $shippingCost = 0;

    public $total = 0;

    public string $voucherCode = '';

    public int $discount = 0;

    public ?string $appliedVoucherCode = null;

    public function mount(CartService $cartService, ShippingService $shippingService): void
    {
        $this->addresses = CustomerAddress::where('user_id', auth()->id())->get();

        if ($this->addresses->isNotEmpty()) {
            $this->selectedAddressId = $this->addresses->firstWhere('is_default', true)?->id ?? $this->addresses->first()->id;
        }

        $this->shippingMethods = $shippingService->getAvailableMethods();
        $this->loadCart($cartService, $shippingService);
    }

    public function updatedSelectedShipping(CartService $cartService, ShippingService $shippingService): void
    {
        $this->loadCart($cartService, $shippingService);
    }

    public function updatedVoucherCode(): void
    {
        if (mb_strtoupper(trim($this->voucherCode)) !== $this->appliedVoucherCode) {
            $this->appliedVoucherCode = null;
            $this->discount = 0;
            $this->total = $this->subtotal + $this->shippingCost;
        }
    }

    public function applyVoucher(CartService $cartService, ShippingService $shippingService, VoucherService $voucherService): void
    {
        $this->resetErrorBag('voucherCode');
        $this->validate(['voucherCode' => ['required', 'string', 'max:50']]);
        $this->loadCart($cartService, $shippingService);

        try {
            $result = $voucherService->validateAndCalculate($this->voucherCode, $this->subtotal, auth()->user());
            $this->appliedVoucherCode = $result['voucher']->code;
            $this->voucherCode = $result['voucher']->code;
            $this->discount = $result['discount'];
            $this->total = max(0, $this->subtotal + $this->shippingCost - $this->discount);
        } catch (ValidationException $exception) {
            $this->appliedVoucherCode = null;
            $this->discount = 0;
            $this->total = $this->subtotal + $this->shippingCost;
            $this->addError('voucherCode', $exception->errors()['voucherCode'][0]);
        }
    }

    public function removeVoucher(): void
    {
        $this->voucherCode = '';
        $this->appliedVoucherCode = null;
        $this->discount = 0;
        $this->total = $this->subtotal + $this->shippingCost;
        $this->resetErrorBag('voucherCode');
    }

    protected function loadCart(CartService $cartService, ShippingService $shippingService): void
    {
        $this->cartItems = $cartService->getCartItems();
        $this->subtotal = 0;

        foreach ($this->cartItems as $item) {
            $price = app(PriceResolver::class)->resolve($item->sku);
            $this->subtotal += $price * $item->quantity;
        }

        $this->shippingCost = $shippingService->getCost($this->selectedShipping);
        $this->total = max(0, $this->subtotal + $this->shippingCost - $this->discount);
    }

    public function placeOrder(CheckoutService $service): void
    {
        $this->validate([
            'selectedAddressId' => ['required', 'integer'],
            'selectedShipping' => ['required', 'string'],
            'paymentMethod' => ['required', 'in:BANK_TRANSFER,COD'],
            'voucherCode' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $order = $service->processCheckout($this->selectedAddressId, $this->selectedShipping, $this->paymentMethod, trim($this->voucherCode) ?: null);

            // Redirect ke halaman pembayaran (atau sukses untuk MVP sementara)
            $this->redirect(route('checkout.success', $order));
        } catch (Exception $e) {
            $this->dispatch('checkout-error', message: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout.checkout-form');
    }
}
