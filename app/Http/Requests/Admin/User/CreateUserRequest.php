<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_USER,
            'phone' => ['nullable', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:20'],
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'role.required' => 'Vui lòng chọn vai trò.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 số.',
        ];
    }
}
