<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublisherController extends Controller
{
    /**
     * Danh sách nhà xuất bản
     */
    public function index()
    {
        $publishers = Publisher::withCount('comics')->orderBy('name')->get();
        return view('admin.publishers.index', compact('publishers'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        return view('admin.publishers.create');
    }

    /**
     * Lưu nhà xuất bản mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
            'country' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Publisher::create($validated);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Đã tạo nhà xuất bản thành công.');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('admin.publishers.edit', compact('publisher'));
    }

    /**
     * Cập nhật nhà xuất bản
     */
    public function update(Request $request, $id)
    {
        $publisher = Publisher::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,' . $id,
            'country' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $publisher->update($validated);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Đã cập nhật nhà xuất bản thành công.');
    }

    /**
     * Xóa nhà xuất bản
     */
    public function destroy($id)
    {
        $publisher = Publisher::findOrFail($id);

        if ($publisher->comics()->exists()) {
            return redirect()->route('admin.publishers.index')
                ->with('error', 'Không thể xóa nhà xuất bản đang có truyện. Vui lòng xóa hoặc chuyển truyện trước.');
        }

        $publisher->delete();

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Đã xóa nhà xuất bản thành công.');
    }
}
