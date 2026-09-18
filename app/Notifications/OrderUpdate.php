<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderUpdate extends Notification
{
    use Queueable;

    public function __construct(public string $message, public int $orderId) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return ['message' => $this->message, 'order_id' => $this->orderId];
    }
}
