@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-6 flex items-center justify-between gap-4 border-t border-neutral-300 pt-5">
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">« Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">« Previous</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">Next »</a>
        @else
            <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">Next »</span>
        @endif
    </nav>
@endif
