@php
    $scrollTo = $scrollTo ?? 'body';
    $scrollIntoViewJsSnippet = $scrollTo !== false
        ? "(\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()"
        : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="mt-6 flex items-center justify-between gap-4 border-t border-neutral-300 pt-5">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">« Previous</span>
            @else
                @if (method_exists($paginator, 'getCursorName'))
                    @php($previousCursor = $paginator->previousCursor() ?? $paginator->cursor())
                    <button type="button" wire:click="setPage('{{ $previousCursor?->encode() }}', '{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white disabled:opacity-40">« Previous</button>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white disabled:opacity-40">« Previous</button>
                @endif
            @endif

            @if ($paginator->hasMorePages())
                @if (method_exists($paginator, 'getCursorName'))
                    @php($nextCursor = $paginator->nextCursor() ?? $paginator->cursor())
                    <button type="button" wire:click="setPage('{{ $nextCursor?->encode() }}', '{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white disabled:opacity-40">Next »</button>
                @else
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center border border-neutral-400 bg-white px-4 text-xs font-medium uppercase tracking-[0.1em] transition-colors hover:border-black hover:bg-black hover:text-white disabled:opacity-40">Next »</button>
                @endif
            @else
                <span aria-disabled="true" class="inline-flex min-h-11 cursor-not-allowed items-center border border-neutral-200 px-4 text-xs font-medium uppercase tracking-[0.1em] text-neutral-300">Next »</span>
            @endif
        </nav>
    @endif
</div>
