@extends('layouts.admin')

@section('title', 'Lịch sử tồn kho')
@section('page-title', isset($comic) ? 'Lịch sử tồn kho: ' . $comic->title : 'Lịch sử tồn kho')

@section('content')
<div class="space-y-6">
    <div class="flex justify-start">
        <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian</th>
                        @if(!isset($comic))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Truyện</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số lượng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người thực hiện</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ghi chú</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            @if(!isset($comic))
                                <td class="px-6 py-4 text-sm">{{ $transaction->comic->title ?? 'N/A' }}</td>
                            @endif
                            <td class="px-6 py-4">
                                @switch($transaction->type)
                                    @case('import')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Nhập hàng</span>
                                        @break
                                    @case('sale')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Bán</span>
                                        @break
                                    @case('adjust')
                                    @case('adjustment')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Điều chỉnh</span>
                                        @break
                                    @case('return')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Hoàn trả</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $transaction->type }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm font-medium {{ $transaction->quantity_change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->quantity_change > 0 ? '+' : '' }}{{ $transaction->quantity_change }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->user->name ?? 'Hệ thống' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ isset($comic) ? 5 : 6 }}" class="px-6 py-12 text-center text-gray-500">Chưa có lịch sử tồn kho</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
