<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Category;
use App\Models\Publisher;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComicController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Danh sách truyện
     */
    public function index(Request $request)
    {
        $query = Comic::with(['category', 'publisher']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

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
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $comics = $query->paginate(20);
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('admin.comics.index', compact('comics', 'categories', 'publishers'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        return view('admin.comics.create', compact('categories', 'publishers'));
    }

    /**
     * Lưu truyện mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'edition_type' => 'required|string',
            'condition' => 'required|string',
            'series' => 'nullable|string|max:255',
            'volume' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Handle image upload
        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('comics', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $comic = Comic::create($validated);

        // Log inventory transaction nếu có stock
        if ($comic->stock > 0) {
            $this->inventoryService->addStock($comic->id, $comic->stock, auth()->id(), 'Nhập hàng ban đầu');
        }

        return redirect()->route('admin.comics.index')
            ->with('success', 'Đã tạo truyện thành công.');
    }

    /**
     * Chi tiết truyện
     */
    public function show($id)
    {
        $comic = Comic::with(['category', 'publisher', 'inventoryTransactions.user'])
            ->findOrFail($id);
        
        $inventoryHistory = $this->inventoryService->getHistory($id, 20);

        return view('admin.comics.show', compact('comic', 'inventoryHistory'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $comic = Comic::findOrFail($id);
        $categories = Category::all();
        $publishers = Publisher::all();
        return view('admin.comics.edit', compact('comic', 'categories', 'publishers'));
    }

    /**
     * Cập nhật truyện
     */
    public function update(Request $request, $id)
    {
        $comic = Comic::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'edition_type' => 'required|string',
            'condition' => 'required|string',
            'series' => 'nullable|string|max:255',
            'volume' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Update slug if title changed
        if ($comic->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('cover')) {
            // Delete old image
            if ($comic->cover) {
                Storage::disk('public')->delete($comic->cover);
            }
            $validated['cover'] = $request->file('cover')->store('comics', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        // Handle stock change
        $oldStock = $comic->stock;
        $newStock = $validated['stock'];
        if ($oldStock != $newStock) {
            $stockDiff = $newStock - $oldStock;
            if ($stockDiff > 0) {
                $this->inventoryService->addStock($comic->id, $stockDiff, auth()->id(), 'Cập nhật tồn kho');
            } else {
                $this->inventoryService->adjustStock($comic->id, $stockDiff, auth()->id(), 'Điều chỉnh tồn kho');
            }
        }

        $comic->update($validated);

        return redirect()->route('admin.comics.index')
            ->with('success', 'Đã cập nhật truyện thành công.');
    }

    /**
     * Xóa truyện
     */
    public function destroy($id)
    {
        $comic = Comic::findOrFail($id);

        // Check if comic has orders
        if ($comic->orderItems()->exists()) {
            return redirect()->route('admin.comics.index')
                ->with('error', 'Không thể xóa truyện đã có đơn hàng. Vui lòng vô hiệu hóa thay vì xóa.');
        }

        // Delete image
        if ($comic->cover) {
            Storage::disk('public')->delete($comic->cover);
        }

        $comic->delete();

        return redirect()->route('admin.comics.index')
            ->with('success', 'Đã xóa truyện thành công.');
    }
}
