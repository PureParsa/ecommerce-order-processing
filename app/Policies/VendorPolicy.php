<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    public function create(User $user): bool
    {
        return $user->vendor_id === null;
    }
    public function cashout(User $user): bool
    {
        return $user->vendor_id !== null;
    }
    public function message(): string
    {
        return 'You are already a vendor';
    }
}
