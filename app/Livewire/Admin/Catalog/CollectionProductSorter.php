<?php

namespace App\Livewire\Admin\Catalog;

use App\Models\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class CollectionProductSorter extends Component
{
    public Collection $collection;

    public function mount(Collection $collection): void
    {
        $this->collection = $collection;
    }

    // Method ini akan dipanggil oleh JavaScript (SortableJS) saat ada perubahan urutan
    #[On('updateOrder')]
    public function updateOrder($orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            // Update kolom display_order di tabel pivot (collection_product)
            $this->collection->products()->updateExistingPivot($id, [
                'display_order' => $index,
            ]);
        }

        $this->dispatch('order-updated', message: 'Urutan product berhasil diperbarui.');
    }

    public function render()
    {
        // Ambil product berdasarkan urutan pivot display_order
        $products = $this->collection->products()
            ->orderBy('pivot_display_order', 'asc')
            ->get();

        return view('livewire.admin.catalog.collection-product-sorter', compact('products'));
    }
}
