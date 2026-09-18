<div x-data="{
    message: '',
    initSortable() {
        const el = document.getElementById('sortable-products');
        if (el) {
            Sortable.create(el, {
                animation: 150,
                ghostClass: 'bg-neutral-100',
                onEnd: (evt) => {
                    let ids = [];
                    document.querySelectorAll('#sortable-products > li').forEach((item) => {
                        ids.push(item.dataset.id);
                    });
                    Livewire.dispatch('updateOrder', { orderedIds: ids });
                }
            });
        }
        Livewire.on('order-updated', ({ message }) => {
            this.message = message;
            this.show = true;
        });
    }
}" x-init="initSortable()">

    {{-- Notifikasi Sukses --}}
    <div x-data="{ show: false }"
         x-show="show"
         x-transition
         x-init="$watch('show', v => setTimeout(() => show = false, 3000))"
         class="mb-4 border border-green-200 bg-green-50 p-4 text-sm text-green-800"
         style="display: none;">
        <span x-text="message || 'Urutan berhasil disimpan.'"></span>
    </div>

    <div class="bg-white p-5 border">
        <h2 class="font-semibold tracking-wide mb-4">SORT PRODUCTS (DRAG & DROP)</h2>

        @if($products->isEmpty())
            <p class="text-neutral-500 text-sm">Belum ada product di collection ini.</p>
        @else
            <ul id="sortable-products" class="space-y-2">
                @foreach($products as $product)
                    <li data-id="{{ $product->id }}" class="flex items-center justify-between border border-neutral-200 bg-white px-4 py-3 cursor-grab active:cursor-grabbing hover:border-neutral-400 transition">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                            </svg>
                            <span class="text-sm font-medium">{{ $product->name }}</span>
                        </div>
                        <span class="text-xs text-neutral-400">ID: {{ $product->id }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Include SortableJS via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</div>
