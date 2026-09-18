@extends('layouts.admin')
@section('title', 'Customer Detail')
@section('content')
<div class="max-w-5xl space-y-6"><a class="text-sm underline" href="{{ route('admin.customers.index') }}">← Customers</a><h1 class="text-2xl font-semibold">{{ $customer->name }}</h1>
<section class="border bg-white p-6 text-sm"><p>{{ $customer->email }} · {{ $customer->phone ?? 'No phone' }}</p><p class="mt-2">{{ $paidCount }} paid orders · Joined {{ $customer->created_at?->format('d M Y') }}</p></section>
<section class="border bg-white p-6"><h2 class="font-semibold">Addresses</h2>@forelse($addresses as $address)<p class="border-b py-3 text-sm">{{ $address->recipient_name }} · {{ $address->address_line_1 }}, {{ $address->city }} {{ $address->postal_code }}</p>@empty<p class="mt-3 text-sm text-neutral-500">No addresses.</p>@endforelse</section>
<section class="border bg-white p-6"><h2 class="font-semibold">Orders</h2>@forelse($orders as $order)<div class="flex justify-between border-b py-3 text-sm"><a class="underline" href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a><span>{{ $order->status }} · Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>@empty<p class="mt-3 text-sm text-neutral-500">No orders.</p>@endforelse<div class="mt-4">{{ $orders->links() }}</div></section>
</div>
@endsection
