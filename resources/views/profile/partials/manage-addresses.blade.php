{{-- Quản lý địa chỉ giao hàng --}}
<section>
    <header class="mb-4">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Địa chỉ giao hàng') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Quản lý các địa chỉ giao hàng của bạn để checkout nhanh hơn.') }}
        </p>
    </header>

    {{-- Nút thêm địa chỉ mới --}}
    <div class="mb-4">
        <a href="{{ route('addresses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('Thêm địa chỉ mới') }}
        </a>
    </div>

    {{-- Danh sách địa chỉ --}}
    @php
        $addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->get();
    @endphp

    @if($addresses->count() > 0)
        <div class="space-y-4">
            @foreach($addresses as $address)
                <div class="border rounded-lg p-4 {{ $address->is_default ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @if($address->label)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $address->label }}
                                    </span>
                                @endif
                                @if($address->is_default)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Mặc định
                                    </span>
                                @endif
                            </div>
                            <p class="font-semibold text-gray-900">{{ $address->recipient_name }}</p>
                            <p class="text-gray-600">{{ $address->phone }}</p>
                            <p class="text-gray-600 mt-1">{{ $address->full_address }}</p>
                        </div>
                        <div class="flex gap-2 ml-4">
                            @if(!$address->is_default)
                                <form action="{{ route('addresses.set-default', $address->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Đặt mặc định
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('addresses.edit', $address->id) }}" class="text-gray-600 hover:text-gray-800 text-sm">
                                Sửa
                            </a>
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 bg-gray-50 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="mt-2 text-gray-600">Bạn chưa có địa chỉ giao hàng nào.</p>
            <p class="text-sm text-gray-500">Thêm địa chỉ để checkout nhanh hơn.</p>
        </div>
    @endif
</section>
