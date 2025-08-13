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
    <div class="space-y-3">
        @forelse($slotsWithStatus as $time => $slot)
            <div class="p-4 border rounded-lg transition-all duration-200 hover:shadow-md
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
                            <div class="font-semibold text-gray-900">
                                {{ isset($slot['label']) ? $slot['label'] : $time . ':00 - ' . (intval($time) + 1) . ':00' }}
                            </div>
                            <div class="text-sm text-gray-600">
                                @if($slot['status'] === 'available')
                                    <span class="text-green-700 font-medium">Tersedia</span>
                                @elseif($slot['status'] === 'blocked')
                                    <span class="text-red-700 font-medium">Diblokir</span>
                                @elseif($slot['status'] === 'booked')
                                    <span class="text-yellow-700 font-medium">Ada Kunjungan</span>
                                @endif

                                @if(isset($slot['reason']) && $slot['reason'])
                                    <span class="text-gray-500"> - {{ $slot['reason'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        @if($slot['status'] === 'available')
                            <button onclick="blockSlot('{{ $time }}')"
                                    class="block-btn bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200 transition">
                                <i class="fas fa-ban mr-1"></i> Blokir
                            </button>
                        @elseif($slot['status'] === 'blocked')
                            <button onclick="unblockSlot('{{ $time }}')"
                                    class="unblock-btn bg-green-100 text-green-700 px-3 py-1 rounded text-sm hover:bg-green-200 transition">
                                <i class="fas fa-check mr-1"></i> Aktifkan
                            </button>
                        @elseif($slot['status'] === 'booked')
                            @php
                                $visit = null;
                                if (isset($visits)) {
                                    $visit = $visits->first(function($v) use ($time) {
                                        return $v->waktu_kunjungan->format('H:i') === $time;
                                    });
                                }
                            @endphp
                            @if($visit)
                                <a href="{{ route('admin.visits.show', $visit) }}" target="_blank"
                                   class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200 transition">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </a>
                            @else
                                <button onclick="viewVisit('{{ $time }}')"
                                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200 transition">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-clock text-2xl mb-3"></i>
                <p>Tidak ada slot waktu yang dikonfigurasi untuk tanggal ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Visits for This Date -->
    @if(isset($visits) && $visits->count() > 0)
        <div class="border-t pt-6">
            <h5 class="font-semibold text-gray-900 mb-4">
                <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                Kunjungan Terjadwal ({{ $visits->count() }})
            </h5>
            <div class="space-y-3">
                @foreach($visits as $visit)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="font-semibold text-blue-900">
                                    {{ $visit->namaPengunjung ?? 'Nama tidak tersedia' }}
                                </div>
                                <div class="text-sm text-blue-700 mt-1">
                                    {{ $visit->institution ?? 'Institution not specified' }}
                                </div>
                                <div class="flex flex-wrap items-center gap-4 text-sm text-blue-600 mt-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $visit->waktu_kunjungan ? $visit->waktu_kunjungan->format('H:i') : 'Waktu tidak tersedia' }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $visit->jumlahPengunjung ?? 0 }} peserta
                                    </div>
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                            {{ isset($visit->status) ? ucfirst($visit->status) : 'Status tidak tersedia' }}
                                        </span>
                                    </div>
                                </div>
                                @if(isset($visit->keperluan) && $visit->keperluan)
                                    <div class="text-sm text-gray-600 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ $visit->keperluan }}
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('admin.visits.show', $visit) }}" target="_blank"
                               class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition flex items-center">
                                <i class="fas fa-external-link-alt mr-1"></i> Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Summary Stats -->
    <div class="border-t pt-6">
        <h5 class="font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-bar mr-2 text-purple-600"></i>
            Ringkasan Hari Ini
        </h5>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-green-100 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-800">
                    {{ isset($slotsWithStatus) ? count(array_filter($slotsWithStatus, function($slot) { return isset($slot['status']) && $slot['status'] === 'available'; })) : 0 }}
                </div>
                <div class="text-sm text-green-700 font-medium">Slot Tersedia</div>
            </div>
            <div class="bg-red-100 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-800">
                    {{ isset($slotsWithStatus) ? count(array_filter($slotsWithStatus, function($slot) { return isset($slot['status']) && $slot['status'] === 'blocked'; })) : 0 }}
                </div>
                <div class="text-sm text-red-700 font-medium">Slot Diblokir</div>
            </div>
            <div class="bg-yellow-100 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-yellow-800">
                    {{ isset($slotsWithStatus) ? count(array_filter($slotsWithStatus, function($slot) { return isset($slot['status']) && $slot['status'] === 'booked'; })) : 0 }}
                </div>
                <div class="text-sm text-yellow-700 font-medium">Slot Terisi</div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span>Total Slot Dikonfigurasi:</span>
                <span class="font-medium">{{ isset($slotsWithStatus) ? count($slotsWithStatus) : 0 }}</span>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-600 mt-1">
                <span>Total Kunjungan:</span>
                <span class="font-medium">{{ isset($visits) ? $visits->count() : 0 }}</span>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-600 mt-1">
                <span>Tingkat Utilisasi:</span>
                <span class="font-medium">
                    @php
                        $totalSlots = isset($slotsWithStatus) ? count($slotsWithStatus) : 0;
                        $bookedSlots = isset($slotsWithStatus) ? count(array_filter($slotsWithStatus, function($slot) { return isset($slot['status']) && $slot['status'] === 'booked'; })) : 0;
                        $utilization = $totalSlots > 0 ? round(($bookedSlots / $totalSlots) * 100) : 0;
                    @endphp
                    {{ $utilization }}%
                </span>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan <span class="text-red-500">*</span></label>
                    <input type="text" id="blockReason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Maintenance AC" required>
                    <p class="text-xs text-gray-500 mt-1">Alasan wajib diisi untuk memblokir slot</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="blockNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Catatan tambahan untuk referensi internal..."></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeBlockModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-ban mr-1"></i>
                    Blokir Slot
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay for this modal -->
<div id="modalLoading" class="hidden fixed inset-0 bg-black bg-opacity-30 z-40 flex items-center justify-center">
    <div class="bg-white rounded-lg p-4 flex items-center space-x-3">
        <i class="fas fa-spinner fa-spin text-blue-600"></i>
        <span class="text-gray-700">Memproses...</span>
    </div>
</div>
