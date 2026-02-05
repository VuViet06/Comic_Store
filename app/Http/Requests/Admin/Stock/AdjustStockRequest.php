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
            'quantity_change' => 'required|integer',
            'reason' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity_change.required' => 'Vui lòng nhập số lượng thay đổi.',
            'reason.required' => 'Vui lòng nhập lý do điều chỉnh.',
        ];
    }
}
