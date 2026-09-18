<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin')
            || ($this->user()?->hasPermission('products.create') ?? false);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug
                ? Str::slug($this->slug)
                : Str::slug($this->name),
            'status' => $this->input('status', Product::STATUS_DRAFT),
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'unique:products,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'base_price' => [
                'required',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',

                Rule::in([
                    Product::STATUS_DRAFT,
                    Product::STATUS_ACTIVE,
                    Product::STATUS_INACTIVE,
                ]),
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'collections' => [
                'nullable',
                'array',
            ],

            'collections.*' => [
                'integer',
                'exists:collections,id',
            ],
        ];
    }
}
