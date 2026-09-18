<?php

namespace App\Policies;

use App\Models\CustomerAddress;
use App\Models\User;

class CustomerAddressPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('customer');
    }

    public function view(User $user, CustomerAddress $address): bool
    {
        return $user->hasRole('customer') && $user->id === $address->user_id;
    }

    public function update(User $user, CustomerAddress $address): bool
    {
        return $this->view($user, $address);
    }

    public function delete(User $user, CustomerAddress $address): bool
    {
        return $this->view($user, $address);
    }
}
