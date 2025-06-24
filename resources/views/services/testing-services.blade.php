{{-- resources/views/services/testing-services.blade.php --}}
@extends('layouts.app')

@section('title', 'Layanan Pengujian - Laboratorium Fisika Dasar')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Gradient -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.jpg') }}"
             alt="Layanan Pengujian"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-700/90 via-blue-800/80 to-blue-900/70"></div>
    </div>

    <!-- Content -->
    <div class="relative z-20 mx-6 px-4 sm:px-6 lg:px-8 text-center max-w-6xl">
        <!-- Breadcrumb -->
        <div class="scroll-animate mb-8 opacity-0" data-animation="fade-down">
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-blue-200 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-blue-300 mx-3"></i>
                            <span class="text-blue-200">Layanan</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-blue-300 mx-3"></i>
                            <span class="text-white font-medium">Layanan Pengujian</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Title -->
        <div class="scroll-animate mb-8 opacity-0" data-animation="fade-up" data-delay="200">
            <h1 class="font-poppins text-5xl md:text-7xl font-bold leading-tight mb-6">
                <span class="text-white">Layanan</span>
                <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent drop-shadow-lg"> Pengujian</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                Solusi lengkap analisis dan pengujian material dengan teknologi terdepan dan standar internasional
            </p>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
@if(session('success'))
<section class="py-8 bg-green-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="bg-green-100 border border-green-200 rounded-2xl p-6 flex items-start space-x-4">
            <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div>
                <h3 class="text-green-800 font-semibold text-lg mb-2">Berhasil!</h3>
                <div class="text-green-700 whitespace-pre-line">{{ session('success') }}</div>
            </div>
        </div>
    </div>
</section>
@endif

@if(session('error') || $errors->any())
<section class="py-8 bg-red-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="bg-red-100 border border-red-200 rounded-2xl p-6 flex items-start space-x-4">
            <div class="w-8 h-8 bg-red-200 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-red-800 font-semibold text-lg mb-2">Terjadi Kesalahan!</h3>
                @if(session('error'))
                    <div class="text-red-700 whitespace-pre-line">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <ul class="text-red-700 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Testing Services Section -->
<section class="py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16 scroll-animate" data-animation="fade-down">
            <div class="inline-flex items-center px-6 py-3 bg-blue-50 border border-blue-200 rounded-full text-blue-700 text-sm font-semibold mb-6 shadow-sm">
                <i class="fas fa-vial mr-2"></i>
                Katalog Pengujian
            </div>
            <h2 class="font-poppins text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Analisis <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Profesional</span>
            </h2>
            <p class="text-gray-600 text-lg md:text-xl max-w-4xl mx-auto leading-relaxed">
                Dapatkan hasil analisis yang akurat dan terpercaya dengan peralatan canggih dan tim ahli berpengalaman.
            </p>
        </div>

        <!-- How to Submit Testing Request -->
        <div class="mb-16 scroll-animate" data-animation="fade-up" data-delay="100">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-blue-800 mb-3 flex items-center justify-center">
                        <i class="fas fa-flask mr-3"></i>
                        Cara Mengajukan Pengujian Laboratorium
                    </h3>
                    <p class="text-blue-700">Ikuti langkah-langkah mudah berikut untuk mengajukan pengujian material</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-blue-300">
                            <span class="text-xl font-bold text-blue-600">1</span>
                        </div>
                        <h4 class="font-semibold text-blue-800 mb-2">Pilih Pengujian</h4>
                        <p class="text-sm text-blue-700">Centang kotak pada jenis pengujian yang dibutuhkan</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-blue-300">
                            <span class="text-xl font-bold text-blue-600">2</span>
                        </div>
                        <h4 class="font-semibold text-blue-800 mb-2">Tentukan Sampel</h4>
                        <p class="text-sm text-blue-700">Atur jumlah sampel untuk setiap pengujian</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-blue-300">
                            <span class="text-xl font-bold text-blue-600">3</span>
                        </div>
                        <h4 class="font-semibold text-blue-800 mb-2">Isi Form</h4>
                        <p class="text-sm text-blue-700">Klik "Ajukan Pengujian" dan lengkapi formulir</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-blue-300">
                            <span class="text-xl font-bold text-blue-600">4</span>
                        </div>
                        <h4 class="font-semibold text-blue-800 mb-2">Konfirmasi Admin</h4>
                        <p class="text-sm text-blue-700">Tim lab akan konfirmasi jadwal dalam 1-2 hari</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-blue-300">
                            <span class="text-xl font-bold text-blue-600">5</span>
                        </div>
                        <h4 class="font-semibold text-blue-800 mb-2">Serahkan Sampel</h4>
                        <p class="text-sm text-blue-700">Bawa sampel sesuai jadwal yang disepakati</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-100 rounded-xl border border-blue-300">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-lightbulb text-blue-600 mt-1"></i>
                        <div class="text-sm text-blue-700">
                            <strong>Tips:</strong> Siapkan sampel sesuai standar pengujian, surat resmi dari institusi, dan pastikan deskripsi sampel lengkap untuk hasil analisis optimal.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Controls -->
        <div class="mb-12 scroll-animate" data-animation="fade-up" data-delay="100">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="select-all-btn"
                                class="px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-xl hover:bg-blue-100 transition-colors duration-200 flex items-center">
                            <i class="fas fa-check-square mr-2"></i>
                            Pilih Semua
                        </button>
                        <button id="clear-selection-btn"
                                class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Hapus Pilihan
                        </button>
                        <div class="text-sm text-gray-500">
                            <span id="selection-count">0</span> jenis pengujian, <span id="total-quantity">0</span> sampel dipilih
                        </div>
                    </div>
                    <button id="bulk-testing-btn"
                            class="px-6 py-3 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors duration-200 flex items-center disabled:bg-gray-300 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-flask mr-2"></i>
                        Ajukan Pengujian (<span id="cart-count">0</span> sampel)
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-12 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row gap-6 items-center">
                    <!-- Search -->
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Cari Pengujian</label>
                        <div class="relative">
                            <input type="text"
                                   id="search-input"
                                   placeholder="Masukkan nama pengujian..."
                                   class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                        <select id="status-filter" class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="unavailable">Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testing Services Grid -->
        <div id="testing-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testingServices as $index => $service)
            <div class="testing-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 scroll-animate"
                 data-animation="fade-up"
                 data-delay="{{ $index * 100 }}"
                 data-status="{{ $service->is_available ? 'available' : 'unavailable' }}"
                 data-name="{{ strtolower($service->nama_pengujian) }}"
                 data-testing-id="{{ $service->id }}">

                <!-- Selection Checkbox & Quantity -->
                <div class="absolute top-4 left-4 z-10">
                    @if($service->is_available)
                    <div class="flex flex-col space-y-2">
                        <label class="testing-checkbox-container">
                            <input type="checkbox"
                                   class="testing-checkbox"
                                   data-testing-id="{{ $service->id }}"
                                   onchange="toggleTestingSelection('{{ $service->id }}')">
                            <span class="checkmark bg-white/90 backdrop-blur-sm"></span>
                        </label>
                        <div class="quantity-selector hidden bg-white/90 backdrop-blur-sm rounded-lg p-2 shadow-lg"
                             id="quantity-selector-{{ $service->id }}">
                            <label class="text-xs font-semibold text-gray-700 block mb-1">Sampel:</label>
                            <input type="number"
                                   class="quantity-input w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   min="1"
                                   max="10"
                                   value="1"
                                   data-testing-id="{{ $service->id }}"
                                   onchange="updateTestingQuantity('{{ $service->id }}', this.value)">
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Status Badge -->
                    <div class="mb-4">
                        @if($service->is_available)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            Tersedia
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Tidak Tersedia
                        </span>
                        @endif
                    </div>

                    <!-- Icon and Duration -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                            <i class="{{ $service->icon }} text-white text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-600">Estimasi</div>
                            <div class="text-sm font-bold text-blue-600">{{ $service->durasi }}</div>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">{{ $service->nama_pengujian }}</h3>
                        <div class="text-lg font-semibold text-blue-600">
                            Rp {{ number_format($service->harga_per_sampel, 0, ',', '.') }}/sampel
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                        {{ $service->deskripsi }}
                    </p>

                    <!-- Applications Preview -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Aplikasi Utama</h4>
                        <div class="space-y-1">
                            @foreach(array_slice($service->aplikasi ?? [], 0, 3) as $app)
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-check-circle text-blue-500 mr-2 flex-shrink-0"></i>
                                <span>{{ $app }}</span>
                            </div>
                            @endforeach
                            @if(count($service->aplikasi ?? []) > 3)
                            <div class="text-xs text-blue-600 font-medium">
                                +{{ count($service->aplikasi) - 3 }} aplikasi lainnya
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('testing.detail', $service->id) }}"
                           class="w-full bg-blue-500 text-white px-4 py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>

                        @if($service->is_available)
                        <button onclick="quickAddToSelection('{{ $service->id }}')"
                                class="w-full border-2 border-blue-500 text-blue-500 px-4 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah ke Pilihan
                        </button>
                        @else
                        <button disabled
                                class="w-full border-2 border-gray-300 text-gray-400 px-4 py-3 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            Tidak Tersedia
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="hidden text-center py-16">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada pengujian ditemukan</h3>
                <p class="text-gray-600">Coba ubah filter atau kata kunci pencarian Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- Floating Cart Button (Mobile) -->
<div id="floating-cart" class="fixed bottom-6 right-6 z-40 lg:hidden">
    <button onclick="openBulkTestingModal()"
            class="bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 transition-all duration-200 transform hover:scale-105 disabled:bg-gray-400"
            disabled>
        <i class="fas fa-flask text-xl"></i>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-semibold" id="floating-cart-count">0</span>
    </button>
</div>

<!-- Bulk Testing Request Modal -->
<div id="bulkTestingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeBulkTestingModal()"></div>

        <!-- Modal content -->
        <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Ajukan Permintaan Pengujian</h3>
                <button onclick="closeBulkTestingModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Selected Testing List -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    Pengujian yang Dipilih (<span id="modal-testing-count">0</span> jenis, <span id="modal-total-quantity">0</span> sampel)
                </h4>
                <div id="selected-testing-list" class="space-y-4 max-h-60 overflow-y-auto">
                    <!-- Testing items will be populated by JavaScript -->
                </div>

                <!-- Summary -->
                <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold text-blue-900">Total Jenis Pengujian:</span>
                            <span class="text-blue-700" id="summary-testing-types">0</span>
                        </div>
                        <div>
                            <span class="font-semibold text-blue-900">Total Sampel:</span>
                            <span class="text-blue-700" id="summary-total-samples">0</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-blue-200">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-blue-900">Estimasi Total Biaya:</span>
                            <span class="text-xl font-bold text-blue-800" id="summary-total-price">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="bulkTestingForm" action="{{ route('testing.request') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_penguji" required value="{{ old('nama_penguji') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Institusi/Organisasi *</label>
                        <input type="text" name="organisasi_penguji" required value="{{ old('organisasi_penguji') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email_penguji" required value="{{ old('email_penguji') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon *</label>
                        <input type="tel" name="no_hp_penguji" required value="{{ old('no_hp_penguji') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Diharapkan *</label>
                        <input type="date" name="tanggal_diharapkan" required value="{{ old('tanggal_diharapkan') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Sampel *</label>
                    <textarea name="deskripsi_sampel" rows="3" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan jenis sampel, asal, dan karakteristik yang diketahui...">{{ old('deskripsi_sampel') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kebutuhan Pengujian *</label>
                    <textarea name="deskripsi" rows="4" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan parameter yang ingin dianalisis dan tujuan pengujian...">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Hidden fields for selected testing data -->
                <input type="hidden" name="selected_tests" id="testing-ids-input">
                <input type="hidden" name="jumlah_sampel" id="testing-quantities-input">

                <!-- Admin Notice -->
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                        <div class="text-sm text-blue-700">
                            <strong>Informasi Penting:</strong> Admin akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi jadwal pengujian dan biaya final setelah evaluasi sampel.
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start space-x-3">
                    <input type="checkbox" id="bulk-terms" required class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="bulk-terms" class="text-sm text-gray-600">
                        Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> layanan pengujian laboratorium.
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex space-x-4">
                    <button type="button" onclick="closeBulkTestingModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" id="submit-btn"
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors duration-200">
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Line clamp for text truncation */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom Checkbox Styles */
.testing-checkbox-container {
    position: relative;
    display: block;
    cursor: pointer;
    user-select: none;
}

.testing-checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 28px;
    width: 28px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.testing-checkbox-container:hover input ~ .checkmark {
    border-color: #3b82f6;
    transform: scale(1.05);
}

.testing-checkbox-container input:checked ~ .checkmark {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.testing-checkbox-container input:checked ~ .checkmark:after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: white;
    font-size: 14px;
}

/* Quantity Selector Styles */
.quantity-selector {
    min-width: 80px;
    animation: slideDown 0.3s ease-out;
}

.quantity-selector.hidden {
    display: none;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.quantity-input, .modal-quantity-input {
    text-align: center;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button,
.modal-quantity-input::-webkit-outer-spin-button,
.modal-quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Selected card highlight */
.testing-card.selected {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-4px);
}

/* Scroll animations */
.scroll-animate {
    opacity: 0;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.scroll-animate[data-animation="fade-down"] {
    transform: translateY(-60px);
}

.scroll-animate[data-animation="fade-up"] {
    transform: translateY(60px);
}

.scroll-animate.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Enhanced gradient text */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Modal animations */
#bulkTestingModal.show {
    display: flex !important;
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .scroll-animate[data-animation="fade-down"],
    .scroll-animate[data-animation="fade-up"] {
        transform: translateY(40px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Testing services data for JavaScript
    const testingServices = @json($testingServices);

    // Selected testing services array with quantities
    window.selectedTesting = {};

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize filters
    initFilters();

    // Initialize selection controls
    initSelectionControls();

    // Set minimum date for date inputs
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.querySelector('input[name="tanggal_diharapkan"]');

    if (dateInput) {
        dateInput.setAttribute('min', today);
    }

    // Keep form values if there were validation errors
    @if($errors->any() || session('error'))
        const oldSelectedTests = @json(old('selected_tests', []));
        const oldJumlahSampel = @json(old('jumlah_sampel', []));

        // Restore selected checkboxes
        if (Array.isArray(oldSelectedTests)) {
            oldSelectedTests.forEach(testId => {
                const checkbox = document.querySelector(`input[data-testing-id="${testId}"]`);
                if (checkbox) {
                    checkbox.checked = true;

                    // Find the testing service data
                    const service = testingServices.find(s => s.id === testId);
                    if (service) {
                        window.selectedTesting[testId] = {
                            ...service,
                            quantity: oldJumlahSampel[testId] || 1
                        };

                        // Show quantity selector
                        const card = checkbox.closest('.testing-card');
                        const quantitySelector = document.getElementById(`quantity-selector-${testId}`);
                        const quantityInput = card.querySelector('.quantity-input');

                        card.classList.add('selected');
                        quantitySelector.classList.remove('hidden');

                        if (quantityInput && oldJumlahSampel[testId]) {
                            quantityInput.value = oldJumlahSampel[testId];
                        }
                    }
                }
            });

            // Update selection display
            updateSelectionUI();
        }
    @endif
});

function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.scroll-animate');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => {
                    entry.target.classList.add('animate');
                }, delay);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

function initFilters() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');

    // Search filter
    searchInput.addEventListener('input', filterTesting);

    // Status filter
    statusFilter.addEventListener('change', filterTesting);
}

function initSelectionControls() {
    // Select all button
    document.getElementById('select-all-btn').addEventListener('click', function() {
        const availableTesting = document.querySelectorAll('.testing-checkbox:not(:disabled)');
        availableTesting.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.checked = true;
                toggleTestingSelection(checkbox.dataset.testingId);
            }
        });
    });

    // Clear selection button
    document.getElementById('clear-selection-btn').addEventListener('click', function() {
        const selectedCheckboxes = document.querySelectorAll('.testing-checkbox:checked');
        selectedCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
            toggleTestingSelection(checkbox.dataset.testingId);
        });
        window.selectedTesting = {};
        updateSelectionUI();
    });

    // Bulk testing button
    document.getElementById('bulk-testing-btn').addEventListener('click', openBulkTestingModal);
}

function filterTesting() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value;

    const testingCards = document.querySelectorAll('.testing-card');
    let visibleCount = 0;

    testingCards.forEach(card => {
        const name = card.dataset.name;
        const status = card.dataset.status;

        const searchMatch = name.includes(searchTerm);
        const statusMatch = statusFilter === 'all' || status === statusFilter;

        if (searchMatch && statusMatch) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Show/hide no results message
    const noResults = document.getElementById('no-results');
    if (visibleCount === 0) {
        noResults.classList.remove('hidden');
    } else {
        noResults.classList.add('hidden');
    }
}

function toggleTestingSelection(testingId) {
    const testingServices = @json($testingServices);
    const service = testingServices.find(s => s.id === testingId);
    const card = document.querySelector(`[data-testing-id="${testingId}"]`);
    const checkbox = document.querySelector(`.testing-checkbox[data-testing-id="${testingId}"]`);
    const quantitySelector = document.getElementById(`quantity-selector-${testingId}`);

    if (checkbox.checked) {
        // Add to selection with default quantity of 1
        window.selectedTesting[testingId] = {
            ...service,
            quantity: 1
        };
        card.classList.add('selected');
        quantitySelector.classList.remove('hidden');
    } else {
        // Remove from selection
        delete window.selectedTesting[testingId];
        card.classList.remove('selected');
        quantitySelector.classList.add('hidden');
        // Reset quantity input
        const quantityInput = document.querySelector(`.quantity-input[data-testing-id="${testingId}"]`);
        if (quantityInput) {
            quantityInput.value = 1;
        }
    }

    updateSelectionUI();
}

function updateTestingQuantity(testingId, quantity) {
    if (window.selectedTesting[testingId]) {
        const qty = parseInt(quantity);

        // Validate quantity
        if (qty < 1) {
            const quantityInput = document.querySelector(`.quantity-input[data-testing-id="${testingId}"]`);
            quantityInput.value = 1;
            window.selectedTesting[testingId].quantity = 1;
        } else if (qty > 10) {
            alert('Maksimal 10 sampel per jenis pengujian');
            const quantityInput = document.querySelector(`.quantity-input[data-testing-id="${testingId}"]`);
            quantityInput.value = 10;
            window.selectedTesting[testingId].quantity = 10;
        } else {
            window.selectedTesting[testingId].quantity = qty;
        }

        updateSelectionUI();
    }
}

function quickAddToSelection(testingId) {
    const checkbox = document.querySelector(`.testing-checkbox[data-testing-id="${testingId}"]`);
    if (checkbox && !checkbox.checked) {
        checkbox.checked = true;
        toggleTestingSelection(testingId);
    }
}

function updateSelectionUI() {
    const selectedTestingArray = Object.values(window.selectedTesting);
    const count = selectedTestingArray.length;
    const totalQuantity = selectedTestingArray.reduce((sum, service) => sum + service.quantity, 0);
    const totalPrice = selectedTestingArray.reduce((sum, service) => sum + (service.harga_per_sampel * service.quantity), 0);

    // Update selection count
    document.getElementById('selection-count').textContent = count;
    document.getElementById('total-quantity').textContent = totalQuantity;
    document.getElementById('cart-count').textContent = totalQuantity;

    const floatingCartCount = document.getElementById('floating-cart-count');
    if (floatingCartCount) {
        floatingCartCount.textContent = totalQuantity;
    }

    // Enable/disable bulk testing button
    const bulkTestingBtn = document.getElementById('bulk-testing-btn');
    const floatingCart = document.querySelector('#floating-cart button');

    if (count > 0) {
        bulkTestingBtn.disabled = false;
        if (floatingCart) floatingCart.disabled = false;
        bulkTestingBtn.classList.remove('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
        if (floatingCart) floatingCart.classList.remove('disabled:bg-gray-400');
    } else {
        bulkTestingBtn.disabled = true;
        if (floatingCart) floatingCart.disabled = true;
        bulkTestingBtn.classList.add('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
        if (floatingCart) floatingCart.classList.add('disabled:bg-gray-400');
    }
}

function openBulkTestingModal() {
    const selectedTestingArray = Object.values(window.selectedTesting);
    if (selectedTestingArray.length === 0) {
        alert('Pilih pengujian terlebih dahulu sebelum mengajukan permintaan.');
        return;
    }

    // Populate selected testing list
    const testingList = document.getElementById('selected-testing-list');
    const testingCount = document.getElementById('modal-testing-count');
    const totalQuantity = document.getElementById('modal-total-quantity');
    const summaryTypes = document.getElementById('summary-testing-types');
    const summarySamples = document.getElementById('summary-total-samples');
    const summaryPrice = document.getElementById('summary-total-price');

    const totalSamples = selectedTestingArray.reduce((sum, service) => sum + service.quantity, 0);
    const totalPrice = selectedTestingArray.reduce((sum, service) => sum + (service.harga_per_sampel * service.quantity), 0);

    testingCount.textContent = selectedTestingArray.length;
    totalQuantity.textContent = totalSamples;
    summaryTypes.textContent = selectedTestingArray.length;
    summarySamples.textContent = totalSamples;
    summaryPrice.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');

    testingList.innerHTML = selectedTestingArray.map(service => `
        <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                    <i class="${service.icon || 'fas fa-flask'} text-white"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900">${service.nama_pengujian}</div>
                    <div class="text-sm text-gray-600">Estimasi: ${service.durasi}</div>
                    <div class="text-sm text-blue-600">Rp ${service.harga_per_sampel.toLocaleString('id-ID')}/sampel</div>
                </div>
                <div class="text-right">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Sampel:</label>
                        <input type="number"
                               class="modal-quantity-input w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                               min="1"
                               max="10"
                               value="${service.quantity}"
                               data-testing-id="${service.id}"
                               onchange="updateModalQuantity('${service.id}', this.value)">
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Subtotal: Rp ${(service.harga_per_sampel * service.quantity).toLocaleString('id-ID')}</div>
                </div>
            </div>
            <button onclick="removeFromSelection('${service.id}')"
                    class="text-red-500 hover:text-red-700 p-2 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');

    // Set testing data in hidden inputs
    document.getElementById('testing-ids-input').value = JSON.stringify(selectedTestingArray.map(service => service.id));
    document.getElementById('testing-quantities-input').value = JSON.stringify(
        selectedTestingArray.reduce((obj, service) => {
            obj[service.id] = service.quantity;
            return obj;
        }, {})
    );

    // Show modal
    document.getElementById('bulkTestingModal').classList.remove('hidden');
    document.getElementById('bulkTestingModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeBulkTestingModal() {
    document.getElementById('bulkTestingModal').classList.add('hidden');
    document.getElementById('bulkTestingModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function updateModalQuantity(testingId, quantity) {
    if (window.selectedTesting[testingId]) {
        const qty = parseInt(quantity);

        // Validate quantity
        if (qty < 1) {
            const modalInput = document.querySelector(`.modal-quantity-input[data-testing-id="${testingId}"]`);
            modalInput.value = 1;
            window.selectedTesting[testingId].quantity = 1;
        } else if (qty > 10) {
            alert('Maksimal 10 sampel per jenis pengujian');
            const modalInput = document.querySelector(`.modal-quantity-input[data-testing-id="${testingId}"]`);
            modalInput.value = 10;
            window.selectedTesting[testingId].quantity = 10;
        } else {
            window.selectedTesting[testingId].quantity = qty;
        }

        // Update quantity selector on main page
        const quantityInput = document.querySelector(`.quantity-input[data-testing-id="${testingId}"]`);
        if (quantityInput) {
            quantityInput.value = window.selectedTesting[testingId].quantity;
        }

        updateSelectionUI();

        // Update modal summary
        const selectedTestingArray = Object.values(window.selectedTesting);
        const totalSamples = selectedTestingArray.reduce((sum, service) => sum + service.quantity, 0);
        const totalPrice = selectedTestingArray.reduce((sum, service) => sum + (service.harga_per_sampel * service.quantity), 0);

        document.getElementById('modal-total-quantity').textContent = totalSamples;
        document.getElementById('summary-total-samples').textContent = totalSamples;
        document.getElementById('summary-total-price').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');

        // Update hidden inputs
        document.getElementById('testing-quantities-input').value = JSON.stringify(
            selectedTestingArray.reduce((obj, service) => {
                obj[service.id] = service.quantity;
                return obj;
            }, {})
        );

        // Update subtotal in the list
        const serviceCard = document.querySelector(`[data-testing-id="${testingId}"]`).closest('.bg-gray-50');
        const subtotalElement = serviceCard.querySelector('.text-xs.text-gray-500');
        const service = window.selectedTesting[testingId];
        subtotalElement.textContent = `Subtotal: Rp ${(service.harga_per_sampel * service.quantity).toLocaleString('id-ID')}`;
    }
}

function removeFromSelection(testingId) {
    const checkbox = document.querySelector(`.testing-checkbox[data-testing-id="${testingId}"]`);
    if (checkbox) {
        checkbox.checked = false;
        toggleTestingSelection(testingId);
    }

    // Refresh modal content if modal is open
    if (!document.getElementById('bulkTestingModal').classList.contains('hidden')) {
        openBulkTestingModal();
    }
}

// Handle form submission
document.getElementById('bulkTestingForm').addEventListener('submit', function(e) {
    const selectedTestingArray = Object.values(window.selectedTesting);
    if (selectedTestingArray.length === 0) {
        e.preventDefault();
        alert('Pilih pengujian terlebih dahulu.');
        return;
    }

    // Disable submit button to prevent double submission
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Mengirim Permintaan...';
});
</script>
@endsection
