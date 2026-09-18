@extends('layouts.cashier')
@section('title', 'Transactions')
@section('content')
<div class="mx-auto max-w-7xl space-y-7">
    <header class="flex flex-col justify-between gap-4 border-b border-neutral-300 pb-7 sm:flex-row sm:items-end"><div><p class="page-kicker">Register / Records</p><h1 class="mt-3">Transactions</h1><p class="mt-2 text-sm text-neutral-500">Completed in-store sales and printable receipts.</p></div><a href="{{ route('cashier.pos.index') }}" class="storefront-button bg-black text-white hover:bg-neutral-800">New Sale <span class="ml-3 text-base">→</span></a></header>
    <div class="flex items-center justify-between border border-neutral-200 bg-white px-5 py-4"><p class="text-xs uppercase tracking-[0.12em] text-neutral-500">Transaction ledger</p><p class="text-sm font-medium">{{ $transactions->total() }} records</p></div>
    <div class="overflow-x-auto border border-neutral-200 bg-white"><table class="w-full text-left text-sm"><thead class="border-b text-xs uppercase tracking-wider text-neutral-500"><tr><th class="px-5 py-4">Receipt</th><th class="px-5 py-4">Date</th><th class="px-5 py-4">Cashier</th><th class="px-5 py-4">Method</th><th class="px-5 py-4 text-right">Total</th><th class="px-5 py-4 text-right">Action</th></tr></thead><tbody>
        @forelse($transactions as $transaction)
            <tr class="border-b"><td class="px-5 py-4 font-medium">{{ $transaction->receipt_number }}</td><td class="px-5 py-4"><span class="block">{{ $transaction->created_at->format('d M Y') }}</span><span class="mt-1 block text-xs text-neutral-400">{{ $transaction->created_at->format('H:i') }}</span></td><td class="px-5 py-4">{{ $transaction->cashier?->name ?? 'Former cashier' }}</td><td class="px-5 py-4"><span class="status-badge">{{ $transaction->payment_method }}</span></td><td class="px-5 py-4 text-right font-medium tabular-nums">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td><td class="px-5 py-4 text-right"><a href="{{ route('cashier.receipt.show', $transaction) }}" class="text-xs font-medium uppercase tracking-[0.08em] underline underline-offset-4">View receipt</a></td></tr>
        @empty<tr><td colspan="6" class="px-5 py-10 text-center text-neutral-500">No completed POS transactions yet.</td></tr>@endforelse
    </tbody></table></div>
    {{ $transactions->links() }}
</div>
@endsection
