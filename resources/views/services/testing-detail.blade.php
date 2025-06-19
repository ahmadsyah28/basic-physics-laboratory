{{-- resources/views/services/testing-detail.blade.php --}}
@extends('layouts.app')

@section('title', $testingService['name'] . ' - Detail Layanan Pengujian')

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
                            <span class="text-gray-900 font-medium">{{ $testingService['name'] }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Service Image -->
            <div class="scroll-animate" data-animation="slide-left">
                <div class="relative">
                    <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/testing/' . $testingService['image']) }}"
                             alt="{{ $testingService['name'] }}"
                             class="w-full h-full object-cover">
                    </div>

                    <!-- Availability Badge -->
                    <div class="absolute top-6 left-6">
                        @if($testingService['available'])
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

                    <!-- Category Icon -->
                    <div class="absolute top-6 right-6">
                        <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="{{ $testingService['icon'] }} text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div class="scroll-animate" data-animation="slide-right" data-delay="200">
                <!-- Header -->
                <div class="mb-8">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold mb-4 border border-blue-200">
                        {{ $testingService['category'] }}
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $testingService['name'] }}</h1>
                    <p class="text-gray-700 leading-relaxed">{{ $testingService['description'] }}</p>
                </div>

                <!-- Service Info -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-8 border border-blue-200">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Estimasi Waktu</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $testingService['duration'] }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Rentang Biaya</div>
                            <div class="text-lg font-bold text-blue-700">{{ $testingService['price_range'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    @if($testingService['available'])
                    <button onclick="openTestingModal({{ $testingService['id'] }})"
                            class="flex-1 bg-blue-500 text-white px-8 py-4 rounded-2xl font-semibold hover:bg-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <i class="fas fa-flask mr-3"></i>
                        Ajukan Pengujian
                    </button>
                    @else
                    <button disabled
                            class="flex-1 bg-gray-300 text-gray-500 px-8 py-4 rounded-2xl font-semibold cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-times-circle mr-3"></i>
                        Tidak Tersedia
                    </button>
                    @endif

                    <button onclick="window.history.back()"
                            class="border-2 border-blue-500 text-blue-500 px-8 py-4 rounded-2xl font-semibold hover:bg-blue-50 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Kembali
                    </button>
                </div>
            </div>
        </div>

        <!-- Detailed Information Tabs -->
        <div class="mt-16 scroll-animate" data-animation="fade-up" data-delay="400">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm"
                            data-tab="applications">
                        Aplikasi
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm"
                            data-tab="requirements">
                        Persyaratan Sampel
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm"
                            data-tab="process">
                        Proses Pengujian
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="mt-8">
                <!-- Applications Tab -->
                <div id="applications-tab" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Aplikasi dan Kegunaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($testingService['applications'] as $application)
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-blue-600 text-xs"></i>
                                </div>
                                <span class="text-gray-700 leading-relaxed">{{ $application }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sample Requirements Tab -->
                <div id="requirements-tab" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Persyaratan Sampel</h3>
                        <div class="space-y-4">
                            @foreach($testingService['sample_requirements'] as $requirement)
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-exclamation text-yellow-600 text-xs"></i>
                                </div>
                                <span class="text-gray-700 leading-relaxed">{{ $requirement }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-3">Informasi Tambahan</h4>
                            <ul class="text-sm text-blue-700 space-y-2">
                                <li>• Konsultasi awal gratis untuk memastikan kesesuaian sampel</li>
                                <li>• Sampel akan disimpan selama 30 hari setelah pengujian</li>
                                <li>• Laporan hasil dalam format PDF dan Excel</li>
                                <li>• Sertifikat pengujian tersedia atas permintaan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Testing Process Tab -->
                <div id="process-tab" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Alur Proses Pengujian</h3>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Konsultasi & Registrasi</h4>
                                    <p class="text-gray-600">Diskusi kebutuhan pengujian dan registrasi sampel</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Penyerahan Sampel</h4>
                                    <p class="text-gray-600">Penyerahan sampel dengan form yang telah diisi</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Persiapan & Analisis</h4>
                                    <p class="text-gray-600">Preparasi sampel dan pelaksanaan pengujian</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">4</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Laporan Hasil</h4>
                                    <p class="text-gray-600">Penyerahan laporan hasil pengujian dan interpretasi</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-6 bg-green-50 rounded-xl border border-green-200">
                            <h4 class="font-semibold text-green-900 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>Jaminan Kualitas
                            </h4>
                            <p class="text-sm text-green-700">
                                Semua pengujian dilakukan dengan standar ISO 17025 dan menggunakan peralatan yang terkalibrasi.
                                Tim analis berpengalaman dengan sertifikasi internasional memastikan hasil yang akurat dan terpercaya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testing Request Modal (reuse from testing-services.blade.php) -->
<!-- Include the same modal code here -->

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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Testing service data for JavaScript
    window.testingServiceData = [@json($testingService)];

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

function openTestingModal(serviceId) {
    // Implementation similar to testing-services.blade.php
    alert('Modal pengujian akan dibuka - implementasi sama dengan halaman utama');
}
</script>
@endsection
