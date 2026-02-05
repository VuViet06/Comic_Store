<?php

namespace App\Http\Requests\Admin;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_status' => 'required|in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_SHIPPING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ]),
            'payment_status' => 'nullable|in:' . implode(',', [
                Order::PAYMENT_STATUS_UNPAID,
                Order::PAYMENT_STATUS_PENDING,
                Order::PAYMENT_STATUS_PAID,
                Order::PAYMENT_STATUS_FAILED,
                Order::PAYMENT_STATUS_REFUNDED,
            ]),
        ];
    }

    public function messages(): array
    {
        return [
            'order_status.required' => 'Vui lòng chọn trạng thái đơn hàng.',
        ];
    }
}
