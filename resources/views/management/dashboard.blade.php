@extends('layouts.management')
@section('title', 'Business Overview')
@section('content')
<div class="space-y-7">
    <div><h1 class="text-2xl font-semibold">Business Overview</h1><p class="mt-1 text-sm text-neutral-500">{{ $filters['from'] }} to {{ $filters['to'] }} · {{ $filters['channel'] }} · read-only</p></div>
    @include('management.reports._filters')

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @foreach(['Revenue' => 'Rp '.number_format($sales['revenue'], 0, ',', '.'), 'Online orders' => $sales['orders'], 'Transactions' => $sales['transactions'], 'Units sold' => $sales['units_sold'], 'AOV' => 'Rp '.number_format($sales['aov'], 0, ',', '.')] as $label => $value)
            <div class="border border-neutral-200 bg-white p-5"><p class="text-xs font-medium uppercase tracking-wider text-neutral-500">{{ $label }}</p><p class="mt-3 text-2xl font-semibold">{{ $value }}</p></div>
        @endforeach
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <section class="border border-neutral-200 bg-white p-6 lg:col-span-2"><div class="flex items-center justify-between"><h2 class="font-semibold">Sales trend</h2><a href="{{ route('management.reports.sales', request()->query()) }}" class="text-xs underline">Full sales report</a></div>
            @php $maxTrend = max(array_merge([1], array_column($trend, 'total'))); @endphp
            <div class="mt-5 space-y-3">@forelse($trend as $day)<div class="grid grid-cols-[6rem_1fr_auto] items-center gap-3 text-xs"><span>{{ $day['date'] }}</span><div class="h-3 bg-neutral-100"><div class="h-3 bg-neutral-900" style="width: {{ round($day['total'] / $maxTrend * 100) }}%"></div></div><span>Rp {{ number_format($day['total'], 0, ',', '.') }}</span></div>@empty<p class="text-sm text-neutral-500">No paid sales in this period.</p>@endforelse</div>
        </section>
        <section class="border border-neutral-200 bg-white p-6"><h2 class="font-semibold">Online vs POS</h2><div class="mt-5 space-y-5 text-sm">@foreach(['Online' => $sales['online'], 'POS' => $sales['pos']] as $label => $row)<div><div class="flex justify-between gap-3"><span>{{ $label }}</span><strong>Rp {{ number_format($row['revenue'], 0, ',', '.') }}</strong></div><p class="mt-1 text-xs text-neutral-500">{{ $row['transactions'] }} transactions · {{ $row['units_sold'] }} units</p><div class="mt-2 h-2 bg-neutral-100"><div class="h-2 {{ $label === 'Online' ? 'bg-black' : 'bg-neutral-500' }}" style="width: {{ $sales['revenue'] > 0 ? round($row['revenue'] / $sales['revenue'] * 100) : 0 }}%"></div></div></div>@endforeach</div></section>
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
        <section class="border border-neutral-200 bg-white p-6"><div class="flex justify-between"><h2 class="font-semibold">Best sellers</h2><a href="{{ route('management.reports.products', request()->query()) }}" class="text-xs underline">Product report</a></div><p class="mt-1 text-xs text-neutral-500">Gross item revenue before discounts; shipping excluded.</p><div class="mt-4 space-y-2">@forelse($products['best_sellers'] as $item)<div class="flex justify-between gap-3 border-t pt-2 text-sm"><span>{{ $item['name'] }} <span class="text-neutral-500">({{ $item['units_sold'] }} units)</span></span><span>Rp {{ number_format($item['gross_item_revenue'], 0, ',', '.') }}</span></div>@empty<p class="text-sm text-neutral-500">No products sold.</p>@endforelse</div></section>
        <section class="border border-neutral-200 bg-white p-6"><div class="flex justify-between"><h2 class="font-semibold">Inventory alerts</h2><a href="{{ route('management.reports.inventory') }}" class="text-xs underline">Inventory report</a></div><p class="mt-3 text-sm"><strong class="text-red-700">{{ $inventory['summary']['out_of_stock'] }}</strong> out of stock · <strong class="text-amber-700">{{ $inventory['summary']['low_stock'] }}</strong> low stock</p><div class="mt-4 space-y-2">@foreach(array_slice(array_merge($inventory['alerts']['out_of_stock'], $inventory['alerts']['low_stock']), 0, 5) as $sku)<div class="flex justify-between border-t pt-2 text-sm"><span>{{ $sku->product->name }} · {{ $sku->variant->name }} / {{ $sku->size?->name }}</span><span>{{ $sku->stock?->available ?? 0 }} avail</span></div>@endforeach</div></section>
    </div>

    <section class="grid gap-4 sm:grid-cols-3"><div class="bg-white p-5"><p class="text-xs uppercase text-neutral-500">New customers</p><p class="mt-2 text-2xl font-semibold">{{ $customers['new'] }}</p></div><div class="bg-white p-5"><p class="text-xs uppercase text-neutral-500">Returning customers</p><p class="mt-2 text-2xl font-semibold">{{ $customers['returning'] }}</p></div><div class="bg-white p-5"><p class="text-xs uppercase text-neutral-500">Online customer value</p><p class="mt-2 text-2xl font-semibold">Rp {{ number_format($customers['purchase_value'], 0, ',', '.') }}</p><a href="{{ route('management.reports.customers', ['from' => $filters['from'], 'to' => $filters['to']]) }}" class="mt-2 inline-block text-xs underline">Customer report</a></div></section>
</div>
@endsection
