<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comic_id' => ['required', 'integer', 'exists:comics,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'comic_id.required' => 'Mã truyện không được để trống',
            'quantity.required' => 'Vui lòng nhập số lượng',
            'quantity.min' => 'Số lượng tối thiểu là 1',
        ];
    }
}
