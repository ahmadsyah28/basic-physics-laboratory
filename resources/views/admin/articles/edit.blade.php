{{-- resources/views/admin/articles/edit.blade.php --}}

@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.articles.index') }}"
           class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Artikel</h1>
            <p class="text-gray-600 mt-1">{{ $article->nama_acara }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Nama Acara -->
                <div>
                    <label for="nama_acara" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Artikel <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_acara"
                           name="nama_acara"
                           value="{{ old('nama_acara', $article->nama_acara) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_acara') border-red-500 @enderror">
                    @error('nama_acara')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea id="deskripsi"
                              name="deskripsi"
                              rows="12"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $article->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Saat Ini -->
                @if($article->gambar->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($article->gambar as $gambar)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $gambar->url) }}"
                                        alt="Gambar artikel"
                                        class="w-full h-24 object-cover rounded-lg">
                                    <button type="button"
                                            onclick="deleteImage('{{ $gambar->id }}')"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upload Gambar Baru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Gambar Baru</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input type="file"
                               id="images"
                               name="images[]"
                               multiple
                               accept="image/*"
                               class="hidden"
                               onchange="previewImages()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Klik untuk upload gambar atau drag & drop</p>
                        <p class="text-sm text-gray-500">PNG, JPG, GIF hingga 2MB (multiple files)</p>
                        <button type="button"
                                onclick="document.getElementById('images').click()"
                                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Pilih Gambar
                        </button>
                    </div>
                    <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4"></div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Penulis -->
                <div>
                    <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">
                        Penulis <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="penulis"
                           name="penulis"
                           value="{{ old('penulis', $article->penulis) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('penulis') border-red-500 @enderror">
                    @error('penulis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Acara -->
                <div>
                    <label for="tanggal_acara" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Acara <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local"
                           id="tanggal_acara"
                           name="tanggal_acara"
                           value="{{ old('tanggal_acara', $article->tanggal_acara->format('Y-m-d\TH:i')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_acara') border-red-500 @enderror">
                    @error('tanggal_acara')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="border-t pt-6">
                    <div class="space-y-3">
                        <button type="submit"
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Artikel
                        </button>
                        <a href="{{ route('admin.articles.index') }}"
                           class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-200 text-center block">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function previewImages() {
    const input = document.getElementById('images');
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-24 object-cover rounded-lg">
                    <button type="button" onclick="removeImage(${index})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeImage(index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();

    Array.from(input.files).forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });

    input.files = dt.files;
    previewImages();
}

function deleteImage(imageId) {
    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        fetch(`/admin/articles/image/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus gambar');
        });
    }
}
</script>
@endsection
