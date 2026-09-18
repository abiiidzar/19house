@csrf

<div class="space-y-8">

    {{-- BASIC INFORMATION --}}
    <section class="border-b border-neutral-200 pb-8">
        <div class="mb-5">
            <h2 class="font-semibold">Basic information</h2>
            <p class="mt-1 text-sm text-neutral-500">Collection name, URL slug, and description.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium">Name</span>
                <input type="text" name="name" value="{{ old('name', $collection->name ?? '') }}" placeholder="e.g. Spring Summer 2026" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3" required>
                @error('name') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium">Slug</span>
                <input type="text" name="slug" value="{{ old('slug', $collection->slug ?? '') }}" placeholder="e.g. spring-summer-2026" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">
                <p class="mt-1 text-xs text-neutral-500">Used in the collection URL.</p>
                @error('slug') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>
        </div>

        <label class="mt-5 block">
            <span class="text-sm font-medium">Description</span>
            <textarea name="description" rows="4" placeholder="Describe this collection..." class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">{{ old('description', $collection->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
        </label>
    </section>

    {{-- STOREFRONT --}}
    <section class="border-b border-neutral-200 pb-8">
        <div class="mb-5">
            <h2 class="font-semibold">Storefront</h2>
            <p class="mt-1 text-sm text-neutral-500">Control collection photography, display order, and visibility.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block">
                    <span class="text-sm font-medium">Collection Image</span>
                    <input type="file" name="image" accept="image/*" class="mt-2 w-full border border-neutral-300 bg-white p-3 text-sm">
                </label>

                <p class="mt-2 text-xs text-neutral-500">Use a campaign or editorial image that represents this collection.</p>
                @error('image') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror

                @if(isset($collection) && $collection->image)
                    <div class="mt-4">
                        <p class="mb-2 text-xs uppercase tracking-wider text-neutral-500">Current Image</p>

                        <div class="aspect-[3/4] w-40 overflow-hidden bg-neutral-100">
                            <img src="{{ Storage::disk('public')->url($collection->image) }}" alt="{{ $collection->name }}" class="h-full w-full object-cover">
                        </div>

                        <p class="mt-2 text-xs text-neutral-500">Upload a new image to replace the current image.</p>
                    </div>
                @endif
            </div>

            <div class="space-y-5">
                <label class="block">
                    <span class="text-sm font-medium">Display Order</span>
                    <input type="number" min="0" name="display_order" value="{{ old('display_order', $collection->display_order ?? 0) }}" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">
                    <p class="mt-1 text-xs text-neutral-500">Lower numbers appear first when collections are ordered by display order.</p>
                    @error('display_order') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                </label>

                <label class="flex cursor-pointer items-start gap-3 border border-neutral-200 bg-white p-4">
                    <input type="checkbox" name="is_active" value="1" class="mt-0.5" @checked(old('is_active', $collection->is_active ?? true))>
                    <span>
                        <span class="block text-sm font-medium">Active Collection</span>
                        <span class="mt-1 block text-xs text-neutral-500">Allow this collection to appear in storefront areas that use active collections.</span>
                    </span>
                </label>
            </div>
        </div>
    </section>

    {{-- PRODUCTS --}}
    <section>
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="font-semibold">Products</h2>
                <p class="mt-1 text-sm text-neutral-500">Choose the products included in this collection.</p>
            </div>

            @isset($collection)
                <p class="text-xs text-neutral-500">{{ $collection->products->count() }} currently assigned</p>
            @endisset
        </div>

        @if($products->isNotEmpty())
            <div class="grid gap-2 md:grid-cols-2">
                @foreach($products as $product)
                    <label class="flex cursor-pointer items-center justify-between gap-4 border bg-white p-4 transition hover:border-neutral-400">
                        <div class="flex min-w-0 items-center gap-3">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" @checked(in_array($product->id, old('products', isset($collection) ? $collection->products->pluck('id')->all() : [])))>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium">{{ $product->name }}</p>
                                <p class="mt-1 truncate text-xs text-neutral-500">{{ $product->slug }}</p>
                            </div>
                        </div>

                        <span class="shrink-0 text-xs tabular-nums text-neutral-500">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                    </label>
                @endforeach
            </div>
        @else
            <div class="border border-dashed px-5 py-10 text-center">
                <p class="font-medium">No products available.</p>
                <p class="mt-1 text-sm text-neutral-500">Create products before assigning them to a collection.</p>
            </div>
        @endif

        @error('products') <p class="mt-2 text-xs text-red-700">{{ $message }}</p> @enderror
    </section>

    {{-- ACTIONS --}}
    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-neutral-200 pt-6">
        @isset($collection)
            <a href="{{ route('admin.collections.show', $collection) }}" class="border border-neutral-300 px-5 py-3 text-sm font-medium transition hover:border-black">CANCEL</a>
        @else
            <a href="{{ route('admin.collections.index') }}" class="border border-neutral-300 px-5 py-3 text-sm font-medium transition hover:border-black">CANCEL</a>
        @endisset

        <button type="submit" class="bg-black px-6 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">SAVE COLLECTION</button>
    </div>

</div>
