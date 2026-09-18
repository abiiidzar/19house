<?php

namespace App\Services\Checkout;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Setting;
use App\Services\Cart\CartService;
use App\Services\Catalog\PriceResolver;
use App\Services\Inventory\StockReservationService;
use App\Services\Payment\PaymentService;
use App\Services\Promotion\VoucherService;
use App\Services\Shipping\ShippingService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    private const PAYMENT_METHODS = ['BANK_TRANSFER', 'COD'];

    public function __construct(
        protected CartService $cartService,
        protected PriceResolver $priceResolver,
        protected ShippingService $shippingService,
        protected StockReservationService $reservationService,
        protected PaymentService $paymentService,
        protected VoucherService $voucherService
    ) {}

    public function processCheckout(int $addressId, string $shippingMethod, string $paymentMethod, ?string $voucherCode = null): Order
    {
        $user = Auth::user();
        $cart = $this->cartService->getOrCreateCart();
        $cartItems = $cart->items()->with('sku.product', 'sku.variant', 'sku.stock')->get();

        // 1. Validasi Awal (Roadmap 727)
        if ($cartItems->isEmpty()) {
            throw new Exception('Cart is empty.');
        }

        $address = CustomerAddress::where('user_id', $user->id)->findOrFail($addressId);
        $shippingMethods = $this->shippingService->getAvailableMethods();
        if (! array_key_exists($shippingMethod, $shippingMethods)) {
            throw new Exception('Invalid shipping method.');
        }

        if (! in_array($paymentMethod, self::PAYMENT_METHODS, true)) {
            throw new Exception('Invalid payment method.');
        }

        $shippingCost = $shippingMethods[$shippingMethod]['cost'];

        // 2. Transaction: Order Creation (Roadmap 728)
        return DB::transaction(function () use ($cartItems, $user, $address, $shippingMethod, $paymentMethod, $shippingCost, $cart, $voucherCode) {
            $subtotal = 0;
            $orderItemsData = [];
            $skusToReserve = [];

            foreach ($cartItems as $item) {
                $sku = $item->sku;

                // Revalidate SKU & Stock
                if (! $sku || ! $sku->is_active || ! $sku->variant?->is_active || $sku->product?->status !== Product::STATUS_ACTIVE) {
                    throw new Exception('A product in your cart is no longer available.');
                }

                if ($item->quantity <= 0) {
                    throw new Exception("Invalid quantity for {$sku->sku}.");
                }

                $availableStock = $sku->stock?->available ?? 0;
                if ($item->quantity > $availableStock) {
                    throw new Exception("Insufficient stock for {$sku->sku}.");
                }

                // Server-side Price Calculation
                $price = $this->priceResolver->resolve($sku);
                $subtotal += ($price * $item->quantity);

                // Snapshot data
                $snapshot = [
                    'product_name' => $sku->product->name,
                    'variant_name' => $sku->variant->name,
                    'size_name' => $sku->size->name,
                ];

                $orderItemsData[] = new OrderItem([
                    'product_sku_id' => $sku->id,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'sku_snapshot' => $snapshot,
                ]);

                $skusToReserve[] = ['sku' => $sku, 'qty' => $item->quantity];
            }

            $total = $subtotal + $shippingCost;

            // Create Order
            $order = Order::create([
                'order_number' => '19H-'.strtoupper(uniqid()),
                'user_id' => $user->id,
                'status' => Order::STATUS_PENDING_PAYMENT,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'shipping_method' => $shippingMethod,
                'payment_method' => $paymentMethod,
                'address_snapshot' => $address->only(['recipient_name', 'phone', 'address_line_1', 'city', 'postal_code']),
            ]);

            if (filled($voucherCode)) {
                // Quota is locked and reserved in this same transaction. The payment is created with the final total.
                $usage = $this->voucherService->reserve($voucherCode, $subtotal, $order, $user);
                $order->update([
                    'discount' => $usage->discount_amount,
                    'total' => max(0, $subtotal + $shippingCost - $usage->discount_amount),
                ]);
            }

            // Save Order Items
            $order->items()->saveMany($orderItemsData);

            // Create Order History
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => Order::STATUS_PENDING_PAYMENT,
                'description' => 'Order created. Awaiting payment.',
            ]);

            // Create Stock Reservations (Roadmap 728)
            foreach ($skusToReserve as $data) {
                $this->reservationService->reserve($data['sku'], $data['qty'], (int) Setting::valueFor('payment_expiry_minutes', 60), $order);
            }

            $this->paymentService->createPaymentAttempt($order);

            // Clear Cart
            $cart->items()->delete();
            $cart->update(['status' => 'CONVERTED']);

            return $order;
        }, 3);
    }
}
