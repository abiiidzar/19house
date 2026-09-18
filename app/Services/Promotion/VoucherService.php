<?php

namespace App\Services\Promotion;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    /** Preview only. Checkout validates again while holding the voucher row lock. */
    public function validateAndCalculate(string $code, int $subtotal, User $user): array
    {
        $voucher = Voucher::query()->where('code', $this->normalizeCode($code))->first();
        $this->assertEligible($voucher, $subtotal, $user);

        return ['voucher' => $voucher, 'discount' => $this->calculateDiscount($voucher, $subtotal)];
    }

    /** Must be called inside the order-creation transaction. */
    public function reserve(string $code, int $subtotal, Order $order, User $user): VoucherUsage
    {
        if (DB::transactionLevel() === 0) {
            throw new \LogicException('Voucher reservation must run inside an order transaction.');
        }

        $voucher = Voucher::query()->where('code', $this->normalizeCode($code))->lockForUpdate()->first();
        $this->assertEligible($voucher, $subtotal, $user, true);

        return VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'discount_amount' => $this->calculateDiscount($voucher, $subtotal),
            'status' => VoucherUsage::STATUS_RESERVED,
        ]);
    }

    public function redeemForOrder(Order $order): void
    {
        $usage = VoucherUsage::query()->where('order_id', $order->id)->lockForUpdate()->first();
        if ($usage?->status === VoucherUsage::STATUS_RESERVED) {
            $usage->update(['status' => VoucherUsage::STATUS_REDEEMED, 'redeemed_at' => now()]);
        }
    }

    public function releaseForOrder(Order $order): void
    {
        $usage = VoucherUsage::query()->where('order_id', $order->id)->first();
        if (! $usage) {
            return;
        }

        // Serialize quota release with checkout reservations on the same voucher row.
        Voucher::query()->whereKey($usage->voucher_id)->lockForUpdate()->firstOrFail();
        $usage = VoucherUsage::query()->whereKey($usage->id)->lockForUpdate()->firstOrFail();
        if ($usage->status === VoucherUsage::STATUS_RESERVED) {
            $usage->update(['status' => VoucherUsage::STATUS_RELEASED, 'released_at' => now()]);
        }
    }

    private function assertEligible(?Voucher $voucher, int $subtotal, User $user, bool $locked = false): void
    {
        if (! $voucher || ! $voucher->is_active) {
            $this->fail('Voucher is invalid or inactive.');
        }
        if (! $user->isActive() || ! $user->hasRole('customer')) {
            $this->fail('Only active customers can use vouchers.');
        }
        if ($voucher->start_date?->isFuture()) {
            $this->fail('Voucher is not yet valid.');
        }
        if ($voucher->end_date?->isPast()) {
            $this->fail('Voucher has expired.');
        }
        if ($subtotal < $voucher->min_purchase) {
            $this->fail('Minimum purchase of Rp '.number_format($voucher->min_purchase, 0, ',', '.').' not met.');
        }
        if ($voucher->value < 1 || ! in_array($voucher->type, [Voucher::TYPE_FIXED, Voucher::TYPE_PERCENTAGE], true)
            || ($voucher->type === Voucher::TYPE_PERCENTAGE && $voucher->value > 100)) {
            $this->fail('Voucher configuration is invalid.');
        }

        $countedStatuses = [VoucherUsage::STATUS_RESERVED, VoucherUsage::STATUS_REDEEMED];
        $usages = $voucher->usages()->whereIn('status', $countedStatuses);
        // A locking read sees current committed usages even under MySQL REPEATABLE READ.
        // A plain COUNT here could reuse a snapshot taken before the voucher row was locked.
        $count = $locked ? (clone $usages)->lockForUpdate()->get(['id'])->count() : (clone $usages)->count();
        if ($voucher->usage_limit !== null && $count >= $voucher->usage_limit) {
            $this->fail('Voucher usage limit has been reached.');
        }
        $userUsages = (clone $usages)->where('user_id', $user->id);
        $userCount = $locked ? $userUsages->lockForUpdate()->get(['id'])->count() : $userUsages->count();
        if ($voucher->per_user_limit !== null && $userCount >= $voucher->per_user_limit) {
            $this->fail('You have reached the usage limit for this voucher.');
        }
    }

    private function calculateDiscount(Voucher $voucher, int $subtotal): int
    {
        $discount = $voucher->type === Voucher::TYPE_FIXED
            ? $voucher->value
            : intdiv($subtotal * $voucher->value, 100);

        if ($voucher->type === Voucher::TYPE_PERCENTAGE && $voucher->max_discount !== null) {
            $discount = min($discount, $voucher->max_discount);
        }

        return min($discount, $subtotal);
    }

    private function normalizeCode(string $code): string
    {
        return mb_strtoupper(trim($code));
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['voucherCode' => $message]);
    }
}
