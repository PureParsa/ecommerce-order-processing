<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class ProductPolicy
{
    public function create(User $user): bool
    {
        if ($user->vendor_id === null) {
            throw new AuthorizationException('Only vendors can create products');
        }
        return true;
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->vendor_id !== $product->vendor_id) {
            throw new AuthorizationException('You can only update your own products');
        }
        return true;
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->vendor_id !== $product->vendor_id) {
            throw new AuthorizationException('You can only delete your own products');
        }
        return true;
    }
}
