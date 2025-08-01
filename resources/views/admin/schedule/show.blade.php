<!-- Schedule Detail for Specific Date -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h4 class="text-lg font-semibold text-gray-900">
            {{ $selectedDate->format('l, d F Y') }}
        </h4>
        <div class="flex space-x-2">
            <button onclick="enableAllSlots()" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                <i class="fas fa-check mr-1"></i> Aktifkan Semua
            </button>
            <button onclick="disableAllSlots()" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">
                <i class="fas fa-times mr-1"></i> Blokir Semua
            </button>
        </div>
    </div>

    <!-- Time Slots Grid -->
    <div class="grid grid-cols-1 gap-3">
        @foreach($slotsWithStatus as $time => $slot)
            <div class="slot-management-card p-4 border rounded-lg transition-all duration-200 hover:shadow-md
                {{ $slot['status'] === 'available' ? 'border-green-200 bg-green-50' : '' }}
                {{ $slot['status'] === 'blocked' ? 'border-red-200 bg-red-50' : '' }}
                {{ $slot['status'] === 'booked' ? 'border-yellow-200 bg-yellow-50' : '' }}">

                <div class="flex items-center justify-between">
                    <!-- Slot Info -->
                    <div class="flex items-center space-x-3">
                        <!-- Status Icon -->
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            {{ $slot['status'] === 'available' ? 'bg-green-500' : '' }}
                            {{ $slot['status'] === 'blocked' ? 'bg-red-500' : '' }}
                            {{ $slot['status'] === 'booked' ? 'bg-yellow-500' : '' }}">
                            @if($slot['status'] === 'available')
                                <i class="fas fa-check text-white text-sm"></i>
                            @elseif($slot['status'] === 'blocked')
                                <i class="fas fa-ban text-white text-sm"></i>
                            @elseif($slot['status'] === 'booked')
                                <i class="fas fa-calendar-check text-white text-sm"></i>
                            @endif
                        </div>

                        <!-- Time & Status -->
                        <div>
                            <div class="font-semibold text-gray-900">{{ $slot['label'] }}</div>
                            <div class="text-sm text-gray-600">
                                @if($slot['status'] === 'available')
                                    <span class="text-green-700 font-medium">Tersedia</span>
                                @elseif($slot['status'] === 'blocked')
                                    <span class="text-red-700 font-medium">Diblokir</span>
                                @elseif($slot['status'] === 'booked')
                                    <span class="text-yellow-700 font-medium">Ada Kunjungan</span>
                                @endif

                                @if($slot['reason'])
                                    <span class="text-gray-500"> - {{ $slot['reason'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        @if($slot['status'] === 'available')
                            <button onclick="blockSlot('{{ $time }}')"
                                    class="block-btn bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200 transition disabled:opacity-50">
                                <i class="fas fa-ban mr-1"></i> Blokir
                            </button>
                        @elseif($slot['status'] === 'blocked')
                            <button onclick="unblockSlot('{{ $time }}')"
                                    class="unblock-btn bg-green-100 text-green-700 px-3 py-1 rounded text-sm hover:bg-green-200 transition disabled:opacity-50">
                                <i class="fas fa-check mr-1"></i> Aktifkan
                            </button>
                        @elseif($slot['status'] === 'booked')
                            <button onclick="viewVisit('{{ $time }}')"
                                    class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200 transition">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Visits for This Date -->
    @if($visits->count() > 0)
        <div class="border-t pt-6">
            <h5 class="font-semibold text-gray-900 mb-4">Kunjungan Terjadwal ({{ $visits->count() }})</h5>
            <div class="space-y-3">
                @foreach($visits as $visit)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-semibold text-blue-900">{{ $visit->namaPengunjung }}</div>
                                <div class="text-sm text-blue-700">{{ $visit->institution }}</div>
                                <div class="text-sm text-blue-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i> {{ $visit->formattedTime }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-users mr-1"></i> {{ $visit->jumlahPengunjung }} peserta
                                    <span class="mx-2">•</span>
                                    <span class="px-2 py-1 rounded text-xs {{ $visit->statusBadgeColor }}">
                                        {{ $visit->statusText }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('admin.visits.show', $visit) }}"
                               class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                                <i class="fas fa-external-link-alt mr-1"></i> Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Summary Stats -->
    <div class="border-t pt-4">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="bg-green-100 rounded-lg p-3">
                <div class="text-2xl font-bold text-green-800">
                    {{ count(array_filter($slotsWithStatus, function($slot) { return $slot['status'] === 'available'; })) }}
                </div>
                <div class="text-sm text-green-700">Slot Tersedia</div>
            </div>
            <div class="bg-red-100 rounded-lg p-3">
                <div class="text-2xl font-bold text-red-800">
                    {{ count(array_filter($slotsWithStatus, function($slot) { return $slot['status'] === 'blocked'; })) }}
                </div>
                <div class="text-sm text-red-700">Slot Diblokir</div>
            </div>
            <div class="bg-yellow-100 rounded-lg p-3">
                <div class="text-2xl font-bold text-yellow-800">
                    {{ count(array_filter($slotsWithStatus, function($slot) { return $slot['status'] === 'booked'; })) }}
                </div>
                <div class="text-sm text-yellow-700">Slot Terisi</div>
            </div>
        </div>
    </div>
</div>

<!-- Block Slot Modal -->
<div id="blockSlotModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Blokir Slot Waktu</h3>
            <button onclick="closeBlockModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="blockSlotForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slot Waktu</label>
                    <input type="text" id="blockSlotTime" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan</label>
                    <input type="text" id="blockReason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Maintenance AC" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="blockNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeBlockModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Blokir Slot
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const selectedDate = '{{ $selectedDate->format('Y-m-d') }}';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentSlotTime = null;

    function blockSlot(timeSlot) {
        // Add loading state
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';

        // Restore button after delay if modal doesn't open
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 500);

        currentSlotTime = timeSlot;
        const timeDisplay = timeSlot + ':00 - ' + (parseInt(timeSlot.split(':')[0]) + 1).toString().padStart(2, '0') + ':00';
        document.getElementById('blockSlotTime').value = timeDisplay;
        document.getElementById('blockSlotModal').classList.remove('hidden');
    }

    function unblockSlot(timeSlot) {
        if (confirm('Apakah Anda yakin ingin mengaktifkan slot ' + timeSlot + '?')) {
            // Add loading state
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengaktifkan...';

            updateSlotStatus(timeSlot, true, null, null);
        }
    }

    function enableAllSlots() {
        if (confirm('Apakah Anda yakin ingin mengaktifkan semua slot yang tersedia?')) {
            const availableSlots = @json(array_keys(array_filter($slotsWithStatus, function($slot) { return $slot['status'] === 'blocked'; })));

            availableSlots.forEach(timeSlot => {
                updateSlotStatus(timeSlot, true, null, null);
            });

            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }

    function disableAllSlots() {
        const reason = prompt('Masukkan alasan untuk memblokir semua slot:');
        if (reason && confirm('Apakah Anda yakin ingin memblokir semua slot yang tersedia?')) {
            const availableSlots = @json(array_keys(array_filter($slotsWithStatus, function($slot) { return $slot['status'] === 'available'; })));

            availableSlots.forEach(timeSlot => {
                updateSlotStatus(timeSlot, false, reason, 'Batch disable all slots');
            });

            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }

    function viewVisit(timeSlot) {
        // Find visit for this time slot and redirect to detail
        @foreach($visits as $visit)
            if ('{{ $visit->waktu_kunjungan->format("H:i") }}' === timeSlot) {
                window.open('{{ route("admin.visits.show", $visit) }}', '_blank');
                return;
            }
        @endforeach
    }

    function closeBlockModal() {
        document.getElementById('blockSlotModal').classList.add('hidden');
        document.getElementById('blockSlotForm').reset();
        currentSlotTime = null;
    }

    // Block slot form handler
    document.getElementById('blockSlotForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const reason = document.getElementById('blockReason').value;
        const notes = document.getElementById('blockNotes').value;

        if (currentSlotTime && reason) {
            updateSlotStatus(currentSlotTime, false, reason, notes);
            closeBlockModal();
        }
    });

    function updateSlotStatus(timeSlot, isAvailable, reason, notes) {
        fetch('{{ route("admin.schedule.update-slot") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                date: selectedDate,
                time_slot: timeSlot,
                is_available: isAvailable,
                reason: reason,
                notes: notes
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Slot berhasil diperbarui!');
                // Reload content after short delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showAlert('error', 'Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memperbarui slot.');
        });
    }

    // Alert function
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const iconClass = type === 'success' ? 'fa-check' : 'fa-exclamation-triangle';

        const alert = document.createElement('div');
        alert.className = `fixed top-4 right-4 ${alertClass} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300`;
        alert.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${iconClass} mr-2"></i>
                ${message}
            </div>
        `;

        document.body.appendChild(alert);

        setTimeout(() => {
            alert.classList.remove('translate-x-full', 'opacity-0');
        }, 100);

        setTimeout(() => {
            alert.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    }

    // Close modal when clicking outside
    document.getElementById('blockSlotModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBlockModal();
        }
    });
</script>

<style>
    .slot-management-card {
        transition: all 0.2s ease;
    }

    .slot-management-card:hover {
        transform: translateY(-1px);
    }
</style>
