<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingPartner;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Danh sách đối tác vận chuyển
     */
    public function index()
    {
        $partners = ShippingPartner::withCount('shipments')->orderBy('name')->get();
        return view('admin.shipping.index', compact('partners'));
    }

    /**
     * Form tạo mới đối tác
     */
    public function create()
    {
        return view('admin.shipping.create');
    }

    /**
     * Lưu đối tác mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_partners,code',
            'api_base_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ShippingPartner::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Đã tạo đối tác vận chuyển thành công.');
    }

    /**
     * Form chỉnh sửa đối tác
     */
    public function edit($id)
    {
        $partner = ShippingPartner::findOrFail($id);
        return view('admin.shipping.edit', compact('partner'));
    }

    /**
     * Cập nhật đối tác
     */
    public function update(Request $request, $id)
    {
        $partner = ShippingPartner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_partners,code,' . $id,
            'api_base_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $partner->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Đã cập nhật đối tác vận chuyển thành công.');
    }

    /**
     * Xóa đối tác
     */
    public function destroy($id)
    {
        $partner = ShippingPartner::findOrFail($id);

        if ($partner->shipments()->exists()) {
            return redirect()->route('admin.shipping.index')
                ->with('error', 'Không thể xóa đối tác đang có đơn vận chuyển.');
        }

        $partner->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Đã xóa đối tác vận chuyển thành công.');
    }

    /**
     * Danh sách đơn vận chuyển
     */
    public function shipments(Request $request)
    {
        $query = Shipment::with(['order', 'shippingPartner']);

        // Filter
        if ($request->filled('partner_id')) {
            $query->where('shipping_partner_id', $request->partner_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($q) use ($search) {
                      $q->where('code', 'like', "%{$search}%");
                  });
            });
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(20);
        $partners = ShippingPartner::all();

        return view('admin.shipping.shipments', compact('shipments', 'partners'));
    }
}
