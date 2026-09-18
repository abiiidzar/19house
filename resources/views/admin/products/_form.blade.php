@csrf

<div class="space-y-8">

    {{-- BASIC INFORMATION --}}
    <section class="border-b border-neutral-200 pb-8">
        <div class="mb-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Basic Information</p>
            <p class="mt-1 text-sm text-neutral-500">Basic information used to identify this product.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium">Product Name</span>
                <input name="name" value="{{ old('name', $product->name ?? '') }}" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3" required>
                @error('name') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium">Slug</span>
                <input name="slug" value="{{ old('slug', $product->slug ?? '') }}" placeholder="essential-tee" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">
                @error('slug') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>
        </div>

        <label class="mt-5 block">
            <span class="text-sm font-medium">Description</span>
            <textarea name="description" rows="5" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
        </label>
    </section>

    {{-- ORGANIZATION --}}
    <section class="border-b border-neutral-200 pb-8">
        <div class="mb-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Organization</p>
            <p class="mt-1 text-sm text-neutral-500">Organize the product using categories and collections.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-[minmax(0,1fr)_minmax(0,2fr)]">
            <label class="block">
                <span class="text-sm font-medium">Category</span>
                <select name="category_id" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">
                    <option value="">No category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>

            <div>
                <p class="text-sm font-medium">Collections</p>

                <div class="mt-2 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($collections as $collection)
                        <label class="flex cursor-pointer items-center gap-3 border border-neutral-200 bg-white px-4 py-3 transition hover:border-neutral-400">
                            <input type="checkbox" name="collections[]" value="{{ $collection->id }}" @checked(in_array($collection->id, old('collections', isset($product) ? $product->collections->pluck('id')->all() : [])))>
                            <span class="text-sm">{{ $collection->name }}</span>
                        </label>
                    @endforeach
                </div>

                @if($collections->isEmpty())
                    <p class="mt-2 text-sm text-neutral-500">No collections available.</p>
                @endif

                @error('collections') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    {{-- PRICING & VISIBILITY --}}
    <section>
        <div class="mb-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Pricing & Visibility</p>
            <p class="mt-1 text-sm text-neutral-500">Configure the default product price and storefront visibility.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium">Base Price</span>
                <div class="mt-2 flex border border-neutral-300 bg-white">
                    <span class="flex items-center border-r border-neutral-300 px-4 text-sm text-neutral-500">Rp</span>
                    <input type="number" min="0" name="base_price" value="{{ old('base_price', $product->base_price ?? 0) }}" class="w-full border-0 px-4 py-3 focus:ring-0" required>
                </div>
                <p class="mt-1 text-xs text-neutral-500">Used as the default price when a SKU does not have a price override.</p>
                @error('base_price') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium">Status</span>
                <select name="status" class="mt-2 w-full border border-neutral-300 bg-white px-4 py-3">
                    <option value="DRAFT" @selected(old('status', $product->status ?? 'DRAFT') === 'DRAFT')>Draft</option>
                    <option value="ACTIVE" @selected(old('status', $product->status ?? 'DRAFT') === 'ACTIVE')>Active</option>
                    <option value="INACTIVE" @selected(old('status', $product->status ?? 'DRAFT') === 'INACTIVE')>Inactive</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </label>
        </div>

        <label class="mt-5 flex cursor-pointer items-start gap-3 border border-neutral-200 bg-white p-4">
            <input type="checkbox" name="is_featured" value="1" class="mt-0.5" @checked(old('is_featured', $product->is_featured ?? false))>
            <span>
                <span class="block text-sm font-medium">Featured Product</span>
                <span class="mt-1 block text-xs text-neutral-500">Highlight this product in storefront sections that use featured products.</span>
            </span>
        </label>
    </section>

    {{-- ACTIONS --}}
    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-neutral-200 pt-6">
        @isset($product)
            <a href="{{ route('admin.products.show', $product) }}" class="border border-neutral-300 px-5 py-3 text-sm font-medium transition hover:border-black">CANCEL</a>
        @else
            <a href="{{ route('admin.products.index') }}" class="border border-neutral-300 px-5 py-3 text-sm font-medium transition hover:border-black">CANCEL</a>
        @endisset

        <button type="submit" class="bg-black px-6 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">SAVE PRODUCT</button>
    </div>

</div>
