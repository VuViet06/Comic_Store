<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublisherController extends Controller
{

    public function index(Request $request)
    {
        $query = Publisher::withCount('comics');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('country', 'like', '%' . $request->search . '%');
        }

        $publishers = $query->orderBy('id')->get();
        return view('admin.publishers.index', compact('publishers'));
    }


    public function create()
    {
        return view('admin.publishers.create');
    }


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



    public function edit($id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('admin.publishers.edit', compact('publisher'));
    }


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
