@extends('layouts.admin')
@section('title', 'Customers')
@section('content')
<div class="space-y-6"><h1 class="text-2xl font-semibold">Customers</h1><form method="GET"><input class="border p-3" name="search" value="{{ request('search') }}" placeholder="Search name or email"><button class="border p-3">Search</button></form>
<div class="overflow-x-auto border bg-white"><table class="w-full text-left text-sm"><thead><tr class="border-b"><th class="p-4">Name</th><th class="p-4">Email</th><th class="p-4">Orders</th><th class="p-4">Joined</th><th class="p-4"></th></tr></thead><tbody>
@forelse($customers as $customer)<tr class="border-b"><td class="p-4">{{ $customer->name }}</td><td class="p-4">{{ $customer->email }}</td><td class="p-4">{{ $customer->orders_count }}</td><td class="p-4">{{ $customer->created_at?->format('d M Y') }}</td><td class="p-4"><a class="underline" href="{{ route('admin.customers.show', $customer) }}">Detail</a></td></tr>@empty<tr><td colspan="5" class="p-6 text-center">No customers.</td></tr>@endforelse
</tbody></table></div>{{ $customers->links() }}</div>
@endsection
