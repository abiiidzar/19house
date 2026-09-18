<?php

namespace App\Listeners;

use App\Events\OrderLifecycleEvent;
use App\Models\User;
use App\Notifications\AdminAlert;
use App\Notifications\OrderUpdate;

class SendOrderNotifications
{
    public function handle(OrderLifecycleEvent $event): void
    {
        $order = $event->order;
        $number = $order->order_number;

        $customerMessage = match ($event->type) {
            'PAID' => "Payment confirmed for order {$number}.",
            'PROCESSING' => "Order {$number} is being processed.",
            'SHIPPED' => "Order {$number} has shipped. Tracking: {$event->detail}.",
            'COMPLETED' => "Order {$number} is complete.",
            'CANCELLATION_APPROVED' => "Cancellation for order {$number} was approved; refund pending.",
            'CANCELLATION_REJECTED' => "Cancellation for order {$number} was rejected.",
            'PAYMENT_EXPIRED' => "Payment for order {$number} expired. The order was cancelled.",
            'REFUND_COMPLETED' => "Refund for order {$number} has been completed.",
            default => null,
        };

        if ($customerMessage !== null) {
            $order->user?->notify(new OrderUpdate($customerMessage, $order->id));
        }

        if (! in_array($event->type, ['PAID', 'CANCELLATION_REQUESTED'], true)) {
            return;
        }

        $title = $event->type === 'PAID' ? 'New paid order' : 'New cancellation request';
        $message = $event->type === 'PAID'
            ? "Order {$number} has been paid."
            : "Order {$number} requested cancellation.";
        $url = route('admin.orders.show', $order);

        User::query()->where('status', 'ACTIVE')
            ->whereHas('role', fn ($query) => $query->where('slug', 'admin'))
            ->each(fn (User $admin) => $admin->notify(new AdminAlert($title, $message, $url)));
    }
}
