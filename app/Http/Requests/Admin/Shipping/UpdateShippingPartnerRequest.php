<?php

namespace App\Http\Requests\Admin\Shipping;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('shipping');
        
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_partners,code,' . $id,
            'api_base_url' => 'nullable|url',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên đối tác.',
            'code.required' => 'Vui lòng nhập mã đối tác.',
            'code.unique' => 'Mã đối tác này đã tồn tại.',
        ];
    }
}
