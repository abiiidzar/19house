<?php

namespace App\Services\Pos;

use App\Models\PosTransaction;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\User;
use App\Services\Catalog\PriceResolver;
use App\Services\Inventory\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PosService
{
    public const PAYMENT_METHODS = ['CASH', 'QRIS', 'TRANSFER'];

    public function __construct(private PriceResolver $prices, private InventoryService $inventory) {}

    public function processCheckout(
        User $cashier,
        array $cartItems,
        string $paymentMethod,
        int $amountReceived = 0,
        int $discount = 0,
        ?string $paymentReference = null,
        bool $nonCashConfirmed = false,
    ): PosTransaction {
        if (! $cashier->isActive() || ! $cashier->hasRole('cashier')) {
            throw ValidationException::withMessages(['cashier' => 'Only an active cashier can complete a POS sale.']);
        }
        if (! in_array($paymentMethod, self::PAYMENT_METHODS, true)) {
            throw ValidationException::withMessages(['paymentMethod' => 'Invalid payment method.']);
        }
        if ($discount < 0) {
            throw ValidationException::withMessages(['discount' => 'Discount cannot be negative.']);
        }

        $quantities = [];
        foreach ($cartItems as $item) {
            $skuId = filter_var($item['sku_id'] ?? null, FILTER_VALIDATE_INT);
            $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);
            if ($skuId === false || $skuId < 1 || $quantity === false || $quantity < 1 || $quantity > 1000) {
                throw ValidationException::withMessages(['cart' => 'Cart contains an invalid SKU or quantity.']);
            }
            $quantities[$skuId] = ($quantities[$skuId] ?? 0) + $quantity;
            if ($quantities[$skuId] > 1000) {
                throw ValidationException::withMessages(['cart' => 'Quantity exceeds the per-SKU limit.']);
            }
        }
        if ($quantities === []) {
            throw ValidationException::withMessages(['cart' => 'Cart is empty.']);
        }
        ksort($quantities, SORT_NUMERIC);

        return DB::transaction(function () use ($cashier, $quantities, $paymentMethod, $amountReceived, $discount, $paymentReference, $nonCashConfirmed) {
            $subtotal = 0;
            $lines = [];
            foreach ($quantities as $skuId => $quantity) {
                $sku = ProductSku::with(['product', 'variant', 'size'])->whereKey($skuId)->lockForUpdate()->first();
                if (! $sku || ! $sku->is_active || $sku->product?->status !== Product::STATUS_ACTIVE || ! $sku->variant?->is_active || ! $sku->size) {
                    throw ValidationException::withMessages(['cart' => 'A selected SKU is no longer available.']);
                }

                $price = $this->prices->resolve($sku);
                if ($price < 0) {
                    throw ValidationException::withMessages(['cart' => 'Invalid product price.']);
                }
                $subtotal += $price * $quantity;
                $lines[] = [
                    'sku' => $sku,
                    'quantity' => $quantity,
                    'price' => $price,
                    'snapshot' => [
                        'product_name' => $sku->product->name,
                        'variant_name' => $sku->variant->name,
                        'size_name' => $sku->size->name,
                        'sku' => $sku->sku,
                    ],
                ];
            }

            $discountCap = min((int) floor($subtotal * 0.10), 50000);
            if ($discount > 0 && (! $cashier->hasPermission('pos.discount.apply') || $discount > $discountCap)) {
                throw ValidationException::withMessages(['discount' => 'Discount is not authorized or exceeds the limit (10%, maximum Rp 50.000).']);
            }
            $total = $subtotal - $discount;
            if ($paymentMethod === 'CASH') {
                if ($amountReceived < $total) {
                    throw ValidationException::withMessages(['amountReceived' => 'Amount received is less than total.']);
                }
                $change = $amountReceived - $total;
                $paymentReference = null;
            } else {
                $paymentReference = trim($paymentReference ?? '');
                if (! $nonCashConfirmed || $paymentReference === '' || mb_strlen($paymentReference) > 100) {
                    throw ValidationException::withMessages(['paymentReference' => 'Verify the non-cash payment and enter a valid reference before completing the sale.']);
                }
                $amountReceived = 0;
                $change = null;
            }

            $transaction = PosTransaction::create([
                'receipt_number' => 'POS-'.(string) Str::ulid(),
                'cashier_id' => $cashier->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => PosTransaction::PAYMENT_CONFIRMED,
                'payment_reference' => $paymentReference,
                'payment_confirmed_at' => now(),
                'amount_received' => $paymentMethod === 'CASH' ? $amountReceived : null,
                'change' => $change,
                'status' => PosTransaction::STATUS_COMPLETED,
            ]);

            foreach ($lines as $line) {
                $transaction->items()->create([
                    'product_sku_id' => $line['sku']->id,
                    'price' => $line['price'],
                    'quantity' => $line['quantity'],
                    'sku_snapshot' => $line['snapshot'],
                ]);
                $this->inventory->deductStockForPos($line['sku'], $line['quantity'], $transaction->receipt_number, $cashier);
            }

            return $transaction;
        });
    }
}
