<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_USER,
            'phone' => ['nullable', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:20'],
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'role.required' => 'Vui lòng chọn vai trò.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 số.',
        ];
    }
}
