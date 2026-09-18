@props(['label' => 'Image unavailable'])

<div {{ $attributes->class(['flex h-full w-full items-center justify-center bg-[#EFEFEC] p-5 text-center']) }} role="img" aria-label="{{ $label }}">
    <div>
        <span class="brand-logo block text-xs text-neutral-500" aria-hidden="true">19HOUSE</span>
        <span class="mt-2 block text-[11px] leading-4 text-neutral-500" aria-hidden="true">Image unavailable</span>
    </div>
</div>
