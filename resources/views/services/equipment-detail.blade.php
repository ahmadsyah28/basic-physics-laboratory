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
                        <img src="{{ asset('images/equipment/' . $equipment['image']) }}"
                             alt="{{ $equipment['name'] }}"
                             class="w-full h-full object-cover">
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute top-6 left-6">
                        @if($equipment['status'] === 'available')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200 shadow-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            Tersedia
                        </span>
                        @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200 shadow-lg">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Maintenance
                        </span>
                        @endif
                    </div>

                    <!-- Category Icon -->
                    <div class="absolute top-6 right-6">
                        <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="{{ $equipment['icon'] }} text-white text-xl"></i>
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
                    <p class="text-xl text-gray-600 font-medium mb-2">{{ $equipment['model'] }}</p>
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
                        </div>
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Durasi Peminjaman</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $equipment['loan_duration'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    @if($equipment['status'] === 'available' && $equipment['quantity_available'] > 0)
                    <button onclick="openLoanModal({{ $equipment['id'] }})"
                            class="flex-1 bg-blue-500 text-white px-8 py-4 rounded-2xl font-semibold hover:bg-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <i class="fas fa-hand-holding mr-3"></i>
                        Ajukan Peminjaman
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
                            data-tab="specifications">
                        Spesifikasi
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm"
                            data-tab="requirements">
                        Persyaratan
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
                    </div>
                </div>

                <!-- Requirements Tab -->
                <div id="requirements-tab" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Persyaratan Peminjaman</h3>
                        <div class="space-y-4">
                            @foreach($equipment['requirements'] as $requirement)
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
                                <li>• Peminjaman maksimal {{ $equipment['loan_duration'] }}</li>
                                <li>• Alat harus dikembalikan dalam kondisi baik</li>
                                <li>• Kerusakan akan dikenakan biaya perbaikan</li>
                                <li>• Konfirmasi peminjaman akan diberikan dalam 1x24 jam</li>
                                <li>• Briefing wajib untuk alat dengan kategori tertentu</li>
                            </ul>
                        </div>
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
                                    </ol>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                                        Keamanan
                                    </h4>
                                    <ol class="space-y-2 text-gray-700">
                                        <li>1. Gunakan APD yang sesuai</li>
                                        <li>2. Baca manual operasi dengan teliti</li>
                                        <li>3. Jangan operasikan tanpa pengawasan</li>
                                        <li>4. Matikan alat setelah penggunaan</li>
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

                                <div class="p-6 bg-green-50 rounded-xl border border-green-200">
                                    <h4 class="font-semibold text-green-900 mb-3">
                                        <i class="fas fa-phone mr-2"></i>Kontak Darurat
                                    </h4>
                                    <div class="text-sm text-green-700 space-y-1">
                                        <p>• <strong>Staff Laboratorium:</strong> Ext. 123 (jam kerja)</p>
                                        <p>• <strong>Teknisi:</strong> Ext. 456 (jam kerja)</p>
                                        <p>• <strong>Emergency:</strong> 0811-xxxx-xxxx (24 jam)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIM/NIP *</label>
                        <input type="text" name="id_number" required
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pinjam *</label>
                        <input type="date" name="loan_date" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kembali *</label>
                        <input type="date" name="return_date" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Penggunaan *</label>
                    <textarea name="purpose" rows="3" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan untuk keperluan apa alat ini akan digunakan..."></textarea>
                </div>

                <!-- Equipment Info -->
                <div id="equipment-info" class="bg-gray-50 rounded-xl p-4">
                    <!-- Equipment details will be populated by JavaScript -->
                </div>

                <!-- Admin Notice -->
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                        <div class="text-sm text-blue-700">
                            <strong>Informasi Penting:</strong> Permintaan peminjaman akan diproses dalam 1x24 jam. Anda akan dihubungi untuk konfirmasi dan briefing penggunaan alat.
                        </div>
                    </div>
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
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Equipment data for JavaScript
    window.equipmentData = [@json($equipment)];

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize tabs
    initTabs();

    // Set minimum date for date inputs
    const today = new Date().toISOString().split('T')[0];
    const loanDateInput = document.querySelector('input[name="loan_date"]');
    const returnDateInput = document.querySelector('input[name="return_date"]');

    if (loanDateInput) {
        loanDateInput.setAttribute('min', today);
        loanDateInput.addEventListener('change', function() {
            const loanDate = new Date(this.value);
            const nextDay = new Date(loanDate);
            nextDay.setDate(loanDate.getDate() + 1);

            if (returnDateInput) {
                returnDateInput.setAttribute('min', nextDay.toISOString().split('T')[0]);
            }
        });
    }
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

function openLoanModal(equipmentId) {
    const equipment = window.equipmentData[0];
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
                <div class="text-sm text-blue-600">Tersedia: ${equipment.quantity_available} unit | Max: ${equipment.loan_duration}</div>
            </div>
        </div>
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h5 class="font-semibold text-yellow-800 mb-2">Persyaratan:</h5>
            <ul class="text-sm text-yellow-700 space-y-1">
                ${equipment.requirements.slice(0, 3).map(req => `<li>• ${req}</li>`).join('')}
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
    alert('Permintaan peminjaman berhasil dikirim! Kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi.');
    closeLoanModal();
});
</script>
@endsection
