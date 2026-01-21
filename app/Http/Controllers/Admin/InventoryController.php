<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\InventoryTransaction;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Danh sách tồn kho
     */
    public function index(Request $request)
    {
        $query = Comic::with(['category', 'publisher']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($request->stock_status === 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
            } elseif ($request->stock_status === 'in_stock') {
                $query->where('stock', '>', 10);
            }
        }

        // Sort
        $sort = $request->get('sort', 'stock_asc');
        switch ($sort) {
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'stock_asc':
            default:
                $query->orderBy('stock', 'asc');
                break;
        }

        $comics = $query->paginate(20);

        return view('admin.inventory.index', compact('comics'));
    }

    /**
     * Form nhập hàng
     */
    public function importForm($id)
    {
        $comic = Comic::findOrFail($id);
        return view('admin.inventory.import', compact('comic'));
    }

    /**
     * Xử lý nhập hàng
     */
    public function import(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $comic = Comic::findOrFail($id);

        try {
            $this->inventoryService->addStock(
                $id,
                $request->quantity,
                auth()->id(),
                $request->notes ?? 'Nhập hàng'
            );

            return redirect()->route('admin.inventory.index')
                ->with('success', "Đã nhập {$request->quantity} quyển {$comic->title} vào kho.");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Form điều chỉnh tồn kho
     */
    public function adjustForm($id)
    {
        $comic = Comic::findOrFail($id);
        return view('admin.inventory.adjust', compact('comic'));
    }

    /**
     * Xử lý điều chỉnh tồn kho
     */
    public function adjust(Request $request, $id)
    {
        $request->validate([
            'quantity_change' => 'required|integer',
            'reason' => 'required|string|max:500',
        ]);

        $comic = Comic::findOrFail($id);

        try {
            $this->inventoryService->adjustStock(
                $id,
                $request->quantity_change,
                auth()->id(),
                $request->reason
            );

            $action = $request->quantity_change > 0 ? 'tăng' : 'giảm';
            return redirect()->route('admin.inventory.index')
                ->with('success', "Đã {$action} tồn kho {$comic->title}.");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Lịch sử giao dịch tồn kho
     */
    public function history(Request $request, $id = null)
    {
        $query = InventoryTransaction::with(['comic', 'user', 'order']);

        if ($id) {
            $query->where('comic_id', $id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(50);

        $comic = $id ? Comic::findOrFail($id) : null;

        return view('admin.inventory.history', compact('transactions', 'comic'));
    }
}
