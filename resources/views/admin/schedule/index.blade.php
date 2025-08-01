@extends('layouts.admin')

@section('title', 'Kelola Jadwal Available')

@section('styles')
<style>
    .day-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .day-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .day-available {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .day-limited {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .day-full {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .day-closed {
        background: linear-gradient(135deg, #6b7280, #4b5563);
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .slot-card {
        transition: all 0.2s ease;
    }

    .slot-card:hover {
        transform: translateY(-1px);
    }

    .slot-available {
        border-left: 4px solid #10b981;
        background: linear-gradient(90deg, #f0fff4, #ffffff);
    }

    .slot-blocked {
        border-left: 4px solid #ef4444;
        background: linear-gradient(90deg, #fef2f2, #ffffff);
    }

    .slot-booked {
        border-left: 4px solid #f59e0b;
        background: linear-gradient(90deg, #fffbeb, #ffffff);
    }

    .calendar-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Jadwal Available</h1>
            <p class="text-gray-600 mt-2">Atur ketersediaan slot waktu laboratorium</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="openBatchModal()"
                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition flex items-center">
                <i class="fas fa-layer-group mr-2"></i>
                Batch Update
            </button>
            <button onclick="openCopyModal()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-copy mr-2"></i>
                Salin Jadwal
            </button>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="stats-card text-white p-6 rounded-xl shadow-lg mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center">
            <div class="text-3xl font-bold mb-2">{{ $stats['total'] }}</div>
            <div class="text-white/80">Total Slot Diatur</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold mb-2">{{ $stats['available'] }}</div>
            <div class="text-white/80">Slot Tersedia</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold mb-2">{{ $stats['unavailable'] }}</div>
            <div class="text-white/80">Slot Diblokir</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold mb-2">{{ $stats['availability_percentage'] }}%</div>
            <div class="text-white/80">Tingkat Ketersediaan</div>
        </div>
    </div>
</div>

<!-- Month Navigation -->
<div class="calendar-header text-white p-6 rounded-xl shadow-lg mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold">{{ $selectedDate->format('F Y') }}</h2>
            <p class="text-white/80">Kelola ketersediaan slot waktu bulanan</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.schedule.index') }}?month={{ $selectedDate->copy()->subMonth()->format('Y-m') }}"
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-chevron-left mr-2"></i>
                Bulan Lalu
            </a>
            <a href="{{ route('admin.schedule.index') }}?month={{ now()->format('Y-m') }}"
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition">
                Bulan Ini
            </a>
            <a href="{{ route('admin.schedule.index') }}?month={{ $selectedDate->copy()->addMonth()->format('Y-m') }}"
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center">
                Bulan Depan
                <i class="fas fa-chevron-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- Calendar Grid -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
        @foreach($monthData as $day => $data)
            <div class="day-card p-4 rounded-lg text-white
                {{ $data['status'] === 'available' ? 'day-available' : '' }}
                {{ $data['status'] === 'limited' ? 'day-limited' : '' }}
                {{ $data['status'] === 'full' ? 'day-full' : '' }}
                {{ $data['status'] === 'closed' ? 'day-closed' : '' }}"
                onclick="openDayModal('{{ $data['date']->format('Y-m-d') }}')">

                <div class="text-center">
                    <div class="text-2xl font-bold mb-1">{{ $day }}</div>
                    <div class="text-xs mb-2">{{ $data['date']->format('D') }}</div>

                    @if($data['status'] === 'closed')
                        <div class="text-xs">Tutup</div>
                    @else
                        <div class="text-xs mb-1">
                            {{ $data['available_slots'] }}/{{ $data['total_slots'] }} slot
                        </div>
                        @if($data['visits_count'] > 0)
                            <div class="text-xs">
                                <i class="fas fa-users mr-1"></i>{{ $data['visits_count'] }} kunjungan
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Legend -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Keterangan</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
            <span class="text-sm text-gray-700">Tersedia (4+ slot)</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
            <span class="text-sm text-gray-700">Terbatas (1-3 slot)</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
            <span class="text-sm text-gray-700">Penuh (0 slot)</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-gray-500 rounded-full mr-3"></div>
            <span class="text-sm text-gray-700">Tutup (Sabtu-Minggu)</span>
        </div>
    </div>
</div>

<!-- Day Detail Modal -->
<div id="dayModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-900" id="dayModalTitle">Detail Jadwal</h3>
            <button onclick="closeDayModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="dayModalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Batch Update Modal -->
<div id="batchModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Batch Update Slot</h3>
            <button onclick="closeBatchModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="batchForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="batchDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slot Waktu</label>
                    <div class="space-y-2" id="batchSlots">
                        <label class="flex items-center">
                            <input type="checkbox" value="08:00" class="mr-2"> 08:00 - 09:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="09:00" class="mr-2"> 09:00 - 10:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="10:00" class="mr-2"> 10:00 - 11:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="11:00" class="mr-2"> 11:00 - 12:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="13:00" class="mr-2"> 13:00 - 14:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="14:00" class="mr-2"> 14:00 - 15:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="15:00" class="mr-2"> 15:00 - 16:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="16:00" class="mr-2"> 16:00 - 17:00
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="17:00" class="mr-2"> 17:00 - 18:00
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aksi</label>
                    <select id="batchAction" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="enable">Aktifkan Slot</option>
                        <option value="disable">Nonaktifkan Slot</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan (Opsional)</label>
                    <input type="text" id="batchReason" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Alasan perubahan">
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeBatchModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Copy Schedule Modal -->
<div id="copyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Salin Jadwal</h3>
            <button onclick="closeCopyModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="copyForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sumber</label>
                    <input type="date" id="sourceDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Tujuan</label>
                    <textarea id="targetDates" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Masukkan tanggal tujuan, satu per baris (format: YYYY-MM-DD)" required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Contoh: 2024-01-15</p>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" id="overwrite" class="mr-2">
                        <span class="text-sm text-gray-700">Timpa pengaturan yang sudah ada</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeCopyModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Salin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function openDayModal(date) {
        document.getElementById('dayModalTitle').textContent = `Jadwal ${new Date(date).toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}`;

        // Load day details
        loadDayDetails(date);

        document.getElementById('dayModal').classList.remove('hidden');
    }

    function loadDayDetails(date) {
        document.getElementById('dayModalContent').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-3"></i>
                <p class="text-gray-500">Memuat detail jadwal...</p>
            </div>
        `;

        fetch(`{{ route('admin.schedule.show') }}?date=${date}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('dayModalContent').innerHTML = data.html;
                } else {
                    document.getElementById('dayModalContent').innerHTML = data.html || `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-3"></i>
                            <p>Gagal memuat detail jadwal</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('dayModalContent').innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-3"></i>
                        <p>Gagal memuat detail jadwal</p>
                    </div>
                `;
            });
    }

    function closeDayModal() {
        document.getElementById('dayModal').classList.add('hidden');
    }

    function openBatchModal() {
        document.getElementById('batchDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('batchModal').classList.remove('hidden');
    }

    function closeBatchModal() {
        document.getElementById('batchModal').classList.add('hidden');
        document.getElementById('batchForm').reset();
    }

    function openCopyModal() {
        document.getElementById('copyModal').classList.remove('hidden');
    }

    function closeCopyModal() {
        document.getElementById('copyModal').classList.add('hidden');
        document.getElementById('copyForm').reset();
    }

    // Batch update form handler
    document.getElementById('batchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const date = document.getElementById('batchDate').value;
        const selectedSlots = Array.from(document.querySelectorAll('#batchSlots input:checked')).map(cb => cb.value);
        const action = document.getElementById('batchAction').value;
        const reason = document.getElementById('batchReason').value;

        if (!date || selectedSlots.length === 0) {
            showAlert('error', 'Silakan pilih tanggal dan minimal satu slot.');
            return;
        }

        $.ajax({
            url: '{{ route("admin.schedule.batch-update") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            data: JSON.stringify({
                date: date,
                slots: selectedSlots,
                action: action,
                reason: reason
            }),
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    closeBatchModal();
                    setTimeout(() => window.location.reload(), 1500);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response.message || 'Gagal melakukan batch update.');
            }
        });
    });

    // Copy schedule form handler
    document.getElementById('copyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const sourceDate = document.getElementById('sourceDate').value;
        const targetDatesText = document.getElementById('targetDates').value;
        const overwrite = document.getElementById('overwrite').checked;

        if (!sourceDate || !targetDatesText) {
            showAlert('error', 'Silakan isi tanggal sumber dan tujuan.');
            return;
        }

        const targetDates = targetDatesText.split('\n').map(date => date.trim()).filter(date => date);

        $.ajax({
            url: '{{ route("admin.schedule.copy") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            data: JSON.stringify({
                source_date: sourceDate,
                target_dates: targetDates,
                overwrite: overwrite
            }),
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    closeCopyModal();
                    setTimeout(() => window.location.reload(), 1500);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response.message || 'Gagal menyalin jadwal.');
            }
        });
    });

    // Alert function
    function showAlert(type, message) {
        const alertClass = {
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'info': 'bg-blue-500'
        }[type] || 'bg-gray-500';

        const iconClass = {
            'success': 'fa-check',
            'error': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        }[type] || 'fa-info';

        const alert = $(`
            <div class="fixed top-4 right-4 ${alertClass} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas ${iconClass} mr-2"></i>
                    ${message}
                </div>
            </div>
        `);

        $('body').append(alert);

        setTimeout(() => {
            alert.removeClass('translate-x-full opacity-0');
        }, 100);

        setTimeout(() => {
            alert.addClass('translate-x-full opacity-0');
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    }

    // Close modals on outside click
    ['dayModal', 'batchModal', 'copyModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });

    // Keyboard handler for modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['dayModal', 'batchModal', 'copyModal'].forEach(modalId => {
                document.getElementById(modalId).classList.add('hidden');
            });
        }
    });
</script>
@endsection
