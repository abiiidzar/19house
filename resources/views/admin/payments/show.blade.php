@extends('layouts.admin')
@section('title', 'Payment Detail')
@section('content')
<div class="max-w-4xl space-y-6"><div><a class="text-sm underline" href="{{ route('admin.payments.index') }}">← Payments</a><h1 class="mt-3 text-2xl font-semibold">Payment {{ $payment->payment_reference }}</h1></div>
<dl class="grid gap-5 border bg-white p-6 text-sm sm:grid-cols-2">
    @foreach(['Status' => $payment->status, 'Amount' => 'Rp '.number_format($payment->amount, 0, ',', '.'), 'Customer' => $payment->order?->user?->name ?? '—', 'Payment method' => $payment->order?->payment_method ?? '—', 'Provider' => $payment->provider, 'Provider reference' => $payment->provider_payment_id ?? '—', 'Created at' => $payment->created_at?->format('d M Y H:i') ?? '—', 'Paid at' => $paidAt?->format('d M Y H:i') ?? '—', 'Expires at' => $payment->expires_at?->format('d M Y H:i') ?? '—'] as $label => $value)
        <div><dt class="text-xs uppercase tracking-wider text-neutral-500">{{ $label }}</dt><dd class="mt-1">{{ $value }}</dd></div>
    @endforeach
    <div><dt class="text-xs uppercase tracking-wider text-neutral-500">Order</dt><dd class="mt-1">@if($payment->order)<a class="underline" href="{{ route('admin.orders.show', $payment->order) }}">{{ $payment->order->order_number }}</a>@else — @endif</dd></div>
</dl>
<section class="border bg-white p-6"><h2 class="font-semibold">Callback / payment history</h2><p class="mt-1 text-xs text-neutral-500">Sensitive raw callback payloads are not displayed.</p>
    @forelse($payment->events->sortByDesc('created_at') as $event)<div class="border-b py-3 text-sm"><span class="font-medium">{{ $event->event_type }}</span><span class="text-neutral-500"> · {{ $event->created_at?->format('d M Y H:i') }}</span>
    @if($event->payload && ($summary = collect($event->payload)->only(['message', 'manual_reference', 'reason', 'note'])->filter(fn ($value) => is_scalar($value) && $value !== '')->all()))<p class="mt-1 text-neutral-600">{{ implode(' · ', array_map(fn ($key, $value) => $key.': '.$value, array_keys($summary), $summary)) }}</p>@endif
    </div>@empty<p class="mt-4 text-sm text-neutral-500">No payment events.</p>@endforelse
</section></div>
@endsection
