<?php

namespace App\Http\Requests\Admin\Voucher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('voucher');
        
        return [
            'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0' . ($this->type === 'percent' ? '|max:100' : ''),
            'max_discount' => 'nullable|numeric|min:0|required_if:type,percent',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'value.required' => 'Vui lòng nhập giá trị giảm.',
        ];
    }
}
