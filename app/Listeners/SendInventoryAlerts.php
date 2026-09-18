<?php

namespace App\Listeners;

use App\Events\InventoryAlertEvent;
use App\Models\User;
use App\Notifications\AdminAlert;

class SendInventoryAlerts
{
    public function handle(InventoryAlertEvent $event): void
    {
        $sku = $event->sku->loadMissing(['product', 'variant', 'size']);
        $name = $sku->product?->name ?? $sku->sku;
        $variant = $sku->variant?->name ?? 'Unknown variant';
        $size = $sku->size?->name ?? 'Unknown size';
        $title = $event->level === 'OUT' ? 'Critical inventory: out of stock' : 'Low stock';
        $message = "{$name} ({$variant} / {$size}) has {$event->available} units available.";
        $url = route('admin.inventory.index');

        User::query()->where('status', 'ACTIVE')
            ->whereHas('role', fn ($query) => $query->where('slug', 'admin'))
            ->each(fn (User $admin) => $admin->notify(new AdminAlert($title, $message, $url)));
    }
}
