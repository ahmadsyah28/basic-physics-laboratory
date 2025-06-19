{{-- resources/views/services/equipment-loan.blade.php --}}
@extends('layouts.app')

@section('title', 'Layanan Peminjaman Alat - Laboratorium Fisika Dasar')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Gradient -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.jpg') }}"
             alt="Layanan Peminjaman Alat"
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
                            <span class="text-white font-medium">Peminjaman Alat</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Title -->
        <div class="scroll-animate mb-8 opacity-0" data-animation="fade-up" data-delay="200">
            <h1 class="font-poppins text-5xl md:text-7xl font-bold leading-tight mb-6">
                <span class="text-white">Layanan</span>
                <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent drop-shadow-lg"> Peminjaman</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                Akses mudah ke peralatan laboratorium berkualitas tinggi untuk mendukung penelitian dan praktikum Anda
            </p>
        </div>

       
    </div>
</section>

<!-- Equipment Section -->
<section class="py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16 scroll-animate" data-animation="fade-down">
            <div class="inline-flex items-center px-6 py-3 bg-blue-50 border border-blue-200 rounded-full text-blue-700 text-sm font-semibold mb-6 shadow-sm">
                <i class="fas fa-tools mr-2"></i>
                Katalog Peralatan
            </div>
            <h2 class="font-poppins text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Peralatan <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Laboratorium</span>
            </h2>
            <p class="text-gray-600 text-lg md:text-xl max-w-4xl mx-auto leading-relaxed">
                Jelajahi koleksi lengkap peralatan laboratorium modern yang tersedia untuk mendukung kegiatan akademik dan penelitian Anda.
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
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Cari Alat</label>
                        <div class="relative">
                            <input type="text"
                                   id="search-input"
                                   placeholder="Masukkan nama alat..."
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
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Grid -->
        <div id="equipment-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($equipments as $index => $equipment)
            <div class="equipment-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 scroll-animate"
                 data-animation="fade-up"
                 data-delay="{{ $index * 100 }}"
                 data-category="{{ $equipment['category'] }}"
                 data-status="{{ $equipment['status'] }}"
                 data-name="{{ strtolower($equipment['name']) }}">

                <!-- Image -->
                <div class="relative overflow-hidden h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                    <img src="{{ asset('images/equipment/' . $equipment['image']) }}"
                         alt="{{ $equipment['name'] }}"
                         class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700">

                    <!-- Status Badge -->
                    <div class="absolute top-4 left-4">
                        @if($equipment['status'] === 'available')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            Tersedia
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Maintenance
                        </span>
                        @endif
                    </div>

                    <!-- Category Icon -->
                    <div class="absolute top-4 right-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="{{ $equipment['icon'] }} text-white text-sm"></i>
                        </div>
                    </div>

                    <!-- Quantity Indicator -->
                    <div class="absolute bottom-4 right-4">
                        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-lg">
                            <div class="text-xs text-gray-600">Tersedia</div>
                            <div class="text-sm font-bold text-blue-600">
                                {{ $equipment['quantity_available'] }}/{{ $equipment['quantity_total'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-4">
                        <div class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold mb-3 border border-blue-200">
                            {{ $equipment['category'] }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">{{ $equipment['name'] }}</h3>
                        <p class="text-sm text-gray-500 font-medium">{{ $equipment['model'] }}</p>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                        {{ $equipment['description'] }}
                    </p>

                    <!-- Specifications Preview -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Spesifikasi Utama</h4>
                        <div class="space-y-1">
                            @foreach(array_slice($equipment['specifications'], 0, 2) as $spec)
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-check-circle text-blue-500 mr-2 flex-shrink-0"></i>
                                <span>{{ $spec }}</span>
                            </div>
                            @endforeach
                            @if(count($equipment['specifications']) > 2)
                            <div class="text-xs text-blue-600 font-medium">
                                +{{ count($equipment['specifications']) - 2 }} spesifikasi lainnya
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Loan Duration -->
                    <div class="flex items-center justify-between text-sm mb-6">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            <span>{{ $equipment['loan_duration'] }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <button onclick="showEquipmentDetail({{ $equipment['id'] }})"
                                class="w-full bg-blue-500 text-white px-4 py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </button>

                        @if($equipment['status'] === 'available' && $equipment['quantity_available'] > 0)
                        <button onclick="openLoanModal({{ $equipment['id'] }})"
                                class="w-full border-2 border-blue-500 text-blue-500 px-4 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-hand-holding mr-2"></i>
                            Ajukan Peminjaman
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
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada alat ditemukan</h3>
                <p class="text-gray-600">Coba ubah filter atau kata kunci pencarian Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- Loan Request Modal -->
<div id="loanModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeLoanModal()"></div>

        <!-- Modal content -->
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Ajukan Peminjaman Alat</h3>
                <button onclick="closeLoanModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="loanForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIM *</label>
                        <input type="text" name="student_id" required
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

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai *</label>
                        <input type="date" name="start_date" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai *</label>
                        <input type="date" name="end_date" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dosen Pembimbing *</label>
                    <input type="text" name="supervisor" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Penggunaan *</label>
                    <textarea name="purpose" rows="4" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan tujuan dan rencana penggunaan alat..."></textarea>
                </div>

                <!-- Equipment Info -->
                <div id="equipment-info" class="bg-gray-50 rounded-xl p-4">
                    <!-- Equipment details will be populated by JavaScript -->
                </div>

                <!-- Terms -->
                <div class="flex items-start space-x-3">
                    <input type="checkbox" id="terms" required class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="terms" class="text-sm text-gray-600">
                        Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> peminjaman alat laboratorium.
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex space-x-4">
                    <button type="button" onclick="closeLoanModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors duration-200">
                        Kirim Permohonan
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
#loanModal.show {
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
    // Equipment data for JavaScript
    const equipments = @json($equipments);

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize filters
    initFilters();

    // Set minimum date for date inputs
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="start_date"]').setAttribute('min', today);

    // Update end date minimum when start date changes
    document.querySelector('input[name="start_date"]').addEventListener('change', function() {
        const startDate = this.value;
        document.querySelector('input[name="end_date"]').setAttribute('min', startDate);
    });
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
    const statusFilter = document.getElementById('status-filter');

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

            filterEquipment();
        });
    });

    // Search filter
    searchInput.addEventListener('input', filterEquipment);

    // Status filter
    statusFilter.addEventListener('change', filterEquipment);
}

function filterEquipment() {
    const activeCategory = document.querySelector('.category-btn.bg-blue-500').dataset.category;
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value;

    const equipmentCards = document.querySelectorAll('.equipment-card');
    let visibleCount = 0;

    equipmentCards.forEach(card => {
        const category = card.dataset.category;
        const name = card.dataset.name;
        const status = card.dataset.status;

        const categoryMatch = activeCategory === 'all' || category === activeCategory;
        const searchMatch = name.includes(searchTerm);
        const statusMatch = statusFilter === 'all' || status === statusFilter;

        if (categoryMatch && searchMatch && statusMatch) {
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

function showEquipmentDetail(equipmentId) {
    window.location.href = `/services/equipment-loan/${equipmentId}`;
}

function openLoanModal(equipmentId) {
    const equipment = @json($equipments).find(eq => eq.id === equipmentId);
    if (!equipment) return;

    // Populate equipment info in modal
    const equipmentInfo = document.getElementById('equipment-info');
    equipmentInfo.innerHTML = `
        <h4 class="font-semibold text-gray-900 mb-3">Alat yang Dipinjam</h4>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                <i class="${equipment.icon} text-white"></i>
            </div>
            <div>
                <div class="font-semibold text-gray-900">${equipment.name}</div>
                <div class="text-sm text-gray-600">${equipment.model}</div>
                <div class="text-sm text-blue-600">Tersedia: ${equipment.quantity_available}/${equipment.quantity_total} unit</div>
            </div>
        </div>
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h5 class="font-semibold text-yellow-800 mb-2">Persyaratan Peminjaman:</h5>
            <ul class="text-sm text-yellow-700 space-y-1">
                ${equipment.requirements.map(req => `<li>• ${req}</li>`).join('')}
            </ul>
        </div>
    `;

    // Show modal
    document.getElementById('loanModal').classList.remove('hidden');
    document.getElementById('loanModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeLoanModal() {
    document.getElementById('loanModal').classList.add('hidden');
    document.getElementById('loanModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Handle form submission
document.getElementById('loanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Permohonan peminjaman berhasil dikirim! Kami akan menghubungi Anda dalam 1x24 jam.');
    closeLoanModal();
});
</script>
@endsection
