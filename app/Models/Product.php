<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'DRAFT';

    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_INACTIVE = 'INACTIVE';

    public const STATUS_ARCHIVED = 'ARCHIVED';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'status',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class)
            ->withPivot('display_order')
            ->withTimestamps();
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function skus(): HasMany
    {
        return $this->hasMany(ProductSku::class);
    }

    // Helper untuk cek apakah product siap diaktifkan (Rule Phase 3)
    public function isReadyToActivate(): bool
    {
        // Harus punya variant
        if (! $this->variants()->exists()) {
            return false;
        }

        // Cek setiap variant apakah sudah punya SKU dan Primary Image
        $variants = $this->variants()->with('images', 'skus')->get();

        foreach ($variants as $variant) {
            if (! $variant->skus()->exists()) {
                return false;
            }
            if (! $variant->images()->where('is_primary', true)->exists()) {
                return false;
            }
        }

        return true;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
