@extends('layouts.admin')
@section('title', 'Vouchers')
@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><h1 class="text-2xl font-semibold">Vouchers</h1><p class="mt-1 text-sm text-neutral-500">One voucher per online order. Reserved claims count toward quota until payment or cancellation.</p></div>
        <a href="{{ route('admin.vouchers.create') }}" class="bg-black px-5 py-3 text-xs font-medium uppercase tracking-wider text-white">Create voucher</a>
    </div>
    @include('admin._search', ['id' => 'voucher-search', 'placeholder' => 'Search voucher code'])
    <div class="overflow-x-auto bg-white">
        <table class="min-w-full text-sm">
            <thead><tr class="border-b text-left text-xs uppercase tracking-wider text-neutral-500"><th class="px-5 py-4">Code</th><th class="px-5 py-4">Discount</th><th class="px-5 py-4">Period</th><th class="px-5 py-4">Claims / limit</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Action</th></tr></thead>
            <tbody>
                @forelse($vouchers as $voucher)
                    <tr class="border-b border-neutral-100">
                        <td class="px-5 py-4 font-medium">{{ $voucher->code }}</td>
                        <td class="px-5 py-4">{{ $voucher->type === 'FIXED' ? 'Rp '.number_format($voucher->value, 0, ',', '.') : $voucher->value.'%' }}</td>
                        <td class="px-5 py-4 text-xs text-neutral-600">{{ $voucher->start_date?->format('d M Y H:i') ?? 'Any time' }} – {{ $voucher->end_date?->format('d M Y H:i') ?? 'No end' }}</td>
                        <td class="px-5 py-4">{{ $voucher->claimed_count }} / {{ $voucher->usage_limit ?? '∞' }} <span class="block text-xs text-neutral-500">{{ $voucher->redeemed_count }} paid</span></td>
                        <td class="px-5 py-4">{{ $voucher->is_active ? 'ACTIVE' : 'INACTIVE' }}</td>
                        <td class="px-5 py-4"><a href="{{ route('admin.vouchers.show', $voucher) }}" class="underline">Details</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-neutral-500">No vouchers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $vouchers->links() }}
</div>
@endsection
