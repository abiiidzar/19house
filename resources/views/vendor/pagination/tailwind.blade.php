@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-6 border-t border-neutral-300 pt-5">
        <div class="flex items-center justify-between gap-3 sm:hidden">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">« Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">« Previous</a>
            @endif

            <span class="text-[10px] font-medium uppercase tracking-[0.14em] text-neutral-500">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">Next »</a>
            @else
                <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">Next »</span>
            @endif
        </div>

        <div class="hidden items-center justify-between gap-6 sm:flex">
            <p class="text-xs uppercase tracking-[0.1em] text-neutral-500">
                Showing <span class="font-semibold text-neutral-900">{{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }}</span>
                of <span class="font-semibold text-neutral-900">{{ $paginator->total() }}</span>
            </p>

            <div class="flex items-center gap-1">
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">« Previous</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">« Previous</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" class="inline-flex h-11 min-w-11 items-center justify-center text-sm text-neutral-400">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex h-11 min-w-11 items-center justify-center bg-black px-3 text-sm font-medium text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}" class="inline-flex h-11 min-w-11 items-center justify-center border border-transparent px-3 text-sm text-neutral-600 transition-colors hover:border-neutral-400 hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">Next »</a>
                @else
                    <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">Next »</span>
                @endif
            </div>
        </div>
    </nav>
@endif
