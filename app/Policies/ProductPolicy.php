<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // Admin bisa melakukan apa saja sebelum pengecekan lain
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true; // Semua role yang login bisa lihat (jika diperlukan)
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('products.create');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasPermission('products.update');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasPermission('products.delete');
    }
}
