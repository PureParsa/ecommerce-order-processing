<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required',
            'items.min' => 'Order must contain at least one item',
            'items.*.product_id.required' => 'Product ID is required for each item',
            'items.*.product_id.exists' => 'One or more products do not exist',
            'items.*.quantity.required' => 'Quantity is required for each item',
            'items.*.quantity.min' => 'Quantity must be at least 1',
        ];
    }
}
