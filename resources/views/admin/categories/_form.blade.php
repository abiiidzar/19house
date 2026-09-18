@csrf

<div class="space-y-8">

    {{-- BASIC INFORMATION --}}
    <section class="border-b border-neutral-200 pb-8">
        <div class="mb-5">
            <h2 class="font-semibold">Basic information</h2>
            <p class="mt-1 text-sm text-neutral-500">Category name, URL slug, and description.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium">Name</span>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. T-Shirts" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3" required>
                @error('name') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium">Slug</span>
                <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" placeholder="e.g. t-shirts" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">
                <p class="mt-1 text-xs text-neutral-500">Used in the category URL.</p>
                @error('slug') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>
        </div>

        <label class="mt-5 block">
            <span class="text-sm font-medium">Description</span>
            <textarea name="description" rows="4" placeholder="Describe this product category..." class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">{{ old('description', $category->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
        </label>
    </section>

    {{-- STOREFRONT --}}
    <section class="border-b border-neutral-200 pb-8">
        <div class="mb-5">
            <h2 class="font-semibold">Storefront</h2>
            <p class="mt-1 text-sm text-neutral-500">Control category photography, display order, and visibility.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block">
                    <span class="text-sm font-medium">Category Image</span>
                    <input type="file" name="image" accept="image/*" class="mt-2 w-full border border-neutral-300 bg-white p-3 text-sm">
                </label>

                <p class="mt-2 text-xs text-neutral-500">Used for visual category sections on the storefront.</p>
                @error('image') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror

                @if(isset($category) && $category->image)
                    <div class="mt-4">
                        <p class="mb-2 text-xs uppercase tracking-wider text-neutral-500">Current Image</p>

                        <div class="aspect-[3/4] w-40 overflow-hidden bg-neutral-100">
                            <img src="{{ Storage::disk('public')->url($category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover">
                        </div>

                        <p class="mt-2 text-xs text-neutral-500">Upload a new image to replace the current image.</p>
                    </div>
                @endif
            </div>

            <div class="space-y-5">
                <label class="block">
                    <span class="text-sm font-medium">Display Order</span>
                    <input type="number" min="0" name="display_order" value="{{ old('display_order', $category->display_order ?? 0) }}" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">
                    <p class="mt-1 text-xs text-neutral-500">Lower numbers appear first when categories are ordered by display order.</p>
                    @error('display_order') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                </label>

                <label class="flex cursor-pointer items-start gap-3 border border-neutral-200 bg-white p-4">
                    <input type="checkbox" name="is_active" value="1" class="mt-0.5" @checked(old('is_active', $category->is_active ?? true))>
                    <span>
                        <span class="block text-sm font-medium">Active Category</span>
                        <span class="mt-1 block text-xs text-neutral-500">Allow this category to appear in storefront areas that use active categories.</span>
                    </span>
                </label>
            </div>
        </div>
    </section>

    {{-- PREVIEW / CURRENT INFORMATION --}}
    @isset($category)
        <section>
            <div class="mb-5">
                <h2 class="font-semibold">Category information</h2>
                <p class="mt-1 text-sm text-neutral-500">Current catalogue usage for this category.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="border bg-white p-4">
                    <p class="text-xs uppercase tracking-wider text-neutral-500">Products</p>
                    <p class="mt-2 text-xl font-semibold">{{ $category->products_count ?? $category->products()->count() }}</p>
                </div>

                <div class="border bg-white p-4">
                    <p class="text-xs uppercase tracking-wider text-neutral-500">Display Order</p>
                    <p class="mt-2 text-xl font-semibold">{{ $category->display_order }}</p>
                </div>

                <div class="border bg-white p-4">
                    <p class="text-xs uppercase tracking-wider text-neutral-500">Status</p>
                    <p class="mt-2 text-sm font-medium {{ $category->is_active ? 'text-green-700' : 'text-neutral-500' }}">{{ $category->is_active ? 'ACTIVE' : 'INACTIVE' }}</p>
                </div>
            </div>
        </section>
    @endisset

    {{-- ACTION --}}
    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-neutral-200 pt-6">
        <a href="{{ route('admin.categories.index') }}" class="border border-neutral-300 px-5 py-3 text-sm font-medium transition hover:border-black">CANCEL</a>
        <button type="submit" class="bg-black px-6 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">SAVE CATEGORY</button>
    </div>

</div>
