@extends('layouts.app')

@section('title', 'Chỉnh sửa địa chỉ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800">
                ← Quay lại Profile
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Chỉnh sửa địa chỉ</h1>

            <form action="{{ route('addresses.update', $address->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('profile.addresses._form', ['address' => $address])

                <div class="flex gap-4 mt-6">
                    <button type="submit" class="btn-primary">
                        Cập nhật
                    </button>
                    <a href="{{ route('profile.edit') }}" class="btn-secondary">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
