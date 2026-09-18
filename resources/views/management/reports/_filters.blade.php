<form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-end gap-3 border border-neutral-200 bg-white p-4 text-sm">
    <label><span class="commerce-label block">From</span><input type="date" name="from" value="{{ $filters['from'] }}" class="mt-2 block border border-neutral-300 px-3 py-2"></label>
    <label><span class="commerce-label block">To</span><input type="date" name="to" value="{{ $filters['to'] }}" class="mt-2 block border border-neutral-300 px-3 py-2"></label>
    @if($showChannel ?? true)
        <label><span class="commerce-label block">Channel</span><select name="channel" class="mt-2 block border border-neutral-300 px-3 py-2"><option value="ALL" @selected($filters['channel'] === 'ALL')>All channels</option><option value="ONLINE" @selected($filters['channel'] === 'ONLINE')>Online</option><option value="POS" @selected($filters['channel'] === 'POS')>POS</option></select></label>
    @endif
    <button class="bg-black px-5 py-2.5 text-xs font-medium uppercase tracking-wider text-white">Apply filters</button>
</form>
