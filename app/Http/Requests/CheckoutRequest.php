<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^\+?[0-9\-\s()]{9,}$/'],
            'shipping_address_line' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cod,momo,vnpay'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập tên người nhận',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại',
            'shipping_address_line.required' => 'Vui lòng nhập địa chỉ giao hàng',
        ];
    }
}
