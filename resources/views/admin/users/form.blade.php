@extends('layouts.admin')
@section('title', $user->exists ? 'Edit User' : 'Add User')
@section('content')
<div class="max-w-2xl bg-white border p-6"><h1 class="text-2xl font-semibold mb-6">{{ $user->exists ? 'Edit User' : 'Add User' }}</h1>
<form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-4">@csrf @if($user->exists) @method('PUT') @endif
    @foreach(['name' => 'Name', 'email' => 'Email', 'phone' => 'Phone'] as $key => $label)
    <label class="block text-sm">{{ $label }}<input class="mt-1 block w-full border p-3" name="{{ $key }}" value="{{ old($key, $user->$key) }}" {{ $key !== 'phone' ? 'required' : '' }}></label>@error($key)<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    @endforeach
    <label class="block text-sm">Role<select name="role_id" class="mt-1 block w-full border p-3">@foreach($roles as $role)<option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>@endforeach</select></label>@error('role_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    <label class="block text-sm">Status<select name="status" class="mt-1 block w-full border p-3"><option value="ACTIVE" @selected(old('status', $user->status ?? 'ACTIVE') === 'ACTIVE')>Active</option><option value="INACTIVE" @selected(old('status', $user->status) === 'INACTIVE')>Inactive</option></select></label>
    <label class="block text-sm">Password {{ $user->exists ? '(leave blank to keep)' : '' }}<input type="password" name="password" class="mt-1 block w-full border p-3" {{ $user->exists ? '' : 'required' }}></label>@error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    <label class="block text-sm">Confirm password<input type="password" name="password_confirmation" class="mt-1 block w-full border p-3"></label>
    <button class="bg-neutral-900 text-white px-5 py-3 text-sm">Save user</button>
</form></div>
@endsection
