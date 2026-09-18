@extends('layouts.admin')
@section('title', 'Users')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center"><div><h1 class="text-2xl font-semibold">Internal users</h1><p class="text-sm text-neutral-500">Customers are listed separately.</p></div><a class="bg-neutral-900 text-white px-4 py-3 text-sm" href="{{ route('admin.users.create') }}">Add user</a></div>
    <form method="GET"><input name="search" value="{{ request('search') }}" placeholder="Search name or email" class="border p-3"><button class="border p-3">Search</button></form>
    <div class="bg-white border overflow-x-auto"><table class="w-full text-sm text-left"><thead><tr class="border-b"><th class="p-4">Name</th><th class="p-4">Email</th><th class="p-4">Role</th><th class="p-4">Status</th><th class="p-4"></th></tr></thead><tbody>
    @forelse($users as $user)<tr class="border-b"><td class="p-4">{{ $user->name }}</td><td class="p-4">{{ $user->email }}</td><td class="p-4">{{ $user->role?->name }}</td><td class="p-4">{{ $user->status }}</td><td class="p-4"><a class="underline" href="{{ route('admin.users.edit', $user) }}">Edit</a></td></tr>
    @empty<tr><td colspan="5" class="p-6 text-center">No users found.</td></tr>@endforelse
    </tbody></table></div>{{ $users->links() }}
</div>
@endsection
