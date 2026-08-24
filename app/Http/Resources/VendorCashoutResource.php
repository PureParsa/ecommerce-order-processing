<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorCashoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'reference_number' => $this->reference_number,
        ];
    }
}
