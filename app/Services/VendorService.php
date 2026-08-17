<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vendor;
use Exception;

class VendorService
{
    public function createVendorAccount(User $user, array $data): Vendor
    {

        $vendor = Vendor::create($data);
        $user->update(['vendor_id' => $vendor->id]);
        return $vendor;
    }

    public function isVendor(User $user): bool
    {
        return $user->vendor_id !== null;
    }
}
