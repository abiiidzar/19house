@extends($layout ?? 'layouts.management')
@section('title', 'Customer Report')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-semibold">Customer Report</h1><p class="mt-1 text-sm text-neutral-500">Customer purchase value counts paid online orders only. Returning means a buyer in this period has at least two paid orders lifetime.</p></div>
    @include('management.reports._filters', ['showChannel' => false])
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">@foreach(['New customers' => $report['new'], 'Returning customers' => $report['returning'], 'Purchasing customers' => $report['purchasing_customers'], 'Online orders' => $report['online_orders'], 'Purchase value' => 'Rp '.number_format($report['purchase_value'], 0, ',', '.')] as $label => $value)<div class="border bg-white p-5"><p class="text-xs uppercase tracking-wider text-neutral-500">{{ $label }}</p><p class="mt-2 text-2xl font-semibold">{{ $value }}</p></div>@endforeach</div>
    <section class="overflow-x-auto border bg-white p-6"><h2 class="mb-4 font-semibold">Top purchasing customers</h2><table class="min-w-full text-sm"><thead><tr class="border-b text-left text-xs uppercase text-neutral-500"><th class="py-3">Customer</th><th class="py-3 text-right">Orders</th><th class="py-3 text-right">Purchase value</th></tr></thead><tbody>@forelse($report['rows'] as $row)<tr class="border-b"><td class="py-3">{{ $row['name'] }} <span class="block text-xs text-neutral-500">{{ $row['email'] }}</span></td><td class="py-3 text-right">{{ $row['order_count'] }}</td><td class="py-3 text-right">Rp {{ number_format($row['purchase_value'], 0, ',', '.') }}</td></tr>@empty<tr><td colspan="3" class="py-6 text-center text-neutral-500">No paying customers in this period.</td></tr>@endforelse</tbody></table></section>
</div>
@endsection
