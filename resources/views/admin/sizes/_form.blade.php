@csrf

<div class="space-y-6">

    <div class="border-b border-neutral-200 pb-6">
        <div class="grid gap-5 sm:grid-cols-2">

            <label class="block">
                <span class="text-sm font-medium">Size Name</span>
                <input type="text" name="name" value="{{ old('name', $size->name ?? '') }}" placeholder="e.g. M" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3" required>
                <p class="mt-1 text-xs text-neutral-500">Examples: XS, S, M, L, XL.</p>
                @error('name') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium">Display Order</span>
                <input type="number" min="0" name="display_order" value="{{ old('display_order', $size->display_order ?? 0) }}" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3" required>
                <p class="mt-1 text-xs text-neutral-500">Lower numbers appear first in size selections.</p>
                @error('display_order') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>

        </div>
    </div>

    <label class="flex cursor-pointer items-start gap-3 border border-neutral-200 bg-white p-4">
        <input type="checkbox" name="is_active" value="1" class="mt-0.5" @checked(old('is_active', $size->is_active ?? true))>

        <span>
            <span class="block text-sm font-medium">Active Size</span>
            <span class="mt-1 block text-xs text-neutral-500">Allow this size to be used when configuring product SKUs.</span>
        </span>
    </label>

    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-neutral-200 pt-6">
        <a href="{{ route('admin.sizes.index') }}" class="border border-neutral-300 px-5 py-3 text-sm font-medium transition hover:border-black">CANCEL</a>
        <button type="submit" class="bg-black px-6 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">SAVE SIZE</button>
    </div>

</div>
