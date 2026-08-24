<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VendorCashOutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:100|max:10000',
        ];
    }
    public function messages(): array
    {
        return [
            'amount.required' => 'Withdrawal amount required',
            'amount.min' => 'Minimum withdrawal is $100',
            'amount.max' => 'Maximum withdrawal is $10,000',
        ];
    }
}
