<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'password',
        'profile_photo_path',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role?->slug === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array(
            $this->role?->slug,
            $roles,
            true
        );
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role
            ?->permissions()
            ->where('slug', $permission)
            ->exists() ?? false;
    }

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }

    public function dashboardRoute(): string
    {
        return match ($this->role?->slug) {
            'admin' => route('admin.dashboard'),
            'cashier' => route('cashier.dashboard'),
            'management' => route('management.dashboard'),
            'customer' => route('home'),
            default => route('home'),
        };
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function voucherUsages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
