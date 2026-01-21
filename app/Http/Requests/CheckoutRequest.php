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
            'shipping_ward' => ['nullable', 'string', 'max:100'],
            'shipping_province' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cod,bank_transfer,momo,vnpay'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'voucher_id' => ['nullable', 'integer', 'exists:vouchers,id'],
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
