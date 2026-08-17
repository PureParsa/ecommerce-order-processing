<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        if ($user->id !== $order->user_id) {
            throw new AuthorizationException('You can only view your own orders');
        }
        return true;
    }

    public function delete(User $user, Order $order): bool
    {
        if ($user->id !== $order->user_id) {
            throw new AuthorizationException('You can only delete your own orders');
        }
        if ($order->status !== 'pending') {
            throw new AuthorizationException('Can only cancel pending orders');
        }
        return true;
    }
}
