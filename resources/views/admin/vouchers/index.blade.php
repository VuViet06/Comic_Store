@extends('layouts.admin')

@section('title', 'Quản lý Mã giảm giá')
@section('page-title', 'Quản lý Mã giảm giá')

@section('content')
<div class="space-y-6">
    <div class="flex xl:flex-row flex-col justify-between items-start xl:items-center gap-4">
        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap border-b border-gray-200 w-full xl:w-auto">
            @php $currentStatus = $status ?? 'all'; @endphp
            <a href="{{ route('admin.vouchers.index', ['status' => 'all'] + request()->except('status', 'page')) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 {{ $currentStatus === 'all' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Tất cả
            </a>
            <a href="{{ route('admin.vouchers.index', ['status' => 'active'] + request()->except('status', 'page')) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 {{ $currentStatus === 'active' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Hoạt động
            </a>
            <a href="{{ route('admin.vouchers.index', ['status' => 'upcoming'] + request()->except('status', 'page')) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 {{ $currentStatus === 'upcoming' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Sắp diễn ra
            </a>
            <a href="{{ route('admin.vouchers.index', ['status' => 'expired'] + request()->except('status', 'page')) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 {{ $currentStatus === 'expired' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Hết hạn
            </a>
            <a href="{{ route('admin.vouchers.index', ['status' => 'out_of_limit'] + request()->except('status', 'page')) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 {{ $currentStatus === 'out_of_limit' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Hết lượt
            </a>
            <a href="{{ route('admin.vouchers.index', ['status' => 'hidden'] + request()->except('status', 'page')) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 {{ $currentStatus === 'hidden' ? 'border-gray-600 text-gray-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Tắt kích hoạt
            </a>
        </div>

        <a href="{{ route('admin.vouchers.create') }}" class="inline-flex shrink-0 items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm mã giảm giá
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mã</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Giá trị</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đơn tối thiểu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sử dụng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hạn sử dụng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($vouchers as $voucher)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-bold">{{ $voucher->code }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($voucher->type == 'percent')
                                    <span class="text-purple-600">Phần trăm</span>
                                @else
                                    <span class="text-green-600">Cố định</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                @if($voucher->type == 'percent')
                                    {{ $voucher->value }}%
                                @else
                                    {{ number_format($voucher->value) }} VNĐ
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ number_format($voucher->min_order_amount ?? 0) }} VNĐ</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $voucher->used_count ?? 0 }} / {{ $voucher->usage_limit ?? '∞' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($voucher->ends_at)
                                    {{ \Carbon\Carbon::parse($voucher->ends_at)->format('d/m/Y') }}
                                @else
                                    Không giới hạn
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.vouchers.toggle-status', $voucher->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="hover:opacity-80 transition-opacity focus:outline-none" title="Nhấn để Bật/Tắt mã giảm giá">
                                        @if(!$voucher->is_active)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Tắt kích hoạt</span>
                                        @elseif($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Hết lượt</span>
                                        @elseif($voucher->ends_at && now()->isAfter($voucher->ends_at))
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Hết hạn</span>
                                        @elseif($voucher->starts_at && now()->isBefore($voucher->starts_at))
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Sắp diễn ra</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Hoạt động</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="inline" onsubmit="return confirm('Xóa mã này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">Chưa có mã giảm giá nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
