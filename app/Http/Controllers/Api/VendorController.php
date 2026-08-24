<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateVendorRequest;
use App\Models\User;
use App\Models\Vendor;
use App\Services\VendorService;

class VendorController extends Controller
{
    public function __construct(private VendorService $vendorService) {}

    public function create(CreateVendorRequest $request)
    {
        $this->authorize('create', Vendor::class);

        $vendor = $this->vendorService->createVendorAccount(
            auth()->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Vendor account created successfully',
            'vendor_id' => $vendor->id
        ], 201);
    }
}
