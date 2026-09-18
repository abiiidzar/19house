<?php

namespace App\Services\Payment;

use App\Events\OrderLifecycleEvent;
use App\Models\InventoryReservation;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Services\Inventory\StockReservationService;
use App\Services\Promotion\VoucherService;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentWebhookService
{
    public function __construct(
        protected StockReservationService $reservationService,
        protected VoucherService $voucherService,
    ) {}

    public function handlePaidWebhook(string $paymentReference): void
    {
        DB::transaction(function () use ($paymentReference) {
            $paymentRecord = Payment::where('payment_reference', $paymentReference)->first();

            if (! $paymentRecord) {
                throw new Exception('Payment not found');
            }

            $order = Order::whereKey($paymentRecord->order_id)->lockForUpdate()->firstOrFail();
            $payment = Payment::whereKey($paymentRecord->id)->lockForUpdate()->firstOrFail();

            // 1. Idempotency Check (Roadmap 737)
            if ($payment->status === Payment::STATUS_PAID) {
                return; // Sudah dibayar, abaikan webhook duplikat
            }

            if ($payment->status !== Payment::STATUS_PENDING || $payment->expires_at?->isPast()) {
                throw new Exception('Payment is no longer payable.');
            }

            if ($order->status !== Order::STATUS_PENDING_PAYMENT) {
                throw new Exception('Order is no longer awaiting payment.');
            }

            // 2. Update Payment
            $payment->update(['status' => Payment::STATUS_PAID]);
            PaymentEvent::create([
                'payment_id' => $payment->id,
                'event_type' => 'PAID',
                'payload' => request()->all(),
            ]);

            // 3. Update Order
            $order->update(['status' => Order::STATUS_PAID]);
            OrderStatusHistory::create(['order_id' => $order->id, 'status' => Order::STATUS_PAID, 'description' => 'Payment confirmed by webhook.']);
            $this->voucherService->redeemForOrder($order);

            // 4. Inventory Update (Consume Reservation -> Sale)
            $reservations = InventoryReservation::where('order_id', $order->id)
                ->where('status', 'PENDING')
                ->get();

            foreach ($reservations as $reservation) {
                $this->reservationService->consume($reservation);
            }

            event(new OrderLifecycleEvent($order, 'PAID'));
        });
    }
}
