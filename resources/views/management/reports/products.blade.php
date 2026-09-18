@extends($layout ?? 'layouts.management')
@section('title', 'Product Report')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-semibold">Product Report</h1><p class="mt-1 text-sm text-neutral-500">{{ $report['product_count'] }} products · {{ $report['variant_count'] }} variants. Item revenue is gross before voucher/POS discounts; shipping excluded.</p></div>
    @include('management.reports._filters')
    <div class="grid gap-5 xl:grid-cols-2">
        <section class="overflow-x-auto border bg-white p-6"><h2 class="mb-4 font-semibold">Best sellers & product performance</h2><table class="min-w-full text-sm"><thead><tr class="border-b text-left text-xs uppercase text-neutral-500"><th class="py-3">Product</th><th class="py-3 text-right">Units</th><th class="py-3 text-right">Gross item revenue</th></tr></thead><tbody>@forelse($report['products'] as $item)<tr class="border-b"><td class="py-3">{{ $item['name'] }}</td><td class="py-3 text-right">{{ $item['units_sold'] }}</td><td class="py-3 text-right">Rp {{ number_format($item['gross_item_revenue'], 0, ',', '.') }}</td></tr>@empty<tr><td colspan="3" class="py-6 text-center text-neutral-500">No products sold in this period.</td></tr>@endforelse</tbody></table></section>
        <section class="overflow-x-auto border bg-white p-6"><h2 class="mb-4 font-semibold">Variant performance</h2><table class="min-w-full text-sm"><thead><tr class="border-b text-left text-xs uppercase text-neutral-500"><th class="py-3">Product / variant</th><th class="py-3 text-right">Units</th><th class="py-3 text-right">Gross item revenue</th></tr></thead><tbody>@forelse($report['variants'] as $item)<tr class="border-b"><td class="py-3">{{ $item['product_name'] }} / {{ $item['name'] }}</td><td class="py-3 text-right">{{ $item['units_sold'] }}</td><td class="py-3 text-right">Rp {{ number_format($item['gross_item_revenue'], 0, ',', '.') }}</td></tr>@empty<tr><td colspan="3" class="py-6 text-center text-neutral-500">No variants sold in this period.</td></tr>@endforelse</tbody></table></section>
    </div>
    @if($report['product_count'] > count($report['products']) || $report['variant_count'] > count($report['variants']))<p class="text-xs text-neutral-500">Showing the top 50 products and variants by units sold.</p>@endif
</div>
@endsection
