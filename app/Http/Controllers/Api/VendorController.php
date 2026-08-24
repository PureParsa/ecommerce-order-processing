<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateVendorRequest;
use App\Http\Requests\Vendor\VendorCashOutRequest;
use App\Http\Resources\VendorCashoutResource;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use App\Services\CashOutService;
use App\Services\VendorService;

class VendorController extends Controller
{
    public function __construct(
        private VendorService $vendorService,
        private CashOutService $cashOutService
    ) {}

    public function create(CreateVendorRequest $request)
    {
        $this->authorize('create', Vendor::class);

        $vendor = $this->vendorService->createVendorAccount(
            auth()->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Vendor account created successfully',
            'vendor' => new VendorResource($vendor)
        ], 201);
    }
    public function cashout(VendorCashOutRequest $request)
    {
        $this->authorize('cashout', Vendor::class);

        $cashOut = $this->cashOutService->cashOut($request->validated() , auth()->user());
        return new VendorCashOutResource($cashOut);
    }

}
