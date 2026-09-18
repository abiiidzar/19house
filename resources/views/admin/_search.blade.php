<form method="GET" action="{{ $action ?? url()->current() }}" class="flex flex-col gap-2 sm:flex-row">
    @foreach(($hidden ?? []) as $name => $value)
        @if($value !== null && $value !== '')
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endif
    @endforeach

    <label class="sr-only" for="{{ $id ?? 'admin-search' }}">Search</label>
    <input
        id="{{ $id ?? 'admin-search' }}"
        type="search"
        name="search"
        value="{{ request('search') }}"
        placeholder="{{ $placeholder ?? 'Search...' }}"
        class="w-full border border-neutral-300 px-4 py-3 text-sm sm:max-w-md"
    >
    <button type="submit" class="bg-neutral-900 px-5 py-3 text-sm font-medium text-white">SEARCH</button>
    @if(request()->filled('search'))
        <a href="{{ ($action ?? url()->current()).(collect($hidden ?? [])->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty() ? '?'.http_build_query(collect($hidden)->filter(fn ($value) => $value !== null && $value !== '')->all()) : '') }}" class="border border-neutral-300 px-5 py-3 text-center text-sm">RESET</a>
    @endif
</form>
