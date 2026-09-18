@extends('layouts.account')
@section('title', 'Notifications - 19HOUSE')

@section('account_content')
<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-neutral-200 pb-4"><h1 class="storefront-section-title">Notifications</h1>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('customer.notifications.read-all') }}">@csrf<button class="storefront-button underline">Mark all as read</button></form>
        @endif
    </div>
    @if(session('success'))<p class="border border-green-200 bg-green-50 p-4 text-sm">{{ session('success') }}</p>@endif
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <article class="border border-neutral-200 p-5 {{ $notification->read_at ? '' : 'border-black' }}">
                <div class="flex flex-wrap items-start justify-between gap-4"><div>
                    <p class="text-sm {{ $notification->read_at ? '' : 'font-medium' }}">{{ $notification->data['message'] ?? 'Order update' }}</p>
                    <p class="mt-2 text-xs text-neutral-500">{{ $notification->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if(!$notification->read_at)
                    <form method="POST" action="{{ route('customer.notifications.read', $notification->id) }}">@csrf<button class="storefront-button underline">Mark read</button></form>
                @endif</div>
                @if(!empty($notification->data['order_id']))<a class="mt-3 inline-block text-sm underline" href="{{ route('customer.orders.show', $notification->data['order_id']) }}">View order</a>@endif
            </article>
        @empty<p class="py-16 text-center text-sm text-neutral-500">No notifications yet.</p>@endforelse
    </div>
    {{ $notifications->links() }}
</div>
@endsection
