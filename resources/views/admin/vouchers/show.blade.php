@extends('layouts.admin')
@section('title', 'Voucher Details')
@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3"><div><a href="{{ route('admin.vouchers.index') }}" class="text-xs underline">← All vouchers</a><h1 class="mt-2 text-2xl font-semibold">{{ $voucher->code }}</h1></div><a href="{{ route('admin.vouchers.edit', $voucher) }}" class="bg-black px-5 py-3 text-xs font-medium uppercase tracking-wider text-white">Edit voucher</a></div>
    <div class="grid gap-4 bg-white p-6 text-sm sm:grid-cols-3"><p>Type: <strong>{{ $voucher->type }}</strong></p><p>Value: <strong>{{ $voucher->type === 'FIXED' ? 'Rp '.number_format($voucher->value, 0, ',', '.') : $voucher->value.'%' }}</strong></p><p>Status: <strong>{{ $voucher->is_active ? 'ACTIVE' : 'INACTIVE' }}</strong></p><p>Min purchase: Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</p><p>Max discount: {{ $voucher->max_discount === null ? '—' : 'Rp '.number_format($voucher->max_discount, 0, ',', '.') }}</p><p>Per customer: {{ $voucher->per_user_limit }}</p></div>
    <div><h2 class="mb-3 text-lg font-semibold">Usage history</h2><div class="overflow-x-auto bg-white"><table class="min-w-full text-sm"><thead><tr class="border-b text-left text-xs uppercase text-neutral-500"><th class="px-5 py-4">Order</th><th class="px-5 py-4">Customer</th><th class="px-5 py-4">Discount</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Date</th></tr></thead><tbody>
        @forelse($usages as $usage)<tr class="border-b border-neutral-100"><td class="px-5 py-4"><a href="{{ route('admin.orders.show', $usage->order) }}" class="underline">{{ $usage->order->order_number }}</a></td><td class="px-5 py-4">{{ $usage->user->name }}</td><td class="px-5 py-4">Rp {{ number_format($usage->discount_amount, 0, ',', '.') }}</td><td class="px-5 py-4">{{ $usage->status }}</td><td class="px-5 py-4">{{ $usage->created_at->format('d M Y H:i') }}</td></tr>
        @empty<tr><td colspan="5" class="px-5 py-8 text-center text-neutral-500">No usage yet.</td></tr>@endforelse
    </tbody></table></div></div>
    {{ $usages->links() }}
</div>
@endsection
