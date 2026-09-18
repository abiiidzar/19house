<?php

namespace App\Services\Orders;

use App\Events\OrderLifecycleEvent;
use App\Models\CancellationRequest;
use App\Models\InventoryReservation;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\User;
use App\Services\Inventory\InventoryService;
use App\Services\Inventory\StockReservationService;
use App\Services\Promotion\VoucherService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderFulfillmentService
{
    private const NEXT_STATUS = [
        Order::STATUS_PAID => Order::STATUS_PROCESSING,
        Order::STATUS_PROCESSING => Order::STATUS_READY_TO_SHIP,
        Order::STATUS_SHIPPED => Order::STATUS_DELIVERED,
        Order::STATUS_DELIVERED => Order::STATUS_COMPLETED,
    ];

    public function __construct(
        private StockReservationService $reservations,
        private InventoryService $inventory,
        private VoucherService $vouchers,
    ) {}

    public function advance(Order $order, string $target, User $actor): Order
    {
        return DB::transaction(function () use ($order, $target, $actor) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ((self::NEXT_STATUS[$order->status] ?? null) !== $target) {
                throw ValidationException::withMessages(['status' => 'Invalid order status transition.']);
            }

            if ($order->cancellationRequests()->where('status', CancellationRequest::STATUS_REQUESTED)->exists()) {
                throw ValidationException::withMessages(['status' => 'Review the cancellation request first.']);
            }

            if ($order->status === Order::STATUS_PAID && $order->payment?->status !== Payment::STATUS_PAID) {
                throw ValidationException::withMessages(['status' => 'Payment has not been confirmed.']);
            }

            $order->update(['status' => $target]);
            $this->history($order, $target, 'Order advanced to '.$target.'.', $actor);

            if (in_array($target, [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED], true)) {
                event(new OrderLifecycleEvent($order, $target));
            }

            if ($target === Order::STATUS_DELIVERED) {
                $order->shipment?->update(['status' => 'DELIVERED', 'delivered_at' => now()]);
            }

            return $order;
        });
    }

    public function ship(Order $order, array $details, User $actor): Order
    {
        return DB::transaction(function () use ($order, $details, $actor) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($order->status !== Order::STATUS_READY_TO_SHIP || $order->shipment()->exists()) {
                throw ValidationException::withMessages(['status' => 'Only a ready-to-ship order without a shipment can be shipped.']);
            }

            $order->shipment()->create([
                'courier' => $details['courier'],
                'service' => $details['service'],
                'tracking_number' => $details['tracking_number'],
                'shipping_cost' => $order->shipping_cost,
                'status' => 'SHIPPED',
                'shipped_at' => now(),
            ]);
            $order->update(['status' => Order::STATUS_SHIPPED]);
            $this->history($order, Order::STATUS_SHIPPED, 'Shipped with '.$details['courier'].' '.$details['service'].'; tracking '.$details['tracking_number'].'.', $actor);
            event(new OrderLifecycleEvent($order, 'SHIPPED', $details['tracking_number']));

            return $order;
        });
    }

    public function cancelUnpaid(Order $order, User $actor, string $reason): Order
    {
        return DB::transaction(function () use ($order, $actor, $reason) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($order->user_id !== $actor->id || $order->status !== Order::STATUS_PENDING_PAYMENT) {
                throw ValidationException::withMessages(['status' => 'Only your unpaid order can be cancelled directly.']);
            }

            $payment = Payment::where('order_id', $order->id)->lockForUpdate()->first();
            if ($payment?->status !== Payment::STATUS_PENDING) {
                throw ValidationException::withMessages(['status' => 'This payment can no longer be cancelled.']);
            }

            $payment->update(['status' => Payment::STATUS_CANCELLED]);
            PaymentEvent::create(['payment_id' => $payment->id, 'event_type' => 'CANCELLED', 'payload' => ['reason' => $reason]]);
            foreach (InventoryReservation::where('order_id', $order->id)->where('status', 'PENDING')->get() as $reservation) {
                $this->reservations->release($reservation);
            }
            $order->update(['status' => Order::STATUS_CANCELLED]);
            $this->vouchers->releaseForOrder($order);
            $this->history($order, Order::STATUS_CANCELLED, 'Customer cancelled unpaid order: '.$reason, $actor);

            return $order;
        });
    }

    public function requestCancellation(Order $order, User $actor, string $reason): CancellationRequest
    {
        return DB::transaction(function () use ($order, $actor, $reason) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($order->user_id !== $actor->id || $order->status !== Order::STATUS_PAID || $order->payment?->status !== Payment::STATUS_PAID) {
                throw ValidationException::withMessages(['status' => 'Only your paid, unprocessed order can request cancellation.']);
            }
            if ($order->cancellationRequests()->where('status', CancellationRequest::STATUS_REQUESTED)->exists()) {
                throw ValidationException::withMessages(['status' => 'A cancellation request is already pending.']);
            }

            $request = $order->cancellationRequests()->create([
                'requested_by' => $actor->id,
                'reason' => $reason,
                'status' => CancellationRequest::STATUS_REQUESTED,
                'requested_at' => now(),
            ]);
            $this->history($order, $order->status, 'Cancellation requested: '.$reason, $actor);
            event(new OrderLifecycleEvent($order, 'CANCELLATION_REQUESTED'));

            return $request;
        });
    }

    public function reviewCancellation(CancellationRequest $request, bool $approve, User $actor, ?string $note): CancellationRequest
    {
        return DB::transaction(function () use ($request, $approve, $actor, $note) {
            $order = Order::whereKey($request->order_id)->lockForUpdate()->firstOrFail();
            $request = CancellationRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
            if ($request->status !== CancellationRequest::STATUS_REQUESTED || $order->status !== Order::STATUS_PAID) {
                throw ValidationException::withMessages(['status' => 'Cancellation request is no longer reviewable.']);
            }

            $request->update([
                'status' => $approve ? CancellationRequest::STATUS_APPROVED : CancellationRequest::STATUS_REJECTED,
                'reviewed_by' => $actor->id,
                'admin_note' => $note,
                'reviewed_at' => now(),
            ]);

            if ($approve) {
                $payment = Payment::where('order_id', $order->id)->lockForUpdate()->first();
                if ($payment?->status !== Payment::STATUS_PAID) {
                    throw ValidationException::withMessages(['status' => 'Paid payment is required for cancellation approval.']);
                }
                // Payment was captured and stock consumed. Restock only because fulfillment has not started.
                foreach (InventoryReservation::where('order_id', $order->id)->where('status', 'CONSUMED')->with('sku')->get() as $reservation) {
                    $this->inventory->addStock($reservation->sku, $reservation->quantity, 'CANCELLED_ORDER_'.$order->id, $actor);
                }
                $payment->update(['status' => Payment::STATUS_REFUND_PENDING]);
                PaymentEvent::create(['payment_id' => $payment->id, 'event_type' => 'REFUND_PENDING', 'payload' => ['note' => $note]]);
                $order->update(['status' => Order::STATUS_CANCELLED]);
                $this->history($order, Order::STATUS_CANCELLED, 'Cancellation approved; manual refund pending. '.$note, $actor);
            } else {
                $this->history($order, $order->status, 'Cancellation rejected. '.$note, $actor);
            }

            event(new OrderLifecycleEvent($order, $approve ? 'CANCELLATION_APPROVED' : 'CANCELLATION_REJECTED'));

            return $request;
        });
    }

    public function confirmManualRefund(Order $order, User $actor, string $reference): Order
    {
        return DB::transaction(function () use ($order, $actor, $reference) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $payment = Payment::where('order_id', $order->id)->lockForUpdate()->first();
            if ($order->status !== Order::STATUS_CANCELLED || $payment?->status !== Payment::STATUS_REFUND_PENDING) {
                throw ValidationException::withMessages(['refund' => 'No manual refund is pending for this order.']);
            }

            $payment->update(['status' => Payment::STATUS_REFUNDED]);
            PaymentEvent::create(['payment_id' => $payment->id, 'event_type' => 'REFUNDED', 'payload' => ['manual_reference' => $reference]]);
            $this->history($order, $order->status, 'Manual refund completed. Reference: '.$reference, $actor);
            event(new OrderLifecycleEvent($order, 'REFUND_COMPLETED'));

            return $order;
        });
    }

    private function history(Order $order, string $status, string $description, ?User $actor): void
    {
        OrderStatusHistory::create(['order_id' => $order->id, 'status' => $status, 'description' => $description, 'actor_id' => $actor?->id]);
    }
}
