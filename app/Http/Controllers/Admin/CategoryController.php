<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Danh sách danh mục
     */
    public function index()
    {
        $categories = Category::withCount('comics')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Lưu danh mục mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã tạo danh mục thành công.');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã cập nhật danh mục thành công.');
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->comics()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục đang có truyện. Vui lòng xóa hoặc chuyển truyện trước.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã xóa danh mục thành công.');
    }
}
