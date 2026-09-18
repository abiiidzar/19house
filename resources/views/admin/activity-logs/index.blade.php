@extends('layouts.admin')
@section('title', 'Activity Log')
@section('content')
<div class="space-y-6"><div><h1 class="text-2xl font-semibold">Activity Log</h1><p class="mt-1 text-sm text-neutral-500">Permanent record of critical catalog, inventory, order, voucher, and role changes.</p></div>
    @include('admin._search', ['id' => 'activity-search', 'placeholder' => 'Search action, description, or user', 'hidden' => ['action' => request('action')]])
    <div class="space-y-3">@forelse($logs as $log)<article class="border border-neutral-200 bg-white p-5"><div class="flex flex-wrap justify-between gap-2"><div><p class="text-sm font-medium">{{ $log->action }}</p><p class="mt-1 text-sm text-neutral-600">{{ $log->description }}</p><p class="mt-1 text-xs text-neutral-500">{{ $log->user?->name ?? 'System' }} · {{ $log->created_at->format('d M Y H:i') }} · {{ class_basename($log->subject_type ?? '') }} #{{ $log->subject_id }}</p></div></div>@if($log->old_values || $log->new_values)<div class="mt-4 grid gap-3 sm:grid-cols-2"><div><p class="mb-1 text-xs uppercase text-neutral-500">Before</p><pre class="overflow-x-auto bg-neutral-50 p-3 text-xs">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></div><div><p class="mb-1 text-xs uppercase text-neutral-500">After</p><pre class="overflow-x-auto bg-neutral-50 p-3 text-xs">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></div></div>@endif</article>@empty<p class="bg-white p-8 text-center text-sm text-neutral-500">No activity yet.</p>@endforelse</div>
    {{ $logs->links() }}
</div>
@endsection
