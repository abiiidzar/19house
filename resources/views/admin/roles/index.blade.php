@extends('layouts.admin')
@section('title', 'Roles & Permissions')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-semibold">Roles & permissions</h1>
        <p class="mt-1 text-sm text-neutral-500">System roles are fixed. Cashier and management permissions can be adjusted; admin and customer roles are protected.</p>
    </div>

    @include('admin._search', ['id' => 'role-search', 'placeholder' => 'Search role name or slug'])

    <div class="space-y-3 sm:hidden">
        @foreach($roles as $role)
            <article class="border bg-white p-4">
                <h2 class="font-medium">{{ $role->name }}</h2>
                <p class="mt-2 text-sm text-neutral-600">{{ $role->users_count }} users · {{ $role->permissions_count }} permissions</p>
                @if(in_array($role->slug, ['cashier', 'management'], true))
                    <a class="mt-3 inline-block py-2 text-sm underline" href="{{ route('admin.roles.edit', $role) }}">Edit permissions</a>
                @else
                    <span class="mt-3 block text-sm text-neutral-500">Protected</span>
                @endif
            </article>
        @endforeach
    </div>

    <div class="hidden overflow-x-auto border bg-white sm:block">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b">
                    <th class="p-4">Role</th>
                    <th class="p-4">Users</th>
                    <th class="p-4">Permissions</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr class="border-b">
                        <td class="p-4">{{ $role->name }}</td>
                        <td class="p-4">{{ $role->users_count }}</td>
                        <td class="p-4">{{ $role->permissions_count }}</td>
                        <td class="p-4">
                            @if(in_array($role->slug, ['cashier', 'management'], true))
                                <a class="underline" href="{{ route('admin.roles.edit', $role) }}">Edit permissions</a>
                            @else
                                <span class="text-neutral-500">Protected</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
