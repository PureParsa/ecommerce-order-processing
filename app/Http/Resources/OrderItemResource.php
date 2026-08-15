<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'vendor_id' => $this->vendor_id,
            'quantity' => $this->quantity,
            'price' => (float) $this->price,
            'item_status' => $this->item_status,
            'created_at' => $this->created_at,
        ];
    }
}
