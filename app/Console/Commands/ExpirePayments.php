<?php

namespace App\Console\Commands;

use App\Events\OrderLifecycleEvent;
use App\Models\InventoryReservation;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Services\Inventory\StockReservationService;
use App\Services\Promotion\VoucherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpirePayments extends Command
{
    protected $signature = 'payments:expire';

    protected $description = 'Expire pending payments and release inventory';

    public function handle(StockReservationService $reservationService, VoucherService $voucherService): void
    {
        $payments = Payment::where('status', Payment::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->get();

        foreach ($payments as $payment) {
            DB::transaction(function () use ($payment, $reservationService, $voucherService): void {
                $order = Order::whereKey($payment->order_id)->lockForUpdate()->first();
                $payment = Payment::whereKey($payment->id)->lockForUpdate()->first();

                if (! $order || ! $payment || $order->status !== Order::STATUS_PENDING_PAYMENT || $payment->status !== Payment::STATUS_PENDING || ! $payment->expires_at?->isPast()) {
                    return;
                }

                $payment->update(['status' => Payment::STATUS_EXPIRED]);
                PaymentEvent::create([
                    'payment_id' => $payment->id,
                    'event_type' => 'EXPIRED',
                    'payload' => ['message' => 'Payment expired'],
                ]);

                $order->update(['status' => Order::STATUS_CANCELLED]);
                $voucherService->releaseForOrder($order);
                OrderStatusHistory::create(['order_id' => $order->id, 'status' => Order::STATUS_CANCELLED, 'description' => 'Payment expired; stock reservation released.']);

                $reservations = InventoryReservation::where('order_id', $order->id)
                    ->where('status', 'PENDING')
                    ->get();

                foreach ($reservations as $reservation) {
                    $reservationService->release($reservation);
                }

                event(new OrderLifecycleEvent($order, 'PAYMENT_EXPIRED'));
            });

            $this->info("Expired payment {$payment->payment_reference} and released stock.");
        }
    }
}
