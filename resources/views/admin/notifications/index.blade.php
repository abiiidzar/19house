@extends('layouts.admin')
@section('title', 'Notifications')
@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3"><div><h1 class="text-2xl font-semibold">Notifications</h1><p class="mt-1 text-sm text-neutral-500">{{ $unreadCount }} unread</p></div>@if($unreadCount > 0)<form method="POST" action="{{ route('admin.notifications.read-all') }}">@csrf<button class="text-sm underline">Mark all as read</button></form>@endif</div>
    @include('admin._search', ['id' => 'notification-search', 'placeholder' => 'Search notification title or message'])
    <div class="divide-y border border-neutral-200 bg-white">@forelse($notifications as $notification)<article class="flex flex-wrap items-start justify-between gap-4 p-5 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/50' }}"><div><p class="text-sm font-medium">{{ $notification->data['title'] ?? 'Admin alert' }}</p><p class="mt-1 text-sm text-neutral-600">{{ $notification->data['message'] ?? '' }}</p><p class="mt-2 text-xs text-neutral-500">{{ $notification->created_at->format('d M Y H:i') }}</p></div><div class="flex gap-4 text-xs">@if(!$notification->read_at)<form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">@csrf<button class="underline">Mark read</button></form>@endif @if(!empty($notification->data['url']))<a href="{{ $notification->data['url'] }}" class="underline">View</a>@endif</div></article>@empty<div class="p-8 text-center text-sm text-neutral-500">No notifications.</div>@endforelse</div>
    {{ $notifications->links() }}
</div>
@endsection
