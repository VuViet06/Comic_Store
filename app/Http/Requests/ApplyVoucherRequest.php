<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'voucher_code' => ['required', 'string', 'max:50'],
            'subtotal_amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'voucher_code.required' => 'Vui lòng nhập mã giảm giá',
            'subtotal_amount.required' => 'Thiếu giá trị đơn hàng',
        ];
    }
}
