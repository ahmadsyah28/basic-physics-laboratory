@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.users.index') }}"
           class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-600 hover:text-gray-900 shadow-md hover:shadow-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Admin</h1>
            <p class="text-gray-600">Perbarui informasi admin {{ $user->name }}</p>
        </div>
    </div>

    <!-- Current Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center mb-3">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <h3 class="font-semibold text-blue-800">Informasi Saat Ini</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-blue-600">Nama:</span>
                <span class="font-medium ml-2 text-blue-800">{{ $user->name }}</span>
            </div>
            <div>
                <span class="text-blue-600">Email:</span>
                <span class="font-medium ml-2 text-blue-800">{{ $user->email }}</span>
            </div>
            <div>
                <span class="text-blue-600">Role:</span>
                <span class="font-medium ml-2 text-blue-800">Admin</span>
            </div>
            <div>
                <span class="text-blue-600">Dibuat:</span>
                <span class="font-medium ml-2 text-blue-800">{{ $user->created_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $user->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Section -->
                <div class="border-t pt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password (Opsional)</h3>
                    <p class="text-sm text-gray-600 mb-4">Biarkan kosong jika tidak ingin mengubah password</p>

                    <div class="space-y-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru
                            </label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Minimal 8 karakter</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-gray-500 text-sm mt-1">Masukkan password yang sama</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
