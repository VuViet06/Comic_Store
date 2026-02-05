<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $query = Category::withCount('comics');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
            ;
        }

        $categories = $query->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.categories.create');
    }


    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã tạo danh mục thành công.');
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }


    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã cập nhật danh mục thành công.');
    }

   
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
