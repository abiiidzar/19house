@extends('layouts.admin')
@section('title', 'Payments')
@section('content')
<div class="space-y-6"><h1 class="text-2xl font-semibold">Payments</h1>
<form method="GET" class="flex flex-col gap-2 sm:flex-row"><input type="search" name="search" value="{{ request('search') }}" placeholder="Search reference, order, or customer" class="w-full border p-3 sm:max-w-md"><select name="status" class="border p-3"><option value="">All statuses</option>@foreach(['PENDING','PAID','EXPIRED','FAILED','CANCELLED','REFUND_PENDING','REFUNDED'] as $option)<option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>@endforeach</select><button class="bg-neutral-900 px-5 py-3 text-white">Filter</button>@if(request()->hasAny(['search', 'status']))<a href="{{ route('admin.payments.index') }}" class="border p-3 text-center">Reset</a>@endif</form>
<div class="bg-white border overflow-x-auto"><table class="w-full text-sm text-left"><thead><tr class="border-b"><th class="p-4">Reference</th><th class="p-4">Order</th><th class="p-4">Customer</th><th class="p-4">Amount</th><th class="p-4">Status</th><th class="p-4"></th></tr></thead><tbody>
@forelse($payments as $payment)<tr class="border-b"><td class="p-4">{{ $payment->payment_reference }}</td><td class="p-4">{{ $payment->order?->order_number }}</td><td class="p-4">{{ $payment->order?->user?->name }}</td><td class="p-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td><td class="p-4">{{ $payment->status }}</td><td class="p-4"><a class="underline" href="{{ route('admin.payments.show', $payment) }}">Detail</a></td></tr>@empty<tr><td colspan="6" class="p-6 text-center">No payments found.</td></tr>@endforelse
</tbody></table></div>{{ $payments->links() }}</div>
@endsection
