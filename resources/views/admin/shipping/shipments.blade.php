@extends('layouts.admin')

@section('title', 'Danh sách Vận đơn')
@section('page-title', 'Danh sách Vận đơn')

@section('content')
<div class="space-y-6">
    <div class="flex justify-start">
        <a href="{{ route('admin.shipping.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mã vận đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đơn hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đối tác</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày tạo</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($shipments as $shipment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-medium">{{ $shipment->tracking_code ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $shipment->order->code ?? '') }}" class="text-blue-600 hover:underline">
                                    {{ $shipment->order->code ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $shipment->shippingPartner->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @switch($shipment->status)
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Chờ lấy</span>
                                        @break
                                    @case('picked_up')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Đã lấy</span>
                                        @break
                                    @case('in_transit')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Đang giao</span>
                                        @break
                                    @case('delivered')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Đã giao</span>
                                        @break
                                    @case('failed')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Thất bại</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $shipment->status }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $shipment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $shipment->order->code ?? '') }}" class="text-blue-600 hover:text-blue-900">
                                    Xem đơn hàng
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Chưa có vận đơn nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($shipments->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
