<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    /**
     * Danh sách địa chỉ của user
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('profile.addresses.index', compact('addresses'));
    }

    /**
     * Form tạo địa chỉ mới
     */
    public function create()
    {
        return view('profile.addresses.create');
    }

    /**
     * Lưu địa chỉ mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:255',
            'ward' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Nếu là địa chỉ đầu tiên hoặc đánh dấu mặc định
        if ($request->is_default || $user->addresses()->count() === 0) {
            // Bỏ mặc định của các địa chỉ khác
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $validated['user_id'] = $user->id;
        UserAddress::create($validated);

        return redirect()->route('addresses.index')
            ->with('success', 'Đã thêm địa chỉ mới thành công.');
    }

    /**
     * Form chỉnh sửa địa chỉ
     */
    public function edit($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        return view('profile.addresses.edit', compact('address'));
    }

    /**
     * Cập nhật địa chỉ
     */
    public function update(Request $request, $id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);

        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:255',
            'ward' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $address->update($validated);

        return redirect()->route('addresses.index')
            ->with('success', 'Đã cập nhật địa chỉ thành công.');
    }

    /**
     * Xóa địa chỉ
     */
    public function destroy($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        
        $wasDefault = $address->is_default;
        $address->delete();

        // Nếu xóa địa chỉ mặc định, đặt địa chỉ đầu tiên làm mặc định
        if ($wasDefault) {
            $firstAddress = Auth::user()->addresses()->first();
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Đã xóa địa chỉ thành công.');
    }

    /**
     * Đặt địa chỉ làm mặc định
     */
    public function setDefault($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->setAsDefault();

        return redirect()->route('addresses.index')
            ->with('success', 'Đã đặt địa chỉ mặc định thành công.');
    }

    /**
     * API: Lấy danh sách địa chỉ (cho AJAX)
     */
    public function getAddresses()
    {
        $addresses = Auth::user()->addresses()
            ->orderBy('is_default', 'desc')
            ->get();

        return response()->json($addresses);
    }
}
