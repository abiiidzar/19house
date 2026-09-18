<section class="border border-neutral-200 p-6" aria-labelledby="order-timeline-title">
    <h2 id="order-timeline-title" class="mb-6 text-sm font-medium uppercase tracking-[0.08em]">Order Timeline</h2>
    <ol>
        @forelse($order->histories->sortBy('id') as $history)
            @php
                $label = match ($history->status) {
                    \App\Models\Order::STATUS_PENDING_PAYMENT => 'Order Placed',
                    \App\Models\Order::STATUS_PAID => 'Payment Confirmed',
                    \App\Models\Order::STATUS_PROCESSING => 'Processing',
                    \App\Models\Order::STATUS_READY_TO_SHIP => 'Ready to Ship',
                    \App\Models\Order::STATUS_SHIPPED => 'Shipped',
                    \App\Models\Order::STATUS_DELIVERED => 'Delivered',
                    \App\Models\Order::STATUS_COMPLETED => 'Completed',
                    \App\Models\Order::STATUS_CANCELLED => 'Cancelled',
                    default => str_replace('_', ' ', $history->status),
                };
            @endphp
            <li class="relative border-l border-neutral-300 pb-6 pl-7 last:border-transparent last:pb-0">
                <span class="absolute -left-[5px] top-1 h-[9px] w-[9px] rounded-full bg-black" aria-hidden="true"></span>
                <h3 class="text-sm font-medium">{{ $label }}</h3>
                <p class="mt-1 text-xs text-neutral-500"><time datetime="{{ $history->created_at->toIso8601String() }}">{{ $history->created_at->format('d M Y, H:i') }}</time></p>
                @if($history->description)<p class="mt-1 text-sm text-neutral-600">{{ $history->description }}</p>@endif
            </li>
        @empty
            <li class="text-sm text-neutral-500">No status history is available for this order yet.</li>
        @endforelse
    </ol>
</section>
