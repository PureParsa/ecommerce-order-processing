<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
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
            'business_name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'wallet_balance' => $this->wallet_balance,
            'is_active' => $this->is_active
        ];
    }
}
