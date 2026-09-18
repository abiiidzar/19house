<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin')
            || ($this->user()?->hasPermission('products.update') ?? false);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ? Str::slug($this->slug) : Str::slug($this->name),
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'integer', 'min:0'],
            'status' => [
                'required',
                Rule::in([
                    Product::STATUS_DRAFT,
                    Product::STATUS_ACTIVE,
                    Product::STATUS_INACTIVE,
                ]),
                // Custom validation untuk Phase 3
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value === Product::STATUS_ACTIVE) {
                        $product = $this->route('product');
                        if (! $product->isReadyToActivate()) {
                            $fail('Product tidak bisa diaktifkan karena belum memiliki Variant, SKU, atau Gambar.');
                        }
                    }
                },
            ],
            'is_featured' => ['nullable', 'boolean'],
            'collections' => ['nullable', 'array'],
            'collections.*' => ['integer', 'exists:collections,id'],
        ];
    }
}
