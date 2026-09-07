<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\InventoryTransaction;
use App\Services\InventoryService;
use App\Http\Requests\Admin\Comic\StoreComicRequest;
use App\Http\Requests\Admin\Comic\UpdateComicRequest;
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

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

        $comics = $query->paginate(6);
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('admin.comics.index', compact('comics', 'categories', 'publishers'));
    }

    public function create()
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        return view('admin.comics.create', compact('categories', 'publishers'));
    }

    public function store(StoreComicRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

        //image upload
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();
            $validated['cover'] = 'data:' . $mimeType . ';base64,' . $imageData;
        }

        $validated['is_active'] = $request->has('is_active');


        // dd($request->all());

        $comic = Comic::create($validated);

        if ($comic->stock > 0) {
            InventoryTransaction::create([
                'comic_id' => $comic->id,
                'type' => 'import',
                'quantity_change' => $comic->stock,
                'user_id' => auth()->id(),
                'note' => 'Nhập hàng ban đầu',
            ]);
        }

        return redirect()->route('admin.comics.index')
            ->with('success', 'Đã tạo truyện thành công.');
    }


    public function show($id)
    {
        $comic = Comic::with(['category', 'publisher', 'inventoryTransactions.user'])
            ->findOrFail($id);

        $inventoryHistory = $this->inventoryService->getHistory($id, 20);

        return view('admin.comics.show', compact('comic', 'inventoryHistory'));
    }


    public function edit($id)
    {
        $comic = Comic::findOrFail($id);
        $categories = Category::all();
        $publishers = Publisher::all();
        return view('admin.comics.edit', compact('comic', 'categories', 'publishers'));
    }


    public function update(UpdateComicRequest $request, $id)
    {
        $comic = Comic::findOrFail($id);
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        //  image upload Base64
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();
            $validated['cover'] = 'data:' . $mimeType . ';base64,' . $imageData;
        }

        $validated['is_active'] = $request->has('is_active');


        $oldStock = $comic->stock;
        $newStock = $validated['stock'];

        // Loại bỏ stock khỏi validated vì sẽ được xử lý riêng qua InventoryService
        unset($validated['stock']);

        // Cập nhật các thông tin khác trước
        $comic->update($validated);

        // Xử lý thay đổi stock riêng
        if ($oldStock != $newStock) {
            $stockDiff = $newStock - $oldStock;
            if ($stockDiff > 0) {
                $this->inventoryService->addStock($comic->id, $stockDiff, auth()->id(), 'Cập nhật tồn kho');
            } else {
                $this->inventoryService->adjustStock($comic->id, $stockDiff, auth()->id(), 'Điều chỉnh tồn kho');
            }
        }

        return redirect()->route('admin.comics.index')
            ->with('success', 'Đã cập nhật truyện thành công.');
    }

    /**
     * Xóa truyện
     */
    public function destroy($id)
    {
        $comic = Comic::findOrFail($id);

        if ($comic->orderItems()->exists()) {
            return redirect()->route('admin.comics.index')
                ->with('error', 'Không thể xóa truyện đã có đơn hàng. Vui lòng vô hiệu hóa thay vì xóa.');
        }


        $comic->delete();

        return redirect()->route('admin.comics.index')
            ->with('success', 'Đã xóa truyện thành công.');
    }
}
