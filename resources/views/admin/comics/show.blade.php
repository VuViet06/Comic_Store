@extends('layouts.admin')

@section('title', 'Chi tiết truyện')
@section('page-title', $comic->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.comics.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại
        </a>
        
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex gap-6">
                    @if($comic->cover)
                        <img src="{{ $comic->cover }}" alt="{{ $comic->title }}" class="w-48 h-auto rounded-lg shadow">
                    @else
                        <div class="w-48 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="flex-1 space-y-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $comic->title }}</h2>
                            @if($comic->series)
                                <p class="text-gray-600">{{ $comic->series }} - Tập {{ $comic->volume }}</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Danh mục:</span>
                                <span class="font-medium">{{ $comic->category->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">NXB:</span>
                                <span class="font-medium">{{ $comic->publisher->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Năm XB:</span>
                                <span class="font-medium">{{ $comic->published_year ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Loại:</span>
                                <span class="font-medium">
                                    @switch($comic->edition_type)
                                        @case('regular') Thường @break
                                        @case('special') Đặc biệt @break
                                        @case('limited') Giới hạn @break
                                        @default {{ $comic->edition_type }}
                                    @endswitch
                                </span>
                            </div>

                            <div>
                                <span class="text-gray-500">Trạng thái:</span>
                                @if($comic->is_active)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Đang bán</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Ẩn</span>
                                @endif
                            </div>
                        </div>

                        @if($comic->description)
                            <div class="pt-4 border-t">
                                <h4 class="font-medium text-gray-900 mb-2">Mô tả</h4>
                                <p class="text-gray-600 text-sm">{{ $comic->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Giá & Tồn kho</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-gray-600">Giá bán</span>
                        <span class="text-2xl font-bold text-green-600">{{ number_format($comic->price) }} VNĐ</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Tồn kho</span>
                        <span class="text-2xl font-bold {{ $comic->stock == 0 ? 'text-red-600' : ($comic->stock <= 10 ? 'text-yellow-600' : 'text-gray-900') }}">
                            {{ $comic->stock }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Thao tác nhanh</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.inventory.import-form', $comic->id) }}" class="block w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg text-center hover:bg-green-200 transition-colors">
                        + Nhập thêm hàng
                    </a>
                    <a href="{{ route('admin.inventory.adjust-form', $comic->id) }}" class="block w-full px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-center hover:bg-yellow-200 transition-colors">
                        Điều chỉnh tồn kho
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Lịch sử tồn kho</h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số lượng</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người thực hiện</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ghi chú</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($inventoryHistory as $history)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $history->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                @switch($history->type)
                                    @case('import')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Nhập hàng</span>
                                        @break
                                    @case('sale')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Bán</span>
                                        @break
                                    @case('adjust')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Điều chỉnh</span>
                                        @break
                                    @case('return')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Hoàn trả</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $history->type }}</span>
                                @endswitch
                            </td>
                            <td class="px-4 py-3 text-sm font-medium {{ $history->quantity_change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $history->quantity_change > 0 ? '+' : '' }}{{ $history->quantity_change }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $history->user->name ?? 'Hệ thống' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $history->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Chưa có lịch sử tồn kho</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
