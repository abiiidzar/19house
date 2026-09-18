@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-7">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-neutral-500">Sales this month through today; catalog and stock are current.</p>
        </div>
        <p class="text-xs text-neutral-400">{{ now()->format('d M Y') }}</p>
    </div>

    {{-- MAIN STATS --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="border bg-white p-5">
            <p class="text-xs uppercase tracking-wider text-neutral-500">Revenue</p>
            <p class="mt-3 text-2xl font-semibold tabular-nums">Rp {{ number_format($sales['revenue'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-neutral-400">This month</p>
        </div>

        <div class="border bg-white p-5">
            <p class="text-xs uppercase tracking-wider text-neutral-500">Paid Transactions</p>
            <p class="mt-3 text-2xl font-semibold tabular-nums">{{ number_format($sales['transactions']) }}</p>
            <p class="mt-2 text-xs text-neutral-400">Completed payments</p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="group border bg-white p-5 transition hover:border-neutral-900">
            <div class="flex items-start justify-between gap-4">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Orders to Fulfill</p>
                <span class="text-neutral-400 transition group-hover:translate-x-1 group-hover:text-black">→</span>
            </div>
            <p class="mt-3 text-2xl font-semibold tabular-nums">{{ number_format($counts['orders_to_fulfill']) }}</p>
            <p class="mt-2 text-xs text-neutral-400">Waiting for fulfillment</p>
        </a>

        <div class="border bg-white p-5">
            <p class="text-xs uppercase tracking-wider text-neutral-500">Inventory Alerts</p>
            <div class="mt-3 flex items-baseline gap-2">
                <p class="text-2xl font-semibold tabular-nums">{{ $inventory['low_stock'] }}</p>
                <span class="text-xs text-neutral-500">low stock</span>
            </div>
            <p class="mt-2 text-xs {{ $inventory['out_of_stock'] > 0 ? 'text-red-600' : 'text-neutral-400' }}">{{ $inventory['out_of_stock'] }} out of stock</p>
        </div>
    </div>

    {{-- NEEDS ATTENTION --}}
    @if($counts['orders_to_fulfill'] > 0 || $inventory['low_stock'] > 0 || $inventory['out_of_stock'] > 0)
        <section>
            <h2 class="mb-3 font-semibold">Needs attention</h2>

            <div class="grid gap-3 sm:grid-cols-2">
                @if($counts['orders_to_fulfill'] > 0)
                    <a href="{{ route('admin.orders.index') }}" class="group flex items-center justify-between border bg-white p-4 transition hover:border-neutral-900">
                        <div>
                            <p class="text-sm font-medium">Orders waiting for fulfillment</p>
                            <p class="mt-1 text-xs text-neutral-500">Review and process customer orders.</p>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="text-xl font-semibold tabular-nums">{{ $counts['orders_to_fulfill'] }}</span>
                            <span class="text-neutral-400 transition group-hover:translate-x-1 group-hover:text-black">→</span>
                        </div>
                    </a>
                @endif

                @if($inventory['low_stock'] > 0 || $inventory['out_of_stock'] > 0)
                    <div class="flex items-center justify-between border bg-white p-4">
                        <div>
                            <p class="text-sm font-medium">Inventory requires attention</p>
                            <p class="mt-1 text-xs text-neutral-500">{{ $inventory['low_stock'] }} low stock · {{ $inventory['out_of_stock'] }} out of stock</p>
                        </div>

                        <div class="text-right">
                            <p class="text-xl font-semibold tabular-nums">{{ $inventory['low_stock'] + $inventory['out_of_stock'] }}</p>
                            <p class="text-xs text-neutral-400">SKUs</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- CATALOGUE SUMMARY --}}
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach([
            'Products' => ['count' => $counts['products'], 'route' => 'admin.products.index'],
            'Categories' => ['count' => $counts['categories'], 'route' => 'admin.categories.index'],
            'Collections' => ['count' => $counts['collections'], 'route' => 'admin.collections.index']
        ] as $label => $item)
            <a href="{{ route($item['route']) }}" class="group border bg-white p-5 transition hover:border-neutral-900">
                <div class="flex items-start justify-between">
                    <p class="text-xs uppercase tracking-wider text-neutral-500">{{ $label }}</p>
                    <span class="text-neutral-400 transition group-hover:translate-x-1 group-hover:text-black">→</span>
                </div>

                <p class="mt-3 text-2xl font-semibold tabular-nums">{{ number_format($item['count']) }}</p>
                <span class="mt-3 inline-block text-xs underline underline-offset-4">View {{ strtolower($label) }}</span>
            </a>
        @endforeach
    </div>

    {{-- QUICK MENU --}}
    <section>
        <h2 class="mb-3 font-semibold">Quick menu</h2>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach([
                'Add product' => [
                    'route' => 'admin.products.create',
                    'description' => 'Create a new catalog item'
                ],
                'Add category' => [
                    'route' => 'admin.categories.create',
                    'description' => 'Organize your catalog'
                ],
                'Stock in' => [
                    'route' => 'admin.inventory.stock-in.create',
                    'description' => 'Record incoming inventory'
                ],
                'View orders' => [
                    'route' => 'admin.orders.index',
                    'description' => 'Review orders to fulfill'
                ]
            ] as $label => $item)
                <a href="{{ route($item['route']) }}" class="group border bg-white p-4 transition hover:border-neutral-900">
                    <div class="flex items-start justify-between gap-4">
                        <p class="text-sm font-medium">{{ $label }}</p>
                        <span class="text-neutral-400 transition group-hover:translate-x-1 group-hover:text-black">→</span>
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">{{ $item['description'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- RECENT ACTIVITY --}}
    <section class="border bg-white">
        <div class="flex items-center justify-between border-b px-5 py-4">
            <div>
                <h2 class="font-semibold">Recent activity</h2>
                <p class="mt-1 text-xs text-neutral-500">Latest administrative activity.</p>
            </div>

            <a href="{{ route('admin.activity-logs.index') }}" class="text-xs underline underline-offset-4">All activity</a>
        </div>

        @forelse($recentActivities as $activity)
            <div class="grid gap-2 border-b px-5 py-4 last:border-b-0 sm:grid-cols-[1fr_auto] sm:items-start">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium">{{ $activity->action }}</span>
                        <span class="text-xs text-neutral-400">· {{ $activity->user?->name ?? 'System' }}</span>
                    </div>

                    <p class="mt-1 text-sm text-neutral-600">{{ $activity->description }}</p>
                </div>

                <span class="whitespace-nowrap text-xs text-neutral-400">{{ $activity->created_at->format('d M Y H:i') }}</span>
            </div>
        @empty
            <div class="px-5 py-10 text-center">
                <p class="text-sm font-medium">No activity recorded yet.</p>
                <p class="mt-1 text-xs text-neutral-500">Administrative activity will appear here.</p>
            </div>
        @endforelse
    </section>

</div>
@endsection
