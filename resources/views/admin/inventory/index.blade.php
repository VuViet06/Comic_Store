@extends('layouts.admin')

@section('title', 'Quản lý Tồn kho')
@section('page-title', 'Quản lý Tồn kho')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('admin.inventory.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Lịch sử tồn kho
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Truyện</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tồn kho</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($comics as $comic)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($comic->cover)
                                        <img src="{{ $comic->cover }}" alt="{{ $comic->title }}" class="w-10 h-14 object-cover rounded mr-3">
                                    @endif
                                    <div>
                                        <p class="font-medium">{{ $comic->title }}</p>
                                        @if($comic->series)
                                            <p class="text-sm text-gray-500">{{ $comic->series }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $comic->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium">{{ number_format($comic->price) }} VNĐ</td>
                            <td class="px-6 py-4">
                                @if($comic->stock == 0)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Hết hàng (0)</span>
                                @elseif($comic->stock <= 10)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Sắp hết ({{ $comic->stock }})</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">{{ $comic->stock }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($comic->is_active)
                                    <span class="text-green-600">Đang bán</span>
                                @else
                                    <span class="text-gray-400">Ẩn</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.inventory.import-form', $comic->id) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                                        + Nhập
                                    </a>
                                    <a href="{{ route('admin.inventory.adjust-form', $comic->id) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
                                        Điều chỉnh
                                    </a>
                                    <a href="{{ route('admin.inventory.comic-history', $comic->id) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200">
                                        Lịch sử
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Chưa có truyện nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($comics->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $comics->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
