@extends('layouts.admin')

@section('title', 'Edit Data Kunjungan')

@section('styles')
<style>
    .form-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .form-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .input-group {
        transition: all 0.2s ease;
    }

    .input-group:focus-within {
        transform: translateY(-1px);
    }

    .input-group input:focus,
    .input-group select:focus,
    .input-group textarea:focus {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .time-slot-loading {
        opacity: 0.5;
        pointer-events: none;
    }

    .read-only-field {
        background-color: #f9fafb;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header with Gradient -->
    <div class="form-container rounded-2xl p-8 mb-8 relative overflow-hidden">
        <div class="relative z-10">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-white/80 mb-6">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="{{ route('admin.visits.index') }}" class="hover:text-white transition">
                    Kelola Kunjungan
                </a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span class="text-white font-medium">Edit Data Kunjungan</span>
            </nav>

            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur rounded-full text-white mb-4">
                    <i class="fas fa-edit mr-2"></i>
                    <span>Edit Data Kunjungan</span>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">Koreksi Data Kunjungan</h1>
                <p class="text-white/80 text-lg">
                    Edit informasi kunjungan untuk koreksi data atau update status
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="form-card rounded-2xl shadow-2xl p-8 md:p-12">
        <form action="{{ route('admin.visits.update', $kunjungan) }}"
              method="POST" id="visitForm" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Visit Status Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Status Kunjungan</h3>
                        <p class="text-blue-700 text-sm">Kunjungan diajukan pada {{ $kunjungan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $kunjungan->statusBadgeColor }}">
                        {{ $kunjungan->statusText }}
                    </span>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-3"></i>
                        Informasi Pengunjung
                    </h3>
                    <p class="text-gray-600">Data pengunjung dan kontak yang dapat dihubungi</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="input-group">
                        <label for="namaPengunjung" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Pengunjung <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="namaPengunjung"
                               name="namaPengunjung"
                               value="{{ old('namaPengunjung', $kunjungan->namaPengunjung) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('namaPengunjung') border-red-500 @enderror"
                               required>
                        @error('namaPengunjung')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $kunjungan->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="input-group">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $kunjungan->phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Institution -->
                    <div class="input-group">
                        <label for="institution" class="block text-sm font-semibold text-gray-700 mb-2">
                            Institusi/Organisasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="institution"
                               name="institution"
                               value="{{ old('institution', $kunjungan->institution) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('institution') border-red-500 @enderror"
                               required>
                        @error('institution')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Visit Information -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                        Informasi Kunjungan
                    </h3>
                    <p class="text-gray-600">Detail jadwal dan tujuan kunjungan</p>
                </div>

                <!-- Purpose -->
                <div class="input-group">
                    <label for="tujuan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tujuan Kunjungan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="tujuan"
                           name="tujuan"
                           value="{{ old('tujuan', $kunjungan->tujuan) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('tujuan') border-red-500 @enderror"
                           required>
                    @error('tujuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Visit Date -->
                    <div class="input-group">
                        <label for="tanggal_kunjungan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_kunjungan"
                               name="tanggal_kunjungan"
                               value="{{ old('tanggal_kunjungan', $kunjungan->tanggal_kunjungan->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('tanggal_kunjungan') border-red-500 @enderror"
                               required>
                        @error('tanggal_kunjungan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visit Time -->
                    <div class="input-group">
                        <label for="waktu_kunjungan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Waktu Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <select id="waktu_kunjungan"
                                name="waktu_kunjungan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('waktu_kunjungan') border-red-500 @enderror"
                                required>
                            <option value="">Memuat slot waktu...</option>
                        </select>
                        @error('waktu_kunjungan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="timeSlotInfo" class="mt-2 text-sm text-gray-500"></div>
                    </div>

                    <!-- Participant Count -->
                    <div class="input-group">
                        <label for="jumlahPengunjung" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jumlah Peserta <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               id="jumlahPengunjung"
                               name="jumlahPengunjung"
                               value="{{ old('jumlahPengunjung', $kunjungan->jumlahPengunjung) }}"
                               min="1"
                               max="50"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('jumlahPengunjung') border-red-500 @enderror"
                               required>
                        @error('jumlahPengunjung')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status (Admin only) -->
                <div class="input-group">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status Kunjungan <span class="text-red-500">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('status') border-red-500 @enderror"
                            required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $kunjungan->status) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Notes -->
                <div class="input-group">
                    <label for="catatan_tambahan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea id="catatan_tambahan"
                              name="catatan_tambahan"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('catatan_tambahan') border-red-500 @enderror"
                              placeholder="Catatan admin, alasan perubahan, atau informasi penting lainnya...">{{ old('catatan_tambahan', $kunjungan->catatan_tambahan) }}</textarea>
                    @error('catatan_tambahan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                <a href="{{ route('admin.visits.index') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-xl hover:bg-gray-600 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.visits.show', $kunjungan) }}"
                       class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>

                    <button type="submit"
                            id="submitButton"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const form = document.getElementById('visitForm');
    const submitButton = document.getElementById('submitButton');
    const dateInput = document.getElementById('tanggal_kunjungan');
    const timeSelect = document.getElementById('waktu_kunjungan');
    const timeSlotInfo = document.getElementById('timeSlotInfo');
    const excludeId = '{{ $kunjungan->id }}';

    // Form submission handler
    form.addEventListener('submit', function(e) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });

    // Date input validation and time slot loading
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;

        if (!selectedDate) {
            resetTimeSlots();
            return;
        }

        const date = new Date(selectedDate);
        const day = date.getDay();

        // Check if it's weekend
        if (day === 0 || day === 6) { // Sunday = 0, Saturday = 6
            alert('Laboratorium tutup pada hari Sabtu dan Minggu. Silakan pilih hari kerja.');
            this.value = '';
            resetTimeSlots();
            return;
        }

        // Load available time slots
        loadAvailableTimeSlots(selectedDate);
    });

    function loadAvailableTimeSlots(date) {
        // Show loading state
        timeSelect.innerHTML = '<option value="">Memuat jadwal tersedia...</option>';
        timeSelect.disabled = true;
        timeSelect.classList.add('time-slot-loading');
        timeSlotInfo.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat jadwal...';

        // Build URL with exclude parameter for edit mode
        let url = `{{ route('admin.visits.available-slots') }}?date=${date}&exclude_id=${excludeId}`;

        // Make AJAX request to get available slots
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateTimeSlots(data.slots, data.date);
            } else {
                showTimeSlotError(data.message);
            }
        })
        .catch(error => {
            console.error('Error loading time slots:', error);
            showTimeSlotError('Terjadi kesalahan saat memuat jadwal. Silakan coba lagi.');
        })
        .finally(() => {
            timeSelect.classList.remove('time-slot-loading');
        });
    }

    function populateTimeSlots(slots, dateFormatted) {
        timeSelect.innerHTML = '<option value="">Pilih waktu kunjungan</option>';

        if (Object.keys(slots).length === 0) {
            timeSelect.innerHTML += '<option value="" disabled>Tidak ada jadwal tersedia</option>';
            timeSlotInfo.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>Semua jadwal pada tanggal ini sudah terisi.';
            timeSelect.disabled = true;
        } else {
            Object.entries(slots).forEach(([time, label]) => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = label;
                timeSelect.appendChild(option);
            });

            timeSelect.disabled = false;
            timeSlotInfo.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2"></i>${Object.keys(slots).length} slot waktu tersedia untuk ${dateFormatted}`;

            // Restore current value
            const currentTime = '{{ $kunjungan->waktu_kunjungan->format("H:i") }}';
            if (currentTime && slots.hasOwnProperty(currentTime)) {
                timeSelect.value = currentTime;
            }
        }
    }

    function showTimeSlotError(message) {
        timeSelect.innerHTML = '<option value="">Tidak dapat memuat jadwal</option>';
        timeSelect.disabled = true;
        timeSlotInfo.innerHTML = `<i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>${message}`;
    }

    function resetTimeSlots() {
        timeSelect.innerHTML = '<option value="">Pilih tanggal terlebih dahulu</option>';
        timeSelect.disabled = true;
        timeSlotInfo.innerHTML = '';
    }

    // Initialize - load time slots for current date
    document.addEventListener('DOMContentLoaded', function() {
        const initialDate = dateInput.value;
        if (initialDate) {
            loadAvailableTimeSlots(initialDate);
        }
    });

    // Auto-grow textarea
    const textarea = document.getElementById('catatan_tambahan');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
</script>
@endsection

@section('styles')
<style>
    .form-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .form-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .input-group {
        transition: all 0.2s ease;
    }

    .input-group:focus-within {
        transform: translateY(-1px);
    }

    .input-group input:focus,
    .input-group select:focus,
    .input-group textarea:focus {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .time-slot-loading {
        opacity: 0.5;
        pointer-events: none;
    }

    .floating-label {
        transition: all 0.2s ease;
    }

    .floating-label.active {
        transform: translateY(-20px) scale(0.875);
        color: #3b82f6;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header with Gradient -->
    <div class="form-container rounded-2xl p-8 mb-8 relative overflow-hidden">
        <div class="relative z-10">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-white/80 mb-6">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="{{ route('admin.visits.index') }}" class="hover:text-white transition">
                    Kelola Kunjungan
                </a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span class="text-white font-medium">
                    {{ isset($kunjungan) ? 'Edit' : 'Tambah' }}
                </span>
            </nav>

            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur rounded-full text-white mb-4">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    <span>{{ isset($kunjungan) ? 'Edit Kunjungan' : 'Tambah Kunjungan Baru' }}</span>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">
                    {{ isset($kunjungan) ? 'Edit Data Kunjungan' : 'Jadwalkan Kunjungan Baru' }}
                </h1>
                <p class="text-white/80 text-lg">
                    {{ isset($kunjungan) ? 'Perbarui informasi kunjungan laboratorium' : 'Buat jadwal kunjungan baru ke laboratorium' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="form-card rounded-2xl shadow-2xl p-8 md:p-12">
        <form action="{{ isset($kunjungan) ? route('admin.visits.update', $kunjungan) : route('admin.visits.store') }}"
              method="POST" id="visitForm" class="space-y-8">
            @csrf
            @if(isset($kunjungan))
                @method('PUT')
            @endif

            <!-- Contact Information -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-3"></i>
                        Informasi Pengunjung
                    </h3>
                    <p class="text-gray-600">Data pengunjung dan kontak yang dapat dihubungi</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="input-group">
                        <label for="namaPengunjung" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Pengunjung <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="namaPengunjung"
                               name="namaPengunjung"
                               value="{{ old('namaPengunjung', $kunjungan->namaPengunjung ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('namaPengunjung') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap pengunjung"
                               required>
                        @error('namaPengunjung')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $kunjungan->email ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                               placeholder="nama@email.com"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="input-group">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $kunjungan->phone ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                               placeholder="08xxxxxxxxxx"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Institution -->
                    <div class="input-group">
                        <label for="institution" class="block text-sm font-semibold text-gray-700 mb-2">
                            Institusi/Organisasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="institution"
                               name="institution"
                               value="{{ old('institution', $kunjungan->institution ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('institution') border-red-500 @enderror"
                               placeholder="Nama sekolah, universitas, atau organisasi"
                               required>
                        @error('institution')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Visit Information -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                        Informasi Kunjungan
                    </h3>
                    <p class="text-gray-600">Detail jadwal dan tujuan kunjungan</p>
                </div>

                <!-- Purpose -->
                <div class="input-group">
                    <label for="tujuan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tujuan Kunjungan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="tujuan"
                           name="tujuan"
                           value="{{ old('tujuan', $kunjungan->tujuan ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('tujuan') border-red-500 @enderror"
                           placeholder="Contoh: Kunjungan Edukasi Mahasiswa Fisika"
                           required>
                    @error('tujuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Visit Date -->
                    <div class="input-group">
                        <label for="tanggal_kunjungan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_kunjungan"
                               name="tanggal_kunjungan"
                               value="{{ old('tanggal_kunjungan', isset($kunjungan) ? $kunjungan->tanggal_kunjungan->format('Y-m-d') : request('date')) }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('tanggal_kunjungan') border-red-500 @enderror"
                               required>
                        @error('tanggal_kunjungan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visit Time -->
                    <div class="input-group">
                        <label for="waktu_kunjungan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Waktu Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <select id="waktu_kunjungan"
                                name="waktu_kunjungan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('waktu_kunjungan') border-red-500 @enderror"
                                required disabled>
                            <option value="">Pilih tanggal terlebih dahulu</option>
                        </select>
                        @error('waktu_kunjungan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="timeSlotInfo" class="mt-2 text-sm text-gray-500"></div>
                    </div>

                    <!-- Participant Count -->
                    <div class="input-group">
                        <label for="jumlahPengunjung" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jumlah Peserta <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               id="jumlahPengunjung"
                               name="jumlahPengunjung"
                               value="{{ old('jumlahPengunjung', $kunjungan->jumlahPengunjung ?? '') }}"
                               min="1"
                               max="50"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('jumlahPengunjung') border-red-500 @enderror"
                               placeholder="Contoh: 25"
                               required>
                        @error('jumlahPengunjung')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status (Admin only) -->
                <div class="input-group">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status Kunjungan <span class="text-red-500">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('status') border-red-500 @enderror"
                            required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $kunjungan->status ?? 'PENDING') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Notes -->
                <div class="input-group">
                    <label for="catatan_tambahan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea id="catatan_tambahan"
                              name="catatan_tambahan"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('catatan_tambahan') border-red-500 @enderror"
                              placeholder="Catatan khusus, permintaan, atau informasi penting lainnya...">{{ old('catatan_tambahan', $kunjungan->catatan_tambahan ?? '') }}</textarea>
                    @error('catatan_tambahan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                <a href="{{ route('admin.visits.index') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-xl hover:bg-gray-600 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>

                <div class="flex space-x-3">
                    @if(isset($kunjungan))
                        <a href="{{ route('admin.visits.show', $kunjungan) }}"
                           class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                    @endif

                    <button type="submit"
                            id="submitButton"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        {{ isset($kunjungan) ? 'Perbarui Kunjungan' : 'Simpan Kunjungan' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const form = document.getElementById('visitForm');
    const submitButton = document.getElementById('submitButton');
    const dateInput = document.getElementById('tanggal_kunjungan');
    const timeSelect = document.getElementById('waktu_kunjungan');
    const timeSlotInfo = document.getElementById('timeSlotInfo');
    const isEdit = {{ isset($kunjungan) ? 'true' : 'false' }};
    const excludeId = isEdit ? '{{ isset($kunjungan) ? $kunjungan->id : "" }}' : null;

    // Form submission handler
    form.addEventListener('submit', function(e) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });

    // Date input validation and time slot loading
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;

        if (!selectedDate) {
            resetTimeSlots();
            return;
        }

        const date = new Date(selectedDate);
        const day = date.getDay();

        // Check if it's Sunday
        if (day === 0) {
            alert('Laboratorium tutup pada hari Minggu. Silakan pilih hari lain.');
            this.value = '';
            resetTimeSlots();
            return;
        }

        // Load available time slots
        loadAvailableTimeSlots(selectedDate);
    });

    function loadAvailableTimeSlots(date) {
        // Show loading state
        timeSelect.innerHTML = '<option value="">Memuat jadwal tersedia...</option>';
        timeSelect.disabled = true;
        timeSelect.classList.add('time-slot-loading');
        timeSlotInfo.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat jadwal...';

        // Build URL with exclude parameter for edit mode
        let url = `{{ route('admin.visits.available-slots') }}?date=${date}`;
        if (excludeId) {
            url += `&exclude_id=${excludeId}`;
        }

        // Make AJAX request to get available slots
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateTimeSlots(data.slots, data.date);
            } else {
                showTimeSlotError(data.message);
            }
        })
        .catch(error => {
            console.error('Error loading time slots:', error);
            showTimeSlotError('Terjadi kesalahan saat memuat jadwal. Silakan coba lagi.');
        })
        .finally(() => {
            timeSelect.classList.remove('time-slot-loading');
        });
    }

    function populateTimeSlots(slots, dateFormatted) {
        timeSelect.innerHTML = '<option value="">Pilih waktu kunjungan</option>';

        if (Object.keys(slots).length === 0) {
            timeSelect.innerHTML += '<option value="" disabled>Tidak ada jadwal tersedia</option>';
            timeSlotInfo.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>Semua jadwal pada tanggal ini sudah terisi.';
            timeSelect.disabled = true;
        } else {
            Object.entries(slots).forEach(([time, label]) => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = label;
                timeSelect.appendChild(option);
            });

            timeSelect.disabled = false;
            timeSlotInfo.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2"></i>${Object.keys(slots).length} slot waktu tersedia untuk ${dateFormatted}`;

            // Restore old value if exists (for edit mode or validation errors)
            const oldTime = '{{ old("waktu_kunjungan", isset($kunjungan) ? $kunjungan->waktu_kunjungan->format("H:i") : "") }}';
            if (oldTime && slots.hasOwnProperty(oldTime)) {
                timeSelect.value = oldTime;
            }
        }
    }

    function showTimeSlotError(message) {
        timeSelect.innerHTML = '<option value="">Tidak dapat memuat jadwal</option>';
        timeSelect.disabled = true;
        timeSlotInfo.innerHTML = `<i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>${message}`;
    }

    function resetTimeSlots() {
        timeSelect.innerHTML = '<option value="">Pilih tanggal terlebih dahulu</option>';
        timeSelect.disabled = true;
        timeSlotInfo.innerHTML = '';
    }

    // Participant count validation
    const participantInput = document.getElementById('jumlahPengunjung');
    participantInput.addEventListener('input', function() {
        const count = parseInt(this.value);
        if (count > 50) {
            this.setCustomValidity('Jumlah peserta maksimal 50 orang. Untuk grup lebih besar, silakan hubungi koordinator.');
        } else if (count < 1) {
            this.setCustomValidity('Jumlah peserta minimal 1 orang.');
        } else {
            this.setCustomValidity('');
        }
    });

    // Auto-grow textarea
    const textarea = document.getElementById('catatan_tambahan');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    // Initialize - load time slots if date is already set
    document.addEventListener('DOMContentLoaded', function() {
        const initialDate = dateInput.value;
        if (initialDate) {
            loadAvailableTimeSlots(initialDate);
        }
    });

    // Floating label effect
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label) {
            // Check if input has value on load
            if (input.value) {
                label.classList.add('active');
            }

            input.addEventListener('focus', () => {
                label.classList.add('active');
            });

            input.addEventListener('blur', () => {
                if (!input.value) {
                    label.classList.remove('active');
                }
            });
        }
    });
</script>
@endsection
