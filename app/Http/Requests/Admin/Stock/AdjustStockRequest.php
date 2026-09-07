<?php

namespace App\Http\Requests\Admin\Stock;

use Illuminate\Foundation\Http\FormRequest;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity_change' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity_change.required' => 'Vui lòng nhập số lượng thay đổi.',
            'quantity_change.not_in' => 'Số lượng thay đổi không được bằng 0.',
            'reason.required' => 'Vui lòng nhập lý do điều chỉnh.',
        ];
    }
}
