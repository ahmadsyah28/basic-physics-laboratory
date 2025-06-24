{{-- resources/views/services/equipment-detail.blade.php --}}
@extends('layouts.app')

@section('title', $equipment['name'] . ' - Detail Alat Laboratorium')

@section('content')
<!-- Equipment Detail Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-gray-50 to-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-8 scroll-animate" data-animation="fade-down">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <a href="{{ route('equipment.loan') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Peminjaman Alat</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <span class="text-gray-900 font-medium">{{ $equipment['name'] }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Equipment Image -->
            <div class="scroll-animate" data-animation="slide-left">
                <div class="relative">
                    <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/equipment/' . ($equipment['image'] ?? 'default.jpg')) }}"
                             alt="{{ $equipment['name'] }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='{{ asset('images/equipment/default.jpg') }}'">
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute top-6 left-6">
                        @if($equipment['status'] === 'available')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200 shadow-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            Tersedia
                        </span>
                        @elseif($equipment['status'] === 'maintenance')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200 shadow-lg">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Maintenance
                        </span>
                        @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-lg">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                            Habis
                        </span>
                        @endif
                    </div>

                    <!-- Category Icon -->
                    <div class="absolute top-6 right-6">
                        <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="{{ $equipment['icon'] ?? 'fas fa-cog' }} text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Details -->
            <div class="scroll-animate" data-animation="slide-right" data-delay="200">
                <!-- Header -->
                <div class="mb-8">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold mb-4 border border-blue-200">
                        {{ $equipment['category'] }}
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $equipment['name'] }}</h1>
                    <p class="text-xl text-gray-600 font-medium mb-2">{{ $equipment['model'] ?? 'Model tidak tersedia' }}</p>
                    <p class="text-gray-700 leading-relaxed">{{ $equipment['description'] }}</p>
                </div>

                <!-- Availability Info -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-8 border border-blue-200">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Ketersediaan</div>
                            <div class="text-2xl font-bold text-blue-700">
                                {{ $equipment['quantity_available'] }}/{{ $equipment['quantity_total'] }} Unit
                            </div>
                            @if($equipment['quantity_available'] == 0)
                                <div class="text-xs text-red-600 mt-1">Stok habis</div>
                            @elseif($equipment['quantity_available'] <= 2)
                                <div class="text-xs text-yellow-600 mt-1">Stok terbatas</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Durasi Peminjaman</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $equipment['loan_duration'] ?? 'Fleksibel' }}</div>
                        </div>
                    </div>
                </div>

                <!-- How to Borrow Info -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-8 border border-blue-200">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        Kembali untuk Meminjam Alat
                    </h3>
                    <div class="text-blue-700 text-sm">
                        <p class="mb-3">Untuk meminjam alat ini, silakan kembali ke halaman utama peminjaman alat dan ikuti panduan yang tersedia.</p>
                        <p class="text-xs text-blue-600">Pastikan Anda sudah menyiapkan kartu mahasiswa dan surat dari dosen pembimbing sebelum mengajukan peminjaman.</p>
                    </div>
                </div>


                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('equipment.loan') }}"
                       class="flex-1 bg-blue-500 text-white px-8 py-4 rounded-2xl font-semibold hover:bg-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <i class="fas fa-hand-holding mr-3"></i>
                        Ajukan Peminjaman Sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- Detailed Information Tabs -->
        <div class="mt-16 scroll-animate" data-animation="fade-up" data-delay="400">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm"
                            data-tab="specifications">
                        Spesifikasi
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm"
                            data-tab="manual">
                        Panduan Penggunaan
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="mt-8">
                <!-- Specifications Tab -->
                <div id="specifications-tab" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Spesifikasi Teknis</h3>
                        @if(!empty($equipment['specifications']) && count($equipment['specifications']) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($equipment['specifications'] as $spec)
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fas fa-check text-blue-600 text-xs"></i>
                                    </div>
                                    <span class="text-gray-700 leading-relaxed">{{ $spec }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-cog text-gray-400 text-xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Spesifikasi Tidak Tersedia</h4>
                                <p class="text-gray-600">Spesifikasi teknis akan dijelaskan saat briefing penggunaan alat.</p>
                            </div>
                        @endif
                    </div>
                </div>



                <!-- Manual Tab -->
                <div id="manual-tab" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Panduan Penggunaan</h3>
                        <div class="prose max-w-none">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-play-circle text-blue-500 mr-2"></i>
                                        Persiapan Alat
                                    </h4>
                                    <ol class="space-y-2 text-gray-700">
                                        <li>1. Periksa kelengkapan alat dan aksesoris</li>
                                        <li>2. Pastikan kondisi alat dalam keadaan baik</li>
                                        <li>3. Lakukan kalibrasi jika diperlukan</li>
                                        <li>4. Siapkan area kerja yang bersih dan aman</li>
                                        <li>5. Baca manual penggunaan dengan teliti</li>
                                    </ol>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                                        Keamanan
                                    </h4>
                                    <ol class="space-y-2 text-gray-700">
                                        <li>1. Gunakan APD yang sesuai</li>
                                        <li>2. Jangan operasikan tanpa pengawasan</li>
                                        <li>3. Matikan alat setelah penggunaan</li>
                                        <li>4. Laporkan kerusakan segera</li>
                                        <li>5. Ikuti prosedur darurat jika terjadi masalah</li>
                                    </ol>
                                </div>
                            </div>

                            <div class="mt-8 space-y-6">
                                <div class="p-6 bg-blue-50 rounded-xl border border-blue-200">
                                    <h4 class="font-semibold text-blue-900 mb-3">
                                        <i class="fas fa-info-circle mr-2"></i>Prosedur Standar
                                    </h4>
                                    <div class="text-sm text-blue-700 space-y-2">
                                        <p>• <strong>Sebelum Penggunaan:</strong> Lakukan pengecekan visual dan fungsional</p>
                                        <p>• <strong>Selama Penggunaan:</strong> Monitor kondisi alat secara berkala</p>
                                        <p>• <strong>Setelah Penggunaan:</strong> Bersihkan dan kembalikan ke posisi semula</p>
                                        <p>• <strong>Pelaporan:</strong> Laporkan segera jika terjadi kerusakan atau abnormalitas</p>
                                    </div>
                                </div>

                                <div class="p-6 bg-red-50 rounded-xl border border-red-200">
                                    <h4 class="font-semibold text-red-900 mb-3">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Peringatan Penting
                                    </h4>
                                    <p class="text-sm text-red-700">
                                        Alat ini memerlukan penanganan khusus. Pastikan Anda telah mendapat briefing dari staff laboratorium sebelum menggunakan.
                                        Penggunaan yang tidak sesuai prosedur dapat menyebabkan kerusakan alat dan membahayakan keselamatan.
                                        <strong class="block mt-2">Hubungi staff laboratorium jika ragu dalam pengoperasian.</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.tab-button.active {
    border-color: rgb(59 130 246);
    color: rgb(37 99 235);
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

.scroll-animate[data-animation="slide-left"] {
    transform: translateX(-100px);
}

.scroll-animate[data-animation="slide-right"] {
    transform: translateX(100px);
}

.scroll-animate.animate {
    opacity: 1;
    transform: translateY(0) translateX(0);
}

/* Tab content transitions */
.tab-content {
    transition: opacity 0.3s ease-in-out;
}

.tab-content.hidden {
    opacity: 0;
}

/* Enhanced gradient text */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Image aspect ratio */
.aspect-w-4 {
    position: relative;
    padding-bottom: 75%; /* 4:3 aspect ratio */
}

.aspect-w-4 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize scroll animations
    initScrollAnimations();

    // Initialize tabs
    initTabs();
});

function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            // Update button states
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('active', 'border-blue-500', 'text-blue-600');

            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            const targetContent = document.getElementById(targetTab + '-tab');
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
}

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
</script>
@endsection
