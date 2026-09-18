<?php

namespace App\Livewire\Admin\Catalog;

use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariantImage;
use App\Models\Size;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductVariantManager extends Component
{
    use WithFileUploads;

    public Product $product;

    // Variant Props
    #[Validate('required|string|max:255')]
    public $variantName = '';

    #[Validate('nullable|string|max:7')]
    public $variantHex = '';

    public ?int $editingVariantId = null;

    public string $editingVariantName = '';

    public string $editingVariantHex = '';

    // SKU Props
    public $selectedVariantId = null;

    #[Validate('required|exists:sizes,id')]
    public $sizeId = null;

    #[Validate('required|string')]
    public $skuCode = '';

    #[Validate('nullable|integer|min:0')]
    public $skuPrice = null;

    // Image Props
    #[Validate('nullable|image|max:4096')]
    public $newImage;

    public $newImagePreview = null; // For temporary preview

    public function mount(Product $product): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('admin') || $user?->hasPermission('products.update'), 403);
        $this->product = $product;
    }

    public function updatedNewImage(): void
    {
        // Generate preview URL before uploading
        if ($this->newImage) {
            $this->newImagePreview = $this->newImage->temporaryUrl();
        }
    }

    public function createVariant(): void
    {
        $this->validate([
            'variantName' => 'required|string|max:255',
            'variantHex' => 'nullable|string|max:7',
        ]);

        $order = $this->product->variants()->count();

        $this->product->variants()->create([
            'name' => $this->variantName,
            'hex_code' => $this->variantHex,
            'display_order' => $order,
            'is_active' => true,
        ]);

        $this->reset(['variantName', 'variantHex']);
        $this->dispatch('variant-created');
    }

    public function deleteVariant($variantId): void
    {
        $variant = $this->product->variants()->findOrFail($variantId);

        foreach ($variant->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $variant->delete();
        $this->dispatch('variant-deleted');
    }

    public function toggleVariant(int $variantId): void
    {
        $variant = $this->product->variants()->findOrFail($variantId);
        $variant->update(['is_active' => ! $variant->is_active]);
        $this->dispatch('variant-updated');
    }

    public function startEditingVariant(int $variantId): void
    {
        $variant = $this->product->variants()->findOrFail($variantId);
        $this->editingVariantId = $variant->id;
        $this->editingVariantName = $variant->name;
        $this->editingVariantHex = $variant->hex_code ?? '';
    }

    public function updateVariant(): void
    {
        $validated = $this->validate([
            'editingVariantId' => 'required|integer',
            'editingVariantName' => 'required|string|max:255',
            'editingVariantHex' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
        $variant = $this->product->variants()->findOrFail($validated['editingVariantId']);
        $variant->update([
            'name' => $validated['editingVariantName'],
            'hex_code' => $validated['editingVariantHex'] ?: null,
        ]);
        $this->reset(['editingVariantId', 'editingVariantName', 'editingVariantHex']);
        $this->dispatch('variant-updated');
    }

    public function moveVariant(int $variantId, string $direction): void
    {
        abort_unless(in_array($direction, ['up', 'down'], true), 422);
        $variant = $this->product->variants()->findOrFail($variantId);
        $operator = $direction === 'up' ? '<' : '>';
        $order = $direction === 'up' ? 'desc' : 'asc';
        $sibling = $this->product->variants()
            ->where('display_order', $operator, $variant->display_order)
            ->orderBy('display_order', $order)
            ->first();

        if ($sibling) {
            [$variantOrder, $siblingOrder] = [$variant->display_order, $sibling->display_order];
            $variant->update(['display_order' => $siblingOrder]);
            $sibling->update(['display_order' => $variantOrder]);
        }
    }

    public function createSku(?int $variantId = null): void
    {
        if ($variantId !== null) {
            $this->selectedVariantId = $variantId;
        }
        $this->validate([
            'selectedVariantId' => 'required|exists:product_variants,id',
            'sizeId' => 'required|exists:sizes,id',
            'skuCode' => 'required|string|unique:product_skus,sku',
            'skuPrice' => 'nullable|integer|min:0',
        ]);

        // Custom Validation: Variant + Size must be unique
        $variant = $this->product->variants()->findOrFail($this->selectedVariantId);

        $exists = ProductSku::where('product_variant_id', $variant->id)
            ->where('size_id', $this->sizeId)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'sizeId' => 'Variant ini sudah memiliki Size yang sama.',
            ]);
        }

        ProductSku::create([
            'product_id' => $this->product->id,
            'product_variant_id' => $variant->id,
            'size_id' => $this->sizeId,
            'sku' => $this->skuCode,
            'price' => $this->skuPrice,
        ]);

        $this->reset(['selectedVariantId', 'sizeId', 'skuCode', 'skuPrice']);
        $this->dispatch('sku-created');
    }

    public function deleteSku($skuId): void
    {
        $this->product->skus()->findOrFail($skuId)->delete();
        $this->dispatch('sku-deleted');
    }

    public function saveImage($variantId): void
    {
        $this->validate(['newImage' => 'image|max:4096']);

        $variant = $this->product->variants()->findOrFail($variantId);

        $path = $this->newImage->store('variants', 'public');

        $variant->images()->create([
            'path' => $path,
            'is_primary' => ! $variant->images()->exists(),
            'display_order' => $variant->images()->count(),
        ]);

        $this->reset(['newImage', 'newImagePreview']);
        $this->dispatch('image-uploaded');
    }

    public function deleteImage($imageId): void
    {
        $image = ProductVariantImage::query()
            ->whereHas('variant', fn ($query) => $query->where('product_id', $this->product->id))
            ->findOrFail($imageId);
        $variant = $image->variant;

        Storage::disk('public')->delete($image->path);
        $image->delete();

        // Jika gambar yang dihapus adalah primary, dan masih ada gambar lain, set primary baru
        if ($variant->images()->exists() && ! $variant->images()->where('is_primary', true)->exists()) {
            $variant->images()->first()->update(['is_primary' => true]);
        }

        $this->dispatch('image-deleted');
    }

    public function setPrimaryImage(int $imageId): void
    {
        $image = ProductVariantImage::query()
            ->whereHas('variant', fn ($query) => $query->where('product_id', $this->product->id))
            ->findOrFail($imageId);

        $image->variant->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        $this->dispatch('image-updated');
    }

    public function moveImage(int $imageId, string $direction): void
    {
        abort_unless(in_array($direction, ['up', 'down'], true), 422);

        $image = ProductVariantImage::query()
            ->whereHas('variant', fn ($query) => $query->where('product_id', $this->product->id))
            ->findOrFail($imageId);
        $operator = $direction === 'up' ? '<' : '>';
        $order = $direction === 'up' ? 'desc' : 'asc';
        $sibling = $image->variant->images()
            ->where('display_order', $operator, $image->display_order)
            ->orderBy('display_order', $order)
            ->first();

        if ($sibling) {
            [$imageOrder, $siblingOrder] = [$image->display_order, $sibling->display_order];
            $image->update(['display_order' => $siblingOrder]);
            $sibling->update(['display_order' => $imageOrder]);
        }
    }

    public function render()
    {
        $sizes = Size::where('is_active', true)->orderBy('display_order')->get();

        $variants = $this->product->variants()
            ->with(['images', 'skus.size'])
            ->orderBy('display_order')
            ->get();

        return view('livewire.admin.catalog.product-variant-manager', compact('sizes', 'variants'));
    }
}
