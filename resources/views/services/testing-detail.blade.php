{{-- resources/views/services/testing-detail.blade.php --}}
@extends('layouts.app')

@section('title', $testingService->nama_pengujian . ' - Detail Layanan Pengujian')

@section('content')
<!-- Testing Service Detail Section -->
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
                            <a href="{{ route('testing.services') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Layanan Pengujian</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <span class="text-gray-900 font-medium">{{ $testingService->nama_pengujian }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Service Icon and Info -->
            <div class="scroll-animate" data-animation="slide-left">
                <div class="relative">
                    <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-32 h-32 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <i class="{{ $testingService->icon }} text-blue-500 text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-blue-800">{{ $testingService->nama_pengujian }}</h3>
                        </div>
                    </div>

                    <!-- Availability Badge -->
                    <div class="absolute top-6 left-6">
                        @if($testingService->is_available)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200 shadow-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            Tersedia
                        </span>
                        @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200 shadow-lg">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Tidak Tersedia
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div class="scroll-animate" data-animation="slide-right" data-delay="200">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $testingService->nama_pengujian }}</h1>
                    <p class="text-gray-700 leading-relaxed text-lg">{{ $testingService->deskripsi }}</p>
                </div>

                <!-- Service Info -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-8 border border-blue-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Estimasi Waktu</div>
                            <div class="text-xl font-bold text-blue-700 flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $testingService->durasi }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Harga per Sampel</div>
                            <div class="text-xl font-bold text-blue-700 flex items-center">
                                <i class="fas fa-tag mr-2"></i>
                                Rp {{ number_format($testingService->harga_per_sampel, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <div class="text-sm text-blue-600 font-semibold mb-2">Informasi Penting</div>
                        <div class="text-sm text-blue-700 flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Biaya final dapat berbeda setelah evaluasi sampel oleh tim laboratorium</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('testing.services') }}"
                       class="flex-1 bg-blue-500 text-white px-8 py-4 rounded-2xl font-semibold hover:bg-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Kembali & Ajukan Pengujian
                    </a>
                </div>

                <!-- Quick Info -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-lightbulb text-yellow-600 mt-1"></i>
                        <div class="text-sm text-yellow-700">
                            <strong>Tips:</strong> Untuk mengajukan pengujian ini, kembali ke halaman utama layanan pengujian dan pilih pengujian yang diinginkan.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="mt-16 scroll-animate" data-animation="fade-up" data-delay="400">
            <!-- Applications Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-list-check mr-3 text-blue-500"></i>
                    Aplikasi dan Kegunaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($testingService->aplikasi ?? [] as $application)
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-blue-600 text-xs"></i>
                        </div>
                        <span class="text-gray-700 leading-relaxed">{{ $application }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Technical Specifications -->
            <div class="mt-8 bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cogs mr-3 text-blue-500"></i>
                    Spesifikasi Teknis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm font-semibold text-gray-600 mb-1">Metode Analisis</div>
                        <div class="text-gray-900">{{ $testingService->nama_pengujian }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm font-semibold text-gray-600 mb-1">Waktu Pengujian</div>
                        <div class="text-gray-900">{{ $testingService->durasi }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm font-semibold text-gray-600 mb-1">Status Ketersediaan</div>
                        <div class="text-gray-900">
                            @if($testingService->is_available)
                                <span class="text-green-600 font-semibold">Tersedia</span>
                            @else
                                <span class="text-red-600 font-semibold">Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
    </div>
</section>

<style>
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .scroll-animate[data-animation="slide-left"],
    .scroll-animate[data-animation="slide-right"] {
        transform: translateY(40px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize scroll animations
    initScrollAnimations();
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
</script>
@endsection
