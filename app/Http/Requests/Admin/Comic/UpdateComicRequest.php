<?php

namespace App\Http\Requests\Admin\Comic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'edition_type' => 'required|string|in:regular,special,limited,collectors',
            'condition' => 'required|string|in:new,like_new,good,fair,in_stock,coming_soon,out_of_stock,used,discontinued',
            'series' => 'nullable|string|max:255',
            'volume' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'publisher_id.required' => 'Vui lòng chọn nhà xuất bản.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'price.required' => 'Vui lòng nhập giá.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
        ];
    }
}
