<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Danh sách voucher
     */
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Lưu voucher mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0|required_if:type,percent',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['used_count'] = 0;

        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã tạo mã giảm giá thành công.');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Cập nhật voucher
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0|required_if:type,percent',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã cập nhật mã giảm giá thành công.');
    }

    /**
     * Xóa voucher
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);

        if ($voucher->orders()->exists()) {
            return redirect()->route('admin.vouchers.index')
                ->with('error', 'Không thể xóa mã giảm giá đã được sử dụng.');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã xóa mã giảm giá thành công.');
    }
}
