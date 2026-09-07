<?php

namespace App\Http\Requests\Admin\Publisher;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublisherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('publisher');
        
        return [
            'name' => 'required|string|max:255|unique:publishers,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:publishers,slug,' . $id,
            'country' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhà xuất bản.',
            'name.unique' => 'Nhà xuất bản này đã tồn tại.',
        ];
    }
}
