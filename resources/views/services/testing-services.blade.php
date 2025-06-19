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
                Estimasi biaya dan jadwal akan dikonfirmasi oleh admin setelah evaluasi sampel.
            </p>
        </div>

        <!-- Filter Section -->
        <div class="mb-12 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row gap-6 items-center">
                    <!-- Category Filter -->
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Filter Kategori</label>
                        <div class="flex flex-wrap gap-2" id="category-filter">
                            @foreach($categories as $key => $category)
                            <button class="category-btn px-4 py-2 rounded-full border transition-all duration-300
                                {{ $key === 'all' ? 'bg-blue-500 text-white border-blue-500' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600' }}"
                                data-category="{{ $key }}">
                                {{ $category }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="flex-1 lg:max-w-md">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Cari Pengujian</label>
                        <div class="relative">
                            <input type="text"
                                   id="search-input"
                                   placeholder="Masukkan nama pengujian..."
                                   class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Availability Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ketersediaan</label>
                        <select id="availability-filter" class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Semua</option>
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
                 data-category="{{ $service['category'] }}"
                 data-available="{{ $service['available'] ? 'available' : 'unavailable' }}"
                 data-name="{{ strtolower($service['name']) }}">

                <!-- Image -->
                <div class="relative overflow-hidden h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                    <img src="{{ asset('images/testing/' . $service['image']) }}"
                         alt="{{ $service['name'] }}"
                         class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700">

                    <!-- Availability Badge -->
                    <div class="absolute top-4 left-4">
                        @if($service['available'])
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

                    <!-- Category Icon -->
                    <div class="absolute top-4 right-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="{{ $service['icon'] }} text-white text-sm"></i>
                        </div>
                    </div>

                    <!-- Duration Info -->
                    <div class="absolute bottom-4 right-4">
                        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-lg">
                            <div class="text-xs text-gray-600">Estimasi</div>
                            <div class="text-sm font-bold text-blue-600">
                                {{ $service['duration'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-4">
                        <div class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold mb-3 border border-blue-200">
                            {{ $service['category'] }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">{{ $service['name'] }}</h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-clock text-blue-500 mr-1"></i>
                                {{ $service['duration'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                        {{ $service['description'] }}
                    </p>

                    <!-- Applications Preview -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Aplikasi Utama</h4>
                        <div class="space-y-1">
                            @foreach(array_slice($service['applications'], 0, 3) as $app)
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-check-circle text-blue-500 mr-2 flex-shrink-0"></i>
                                <span>{{ $app }}</span>
                            </div>
                            @endforeach
                            @if(count($service['applications']) > 3)
                            <div class="text-xs text-blue-600 font-medium">
                                +{{ count($service['applications']) - 3 }} aplikasi lainnya
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Price Info Notice -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="text-xs text-gray-600 text-center">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Estimasi biaya akan dikonfirmasi admin
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <button onclick="showTestingDetail({{ $service['id'] }})"
                                class="w-full bg-blue-500 text-white px-4 py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </button>

                        @if($service['available'])
                        <button onclick="openTestingModal({{ $service['id'] }})"
                                class="w-full border-2 border-blue-500 text-blue-500 px-4 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-flask mr-2"></i>
                            Ajukan Pengujian
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

<!-- Testing Request Modal -->
<div id="testingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeTestingModal()"></div>

        <!-- Modal content -->
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Ajukan Permintaan Pengujian</h3>
                <button onclick="closeTestingModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="testingForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal/Organization Info -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Institusi/Organisasi *</label>
                        <input type="text" name="organization" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon *</label>
                        <input type="tel" name="phone" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Diharapkan *</label>
                        <input type="date" name="expected_date" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Sampel *</label>
                    <textarea name="sample_description" rows="3" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan jenis sampel, asal, dan karakteristik yang diketahui..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kebutuhan Pengujian *</label>
                    <textarea name="test_requirements" rows="4" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan parameter yang ingin dianalisis dan tujuan pengujian..."></textarea>
                </div>

                <!-- Testing Service Info -->
                <div id="testing-info" class="bg-gray-50 rounded-xl p-4">
                    <!-- Testing service details will be populated by JavaScript -->
                </div>

                <!-- Admin Notice -->
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                        <div class="text-sm text-blue-700">
                            <strong>Informasi Penting:</strong> Admin akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi jadwal pengujian dan estimasi biaya berdasarkan sampel yang akan dianalisis.
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start space-x-3">
                    <input type="checkbox" id="terms" required class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="terms" class="text-sm text-gray-600">
                        Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> layanan pengujian laboratorium.
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex space-x-4">
                    <button type="button" onclick="closeTestingModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
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
#testingModal.show {
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

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize filters
    initFilters();

    // Set minimum date for date input
    const today = new Date().toISOString().split('T')[0];
    const expectedDateInput = document.querySelector('input[name="expected_date"]');
    if (expectedDateInput) {
        expectedDateInput.setAttribute('min', today);
    }
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
    const categoryBtns = document.querySelectorAll('.category-btn');
    const searchInput = document.getElementById('search-input');
    const availabilityFilter = document.getElementById('availability-filter');

    // Category filter
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            categoryBtns.forEach(b => {
                b.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
                b.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-200');
            });
            this.classList.remove('bg-gray-50', 'text-gray-700', 'border-gray-200');
            this.classList.add('bg-blue-500', 'text-white', 'border-blue-500');

            filterTestingServices();
        });
    });

    // Search filter
    if (searchInput) {
        searchInput.addEventListener('input', filterTestingServices);
    }

    // Availability filter
    if (availabilityFilter) {
        availabilityFilter.addEventListener('change', filterTestingServices);
    }
}

function filterTestingServices() {
    const activeCategory = document.querySelector('.category-btn.bg-blue-500').dataset.category;
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const availabilityFilter = document.getElementById('availability-filter').value;

    const testingCards = document.querySelectorAll('.testing-card');
    let visibleCount = 0;

    testingCards.forEach(card => {
        const category = card.dataset.category;
        const name = card.dataset.name;
        const available = card.dataset.available;

        const categoryMatch = activeCategory === 'all' || category === activeCategory;
        const searchMatch = name.includes(searchTerm);
        const availabilityMatch = availabilityFilter === 'all' || available === availabilityFilter;

        if (categoryMatch && searchMatch && availabilityMatch) {
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

function showTestingDetail(serviceId) {
    window.location.href = `/services/testing/${serviceId}`;
}

function openTestingModal(serviceId) {
    const service = @json($testingServices).find(s => s.id === serviceId);
    if (!service) return;

    // Populate testing service info in modal
    const testingInfo = document.getElementById('testing-info');
    testingInfo.innerHTML = `
        <h4 class="font-semibold text-gray-900 mb-3">Layanan Pengujian</h4>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                <i class="${service.icon} text-white"></i>
            </div>
            <div>
                <div class="font-semibold text-gray-900">${service.name}</div>
                <div class="text-sm text-gray-600">${service.category}</div>
                <div class="text-sm text-blue-600">Estimasi: ${service.duration}</div>
            </div>
        </div>
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h5 class="font-semibold text-yellow-800 mb-2">Persyaratan Sampel:</h5>
            <ul class="text-sm text-yellow-700 space-y-1">
                ${service.sample_requirements.map(req => `<li>• ${req}</li>`).join('')}
            </ul>
        </div>
    `;

    // Show modal
    document.getElementById('testingModal').classList.remove('hidden');
    document.getElementById('testingModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeTestingModal() {
    document.getElementById('testingModal').classList.add('hidden');
    document.getElementById('testingModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Handle form submission
document.getElementById('testingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Permintaan pengujian berhasil dikirim! Admin akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi jadwal dan estimasi biaya.');
    closeTestingModal();
});
</script>
@endsection
