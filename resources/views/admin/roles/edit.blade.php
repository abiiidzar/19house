@extends('layouts.admin')
@section('title', 'Edit Role Permissions')
@section('content')
<div class="max-w-2xl space-y-6"><a class="text-sm underline" href="{{ route('admin.roles.index') }}">← Roles</a><h1 class="text-2xl font-semibold">{{ $role->name }} permissions</h1>
<form method="POST" action="{{ route('admin.roles.update', $role) }}" class="border bg-white p-6">@csrf @method('PUT')<div class="grid gap-3 sm:grid-cols-2">@foreach($permissions as $permission)<label class="flex gap-3 text-sm"><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', $role->permissions->pluck('id')->all())))><span>{{ $permission->name }}<small class="block text-neutral-500">{{ $permission->slug }}</small></span></label>@endforeach</div>@error('permissions')<p class="mt-3 text-sm text-red-600">{{ $message }}</p>@enderror<button class="mt-6 bg-neutral-900 px-5 py-3 text-sm text-white">Save permissions</button></form></div>
@endsection
