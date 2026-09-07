<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Http\Requests\Admin\Voucher\StoreVoucherRequest;
use App\Http\Requests\Admin\Voucher\UpdateVoucherRequest;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    public function index(Request $request)
    {
        $query = Voucher::query();

        $status = $request->get('status', 'all');

        switch ($status) {
            case 'hidden':
                $query->where('is_active', false);
                break;
            case 'out_of_limit':
                $query->where('is_active', true)
                      ->whereNotNull('usage_limit')
                      ->whereColumn('used_count', '>=', 'usage_limit');
                break;
            case 'expired':
                $query->where('is_active', true)
                      ->whereNotNull('ends_at')
                      ->where('ends_at', '<', now());
                break;
            case 'upcoming':
                $query->where('is_active', true)
                      ->whereNotNull('starts_at')
                      ->where('starts_at', '>', now());
                break;
            case 'active':
                $query->where('is_active', true)
                      ->where(function($q) {
                          $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                      })
                      ->where(function($q) {
                          $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                      })
                      ->where(function($q) {
                          $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
                      });
                break;
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderBy('created_at', 'desc')->paginate(6);

        return view('admin.vouchers.index', compact('vouchers', 'status'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }


    public function store(StoreVoucherRequest $request)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');
        $validated['used_count'] = 0;

        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã tạo mã giảm giá thành công.');
    }


    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }


    public function update(UpdateVoucherRequest $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã cập nhật mã giảm giá thành công.');
    }

    
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

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        return redirect()->back()
            ->with('success', 'Đã thay đổi trạng thái mã giảm giá thành công.');
    }
}
