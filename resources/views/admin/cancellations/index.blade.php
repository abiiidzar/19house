@extends('layouts.admin')
@section('title', 'Cancellations')
@section('content')
<div class="space-y-6"><h1 class="text-2xl font-semibold">Cancellation requests</h1>
@include('admin._search', ['id' => 'cancellation-search', 'placeholder' => 'Search order, customer, email, or reason', 'hidden' => ['status' => $status]])
<div class="flex gap-2 text-sm">@foreach(['' => 'All', 'REQUESTED' => 'Pending review', 'APPROVED' => 'Approved', 'REJECTED' => 'Rejected'] as $value => $label)<a class="border px-3 py-2 {{ $status === $value || ($status === null && $value === '') ? 'bg-neutral-900 text-white' : 'bg-white' }}" href="{{ route('admin.cancellations.index', array_filter(['status' => $value, 'search' => request('search')])) }}">{{ $label }}</a>@endforeach</div>
<div class="overflow-x-auto border bg-white"><table class="w-full text-left text-sm"><thead><tr class="border-b"><th class="p-4">Order</th><th class="p-4">Customer</th><th class="p-4">Status</th><th class="p-4">Reason</th><th class="p-4">Requested</th><th class="p-4"></th></tr></thead><tbody>
@forelse($requests as $item)<tr class="border-b"><td class="p-4">{{ $item->order?->order_number }}</td><td class="p-4">{{ $item->requester?->name }}</td><td class="p-4">{{ $item->status }}</td><td class="p-4">{{ $item->reason }}</td><td class="p-4">{{ $item->requested_at?->format('d M Y') }}</td><td class="p-4">@if($item->order)<a class="underline" href="{{ route('admin.orders.show', $item->order) }}">Review order</a>@endif</td></tr>@empty<tr><td colspan="6" class="p-6 text-center">No cancellation requests.</td></tr>@endforelse
</tbody></table></div>{{ $requests->links() }}</div>
@endsection
