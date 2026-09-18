@extends('layouts.cashier')
@section('title', 'Dashboard')
@section('content')
<div class="mx-auto max-w-6xl space-y-8">
    <header class="flex flex-col justify-between gap-5 border-b border-neutral-300 pb-8 sm:flex-row sm:items-end">
        <div>
            <p class="page-kicker">19HOUSE / Point of Sale</p>
            <h1 class="mt-3">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ str(auth()->user()->name)->before(' ') }}.</h1>
            <p class="mt-3 max-w-xl text-sm leading-6 text-neutral-600">Your register is ready. Start a new order or review transactions from this workspace.</p>
        </div>
        <div class="text-left sm:text-right"><p class="text-sm font-medium">{{ now()->format('l') }}</p><p class="mt-1 text-xs uppercase tracking-[0.12em] text-neutral-500">{{ now()->format('d F Y') }}</p></div>
    </header>

    <section class="grid gap-px overflow-hidden border border-neutral-200 bg-neutral-200 md:grid-cols-2">
        <a href="{{ route('cashier.pos.index') }}" class="group flex min-h-64 flex-col justify-between bg-[#111111] p-7 text-white transition-colors hover:bg-black sm:p-9">
            <div class="flex items-center justify-between"><span class="text-[10px] uppercase tracking-[0.16em] text-neutral-500">01 / Primary action</span><span class="text-2xl transition-transform group-hover:translate-x-1">→</span></div>
            <div><h2 class="text-3xl font-medium">New Sale</h2><p class="mt-3 max-w-sm text-sm leading-6 text-neutral-400">Find an item, select its variant, and complete an in-store payment.</p></div>
        </a>
        <a href="{{ route('cashier.transactions.index') }}" class="group flex min-h-64 flex-col justify-between bg-white p-7 transition-colors hover:bg-neutral-50 sm:p-9">
            <div class="flex items-center justify-between"><span class="text-[10px] uppercase tracking-[0.16em] text-neutral-400">02 / Records</span><span class="text-2xl transition-transform group-hover:translate-x-1">→</span></div>
            <div><h2 class="text-3xl font-medium">Transactions</h2><p class="mt-3 max-w-sm text-sm leading-6 text-neutral-500">Review completed sales, payment details, and print customer receipts.</p></div>
        </a>
    </section>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="border border-neutral-200 bg-white p-5"><p class="page-kicker">Register</p><p class="mt-3 text-sm font-medium">Active and ready</p></div>
        <div class="border border-neutral-200 bg-white p-5"><p class="page-kicker">Cashier</p><p class="mt-3 truncate text-sm font-medium">{{ auth()->user()->name }}</p></div>
        <div class="border border-neutral-200 bg-white p-5"><p class="page-kicker">Local time</p><p class="mt-3 text-sm font-medium tabular-nums">{{ now()->format('H:i') }} WIB</p></div>
    </div>
</div>
@endsection
