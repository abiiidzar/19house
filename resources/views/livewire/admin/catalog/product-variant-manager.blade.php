<div class="space-y-6">

    {{-- FLASH MESSAGE --}}
    @if(session()->has('message'))
        <div class="border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('message') }}</div>
    @endif

    {{-- ADD VARIANT --}}
    <div class="border border-neutral-200 bg-white">
        <div class="border-b border-neutral-200 px-5 py-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Add Variant</p>
            <p class="mt-1 text-sm text-neutral-500">Create a color or style variant for this product.</p>
        </div>

        <form wire:submit="createVariant" class="grid gap-4 p-5 md:grid-cols-[minmax(0,1fr)_7rem_auto] md:items-end">
            <div>
                <label class="mb-2 block text-xs font-medium uppercase tracking-wider">Variant Name</label>
                <input type="text" wire:model="variantName" placeholder="e.g. Black" class="w-full border border-neutral-300 bg-white px-4 py-3 focus:border-black focus:outline-none">
                @error('variantName') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-medium uppercase tracking-wider">Color</label>
                <input type="color" wire:model="variantHex" class="h-12 w-full cursor-pointer border border-neutral-300 bg-white p-1">
            </div>

            <button type="submit" class="h-12 bg-black px-5 text-xs font-medium uppercase tracking-wider text-white transition hover:bg-neutral-800">Add Variant</button>
        </form>
    </div>

    {{-- VARIANT LIST --}}
    <div class="space-y-5">
        @forelse($variants as $variant)
            <article class="border border-neutral-200 bg-white">

                {{-- VARIANT HEADER --}}
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-neutral-200 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <span class="h-7 w-7 shrink-0 border border-neutral-300" style="background-color: {{ $variant->hex_code ?? '#ffffff' }}"></span>

                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold">{{ $variant->name }}</h3>
                                <span class="text-[10px] font-medium uppercase tracking-wider {{ $variant->is_active ? 'text-green-700' : 'text-neutral-400' }}">{{ $variant->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>

                            <p class="mt-1 text-xs text-neutral-500">{{ $variant->images->count() }} images · {{ $variant->skus->count() }} SKUs</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <button type="button" wire:click="moveVariant({{ $variant->id }}, 'up')" class="text-xs uppercase tracking-wider text-neutral-500 hover:text-black">Move Up</button>
                        <button type="button" wire:click="moveVariant({{ $variant->id }}, 'down')" class="text-xs uppercase tracking-wider text-neutral-500 hover:text-black">Move Down</button>
                        <button type="button" wire:click="startEditingVariant({{ $variant->id }})" class="text-xs uppercase tracking-wider hover:underline">Edit</button>
                        <button type="button" wire:click="toggleVariant({{ $variant->id }})" class="text-xs uppercase tracking-wider hover:underline">{{ $variant->is_active ? 'Deactivate' : 'Activate' }}</button>
                        <button type="button" wire:click="deleteVariant({{ $variant->id }})" wire:confirm="Yakin hapus variant ini?" class="text-xs uppercase tracking-wider text-red-600 hover:underline">Delete</button>
                    </div>
                </div>

                {{-- EDIT VARIANT --}}
                @if($editingVariantId === $variant->id)
                    <form wire:submit="updateVariant" class="grid gap-3 border-b border-neutral-200 bg-neutral-50 p-5 md:grid-cols-[minmax(0,1fr)_7rem_auto] md:items-end">
                        <div>
                            <label class="mb-2 block text-xs font-medium uppercase tracking-wider">Variant Name</label>
                            <input wire:model="editingVariantName" class="w-full border border-neutral-300 bg-white px-4 py-3 text-sm focus:border-black focus:outline-none">
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-medium uppercase tracking-wider">Color</label>
                            <input type="color" wire:model="editingVariantHex" class="h-12 w-full cursor-pointer border border-neutral-300 bg-white p-1">
                        </div>

                        <button type="submit" class="h-12 bg-black px-5 text-xs font-medium uppercase tracking-wider text-white">Save</button>
                    </form>
                @endif

                <div class="grid gap-8 p-5 xl:grid-cols-2">

                    {{-- IMAGES --}}
                    <section>
                        <div class="mb-4 flex items-end justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Images</p>
                                <p class="mt-1 text-xs text-neutral-400">Product photography for {{ $variant->name }}.</p>
                            </div>

                            <span class="text-xs text-neutral-500">{{ $variant->images->count() }} images</span>
                        </div>

                        @if($variant->images->isNotEmpty())
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach($variant->images as $img)
                                    <div class="group relative aspect-[3/4] overflow-hidden border border-neutral-200 bg-neutral-100">
                                        <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }} - {{ $variant->name }}" class="h-full w-full object-cover">

                                        @if($img->is_primary)
                                            <span class="absolute left-2 top-2 bg-black px-2 py-1 text-[9px] font-medium uppercase tracking-wider text-white">Primary</span>
                                        @else
                                            <button type="button" wire:click="setPrimaryImage({{ $img->id }})" class="absolute inset-x-2 bottom-2 bg-black/80 px-2 py-2 text-[9px] uppercase tracking-wider text-white transition hover:bg-black">Set Primary</button>
                                        @endif

                                        <button type="button" wire:click="deleteImage({{ $img->id }})" wire:confirm="Hapus gambar ini?" aria-label="Delete image" class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center bg-white text-xs text-red-600 opacity-100 shadow-sm transition md:opacity-0 md:group-hover:opacity-100">×</button>

                                        <div class="absolute bottom-2 right-2 flex overflow-hidden border border-neutral-200 bg-white">
                                            <button type="button" wire:click="moveImage({{ $img->id }}, 'up')" aria-label="Move image earlier" class="flex h-7 w-7 items-center justify-center text-xs hover:bg-neutral-100">←</button>
                                            <button type="button" wire:click="moveImage({{ $img->id }}, 'down')" aria-label="Move image later" class="flex h-7 w-7 items-center justify-center border-l border-neutral-200 text-xs hover:bg-neutral-100">→</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex aspect-[3/1] items-center justify-center border border-dashed border-neutral-300 bg-neutral-50">
                                <p class="text-sm text-neutral-400">No images uploaded.</p>
                            </div>
                        @endif

                        {{-- IMAGE UPLOAD --}}
                        <form wire:submit="saveImage({{ $variant->id }})" class="mt-4 border border-dashed border-neutral-300 bg-neutral-50 p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <div class="flex-1">
                                    <label class="mb-2 block text-xs font-medium uppercase tracking-wider">Upload Image</label>
                                    <input type="file" wire:model="newImage" accept="image/*" class="w-full text-xs file:mr-3 file:border-0 file:bg-neutral-200 file:px-3 file:py-2 file:text-xs file:font-medium file:uppercase file:tracking-wider">
                                </div>

                                <button type="submit" class="bg-neutral-900 px-5 py-3 text-xs font-medium uppercase tracking-wider text-white transition hover:bg-black">Upload</button>
                            </div>

                            @if($newImagePreview)
                                <div class="mt-4">
                                    <p class="mb-2 text-xs uppercase tracking-wider text-neutral-500">Preview</p>
                                    <div class="aspect-[3/4] w-28 overflow-hidden border border-neutral-300 bg-white">
                                        <img src="{{ $newImagePreview }}" alt="Upload preview" class="h-full w-full object-cover">
                                    </div>
                                </div>
                            @endif

                            @error('newImage') <p class="mt-2 text-xs text-red-700">{{ $message }}</p> @enderror
                        </form>
                    </section>

                    {{-- SKUS --}}
                    <section>
                        <div class="mb-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Sizes & SKUs</p>
                            <p class="mt-1 text-xs text-neutral-400">Manage size, SKU code, and optional price override.</p>
                        </div>

                        @if($variant->skus->isNotEmpty())
                            <div class="overflow-x-auto border border-neutral-200">
                                <table class="w-full min-w-[30rem] text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-neutral-200 bg-neutral-50 text-[10px] uppercase tracking-wider text-neutral-500">
                                            <th class="px-3 py-3">Size</th>
                                            <th class="px-3 py-3">SKU</th>
                                            <th class="px-3 py-3 text-right">Price</th>
                                            <th class="px-3 py-3 text-right">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($variant->skus as $sku)
                                            <tr class="border-b border-neutral-200 last:border-b-0">
                                                <td class="px-3 py-3 font-medium">{{ $sku->size->name ?? '-' }}</td>
                                                <td class="px-3 py-3 font-mono text-xs">{{ $sku->sku }}</td>
                                                <td class="whitespace-nowrap px-3 py-3 text-right font-medium tabular-nums">Rp {{ number_format($sku->price ?: $product->base_price, 0, ',', '.') }}</td>
                                                <td class="px-3 py-3 text-right">
                                                    <button type="button" wire:click="deleteSku({{ $sku->id }})" wire:confirm="Yakin hapus SKU ini?" class="text-xs uppercase tracking-wider text-red-600 hover:underline">Remove</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="border border-dashed border-neutral-300 bg-neutral-50 px-4 py-8 text-center">
                                <p class="text-sm text-neutral-400">No SKUs created.</p>
                            </div>
                        @endif

                        {{-- ADD SKU --}}
                        <form wire:submit="createSku({{ $variant->id }})" class="mt-4 space-y-3 border border-neutral-200 bg-neutral-50 p-4">
                            <div>
                                <label class="mb-2 block text-xs font-medium uppercase tracking-wider">Size</label>
                                <select wire:model="sizeId" class="w-full border border-neutral-300 bg-white px-3 py-3 text-sm focus:border-black focus:outline-none">
                                    <option value="">Select Size</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </select>
                                @error('sizeId') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-medium uppercase tracking-wider">SKU Code</label>
                                <input type="text" wire:model="skuCode" placeholder="19H-TEE-BLK-S" class="w-full border border-neutral-300 bg-white px-3 py-3 text-sm focus:border-black focus:outline-none">
                                @error('skuCode') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-medium uppercase tracking-wider">Price Override</label>
                                <div class="flex border border-neutral-300 bg-white">
                                    <span class="flex items-center border-r border-neutral-300 px-3 text-sm text-neutral-500">Rp</span>
                                    <input type="number" min="0" wire:model="skuPrice" placeholder="{{ number_format($product->base_price, 0, ',', '.') }}" class="w-full border-0 px-3 py-3 text-sm focus:ring-0">
                                </div>
                                <p class="mt-1 text-xs text-neutral-500">Leave empty to use the base price of Rp {{ number_format($product->base_price, 0, ',', '.') }}.</p>
                            </div>

                            <button type="submit" class="w-full bg-neutral-900 px-4 py-3 text-xs font-medium uppercase tracking-wider text-white transition hover:bg-black">Add SKU</button>
                        </form>
                    </section>

                </div>
            </article>
        @empty
            <div class="border border-dashed border-neutral-300 bg-neutral-50 px-6 py-12 text-center">
                <p class="font-medium">No variants yet.</p>
                <p class="mt-1 text-sm text-neutral-500">Create the first color variant using the form above.</p>
            </div>
        @endforelse
    </div>

</div>
