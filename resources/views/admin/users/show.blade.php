@extends('layouts.admin')

@section('title', 'Detail Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.users.index') }}"
               class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-600 hover:text-gray-900 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Admin</h1>
                <p class="text-gray-600">Informasi admin {{ $user->name }}</p>
            </div>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <button onclick="deleteUser({{ $user->id }})"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center">
                <i class="fas fa-trash mr-2"></i>
                Hapus
            </button>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Profile Header -->
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="text-gray-600">{{ $user->email }}</p>
            <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                <i class="fas fa-user-shield mr-1"></i>
                Admin
            </span>
        </div>

        <!-- Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-user text-gray-600 mr-2"></i>
                    <h3 class="font-medium text-gray-900">Nama Lengkap</h3>
                </div>
                <p class="text-gray-700">{{ $user->name }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-envelope text-gray-600 mr-2"></i>
                    <h3 class="font-medium text-gray-900">Email</h3>
                </div>
                <p class="text-gray-700">{{ $user->email }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-shield-alt text-gray-600 mr-2"></i>
                    <h3 class="font-medium text-gray-900">Role</h3>
                </div>
                <p class="text-gray-700">Admin</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-calendar-alt text-gray-600 mr-2"></i>
                    <h3 class="font-medium text-gray-900">Tanggal Bergabung</h3>
                </div>
                <p class="text-gray-700">
                    {{ $user->created_at->format('d F Y') }}
                    <span class="text-sm text-gray-500">({{ $user->created_at->diffForHumans() }})</span>
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-clock text-gray-600 mr-2"></i>
                    <h3 class="font-medium text-gray-900">Terakhir Update</h3>
                </div>
                <p class="text-gray-700">
                    {{ $user->updated_at->format('d F Y, H:i') }}
                    <span class="text-sm text-gray-500">({{ $user->updated_at->diffForHumans() }})</span>
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-check-circle text-gray-600 mr-2"></i>
                    <h3 class="font-medium text-gray-900">Status</h3>
                </div>
                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                    Aktif
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus admin <strong>{{ $user->name }}</strong>?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Batal
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteUser(userId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/admin/users/${userId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
