{{-- resources/views/admin/peminjaman/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Peminjaman Alat')

@section('styles')
<style>
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold;
    }
    .status-PENDING { @apply bg-yellow-100 text-yellow-800 border border-yellow-200; }
    .status-PROCESSING { @apply bg-blue-100 text-blue-800 border border-blue-200; }
    .status-COMPLETED { @apply bg-green-100 text-green-800 border border-green-200; }
    .status-CANCELLED { @apply bg-red-100 text-red-800 border border-red-200; }

    .borrower-usk { @apply bg-blue-100 text-blue-800 border border-blue-200; }
    .borrower-external { @apply bg-purple-100 text-purple-800 border border-purple-200; }

    .priority-high { @apply bg-red-50 border-l-4 border-red-500; }
    .priority-medium { @apply bg-yellow-50 border-l-4 border-yellow-500; }
    .priority-low { @apply bg-gray-50; }

    .stats-card {
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg text-white p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Kelola Peminjaman Alat</h1>
                    <p class="text-blue-100">Pantau dan kelola semua peminjaman alat laboratorium</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ $statistics['total'] }}</div>
                <div class="text-sm text-blue-100">Total Peminjaman</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['pending'] }}</div>
                    <div class="text-sm text-gray-600">Menunggu</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-hand-holding text-blue-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['processing'] }}</div>
                    <div class="text-sm text-gray-600">Dipinjam</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['completed'] }}</div>
                    <div class="text-sm text-gray-600">Selesai</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['overdue'] }}</div>
                    <div class="text-sm text-gray-600">Terlambat</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-times text-orange-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['due_soon'] }}</div>
                    <div class="text-sm text-gray-600">Jatuh Tempo</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-line text-gray-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</div>
                    <div class="text-sm text-gray-600">Total</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Peminjaman</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nama, HP, atau tujuan..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Menunggu</option>
                        <option value="PROCESSING" {{ request('status') === 'PROCESSING' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>Selesai</option>
                        <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Borrower Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Peminjam</label>
                    <select name="borrower_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tipe</option>
                        <option value="1" {{ request('borrower_type') === '1' ? 'selected' : '' }}>Mahasiswa USK</option>
                        <option value="0" {{ request('borrower_type') === '0' ? 'selected' : '' }}>Eksternal</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam</label>
                    <div class="flex space-x-2">
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <input type="date"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </a>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.peminjaman.export', request()->query()) }}"
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="hidden bg-blue-50 rounded-lg p-4 border border-blue-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-blue-800 font-medium mr-4">
                    <span id="selected-count">0</span> peminjaman dipilih
                </span>
            </div>
            <div class="flex space-x-2">
                <select id="bulk-status" class="px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Status</option>
                    <option value="PROCESSING">Setujui</option>
                    <option value="CANCELLED">Batalkan</option>
                </select>
                <button onclick="applyBulkAction()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Terapkan
                </button>
                <button onclick="clearSelection()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Peminjaman List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Peminjaman</h3>
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $peminjamans->firstItem() ?? 0 }} - {{ $peminjamans->lastItem() ?? 0 }} dari {{ $peminjamans->total() }} data
                </div>
            </div>
        </div>

        @if($peminjamans->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left">
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($peminjamans as $peminjaman)
                    @php
                        $priorityClass = match($peminjaman->getPriorityLevel()) {
                            'high' => 'priority-high',
                            'medium' => 'priority-medium',
                            default => 'priority-low'
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $priorityClass }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox"
                                   class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   value="{{ $peminjaman->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $peminjaman->namaPeminjam }}</div>
                                    <div class="text-sm text-gray-500">{{ $peminjaman->noHp }}</div>
                                    <div class="mt-1">
                                        <span class="status-badge {{ $peminjaman->is_mahasiswa_usk ? 'borrower-usk' : 'borrower-external' }}">
                                            {{ $peminjaman->borrower_type }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div><i class="fas fa-play text-green-500 mr-1"></i>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                                <div><i class="fas fa-stop text-red-500 mr-1"></i>{{ $peminjaman->tanggal_pengembalian->format('d M Y') }}</div>
                            </div>
                            @if($peminjaman->is_overdue)
                                <div class="text-xs text-red-600 font-medium mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat {{ abs($peminjaman->days_until_return) }} hari
                                </div>
                            @elseif($peminjaman->status === 'PROCESSING' && $peminjaman->days_until_return <= 2)
                                <div class="text-xs text-yellow-600 font-medium mt-1">
                                    <i class="fas fa-clock mr-1"></i>Jatuh tempo {{ $peminjaman->days_until_return }} hari
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $peminjaman->total_types }} jenis, {{ $peminjaman->total_quantity }} unit</div>
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                    {{ $peminjaman->getEquipmentSummaryText() }}
                                </div>
                                @if($peminjaman->tujuanPeminjaman)
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                    <i class="fas fa-info-circle mr-1"></i>{{ $peminjaman->tujuanPeminjaman }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge status-{{ $peminjaman->status }}">
                                <i class="fas fa-{{ $peminjaman->getStatusIcon() }} mr-1"></i>
                                {{ $peminjaman->status_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.peminjaman.show', $peminjaman) }}"
                                   class="text-blue-600 hover:text-blue-900 transition">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($peminjaman->canBeApproved())
                                <button onclick="updateStatus('{{ $peminjaman->id }}', 'PROCESSING')"
                                        class="text-green-600 hover:text-green-900 transition"
                                        title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif

                                @if($peminjaman->canBeCompleted())
                                <button onclick="showCompleteModal('{{ $peminjaman->id }}')"
                                        class="text-blue-600 hover:text-blue-900 transition"
                                        title="Selesaikan">
                                    <i class="fas fa-check-double"></i>
                                </button>
                                @endif

                                @if($peminjaman->canBeCancelled())
                                <button onclick="updateStatus('{{ $peminjaman->id }}', 'CANCELLED')"
                                        class="text-red-600 hover:text-red-900 transition"
                                        title="Batalkan">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif

                                @if(in_array($peminjaman->status, ['COMPLETED', 'CANCELLED']))
                                <button onclick="deletePeminjaman('{{ $peminjaman->id }}')"
                                        class="text-red-600 hover:text-red-900 transition"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $peminjamans->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exchange-alt text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data peminjaman</h3>
            <p class="text-gray-600">Data peminjaman akan muncul di sini setelah ada yang mengajukan.</p>
        </div>
        @endif
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeCompleteModal()"></div>
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Selesaikan Peminjaman</h3>
                <button onclick="closeCompleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="completeForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="COMPLETED">

                <div id="equipment-conditions" class="space-y-4 mb-6">
                    <!-- Equipment conditions will be populated by JavaScript -->
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCompleteModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i>Selesaikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let selectedRows = new Set();

// Select All functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const isChecked = this.checked;

    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
        if (isChecked) {
            selectedRows.add(checkbox.value);
        } else {
            selectedRows.delete(checkbox.value);
        }
    });

    updateBulkActions();
});

// Individual checkbox handling
document.querySelectorAll('.row-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            selectedRows.add(this.value);
        } else {
            selectedRows.delete(this.value);
        }
        updateBulkActions();
    });
});

function updateBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');

    if (selectedRows.size > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = selectedRows.size;
    } else {
        bulkActions.classList.add('hidden');
    }
}

function clearSelection() {
    selectedRows.clear();
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('select-all').checked = false;
    updateBulkActions();
}

function applyBulkAction() {
    const status = document.getElementById('bulk-status').value;
    if (!status) {
        alert('Pilih status terlebih dahulu');
        return;
    }

    if (selectedRows.size === 0) {
        alert('Pilih peminjaman terlebih dahulu');
        return;
    }

    if (confirm(`Apakah Anda yakin ingin mengubah status ${selectedRows.size} peminjaman?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.peminjaman.bulk-update") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'bulk_status';
        statusInput.value = status;
        form.appendChild(statusInput);

        selectedRows.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'peminjaman_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
}

function updateStatus(peminjamanId, status) {
    let message = '';
    switch(status) {
        case 'PROCESSING':
            message = 'Apakah Anda yakin ingin menyetujui peminjaman ini?';
            break;
        case 'CANCELLED':
            message = 'Apakah Anda yakin ingin membatalkan peminjaman ini?';
            break;
        default:
            message = 'Apakah Anda yakin ingin mengubah status peminjaman ini?';
    }

    if (confirm(message)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/peminjaman/${peminjamanId}/status`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function showCompleteModal(peminjamanId) {
    // Fetch peminjaman details and show modal
    fetch(`/admin/peminjaman/${peminjamanId}`)
        .then(response => response.text())
        .then(html => {
            // Parse the response to get equipment list
            // This is a simplified version - you might want to create a separate API endpoint
            document.getElementById('completeForm').action = `/admin/peminjaman/${peminjamanId}/status`;
            document.getElementById('completeModal').classList.remove('hidden');
        });
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}

function deletePeminjaman(peminjamanId) {
    if (confirm('Apakah Anda yakin ingin menghapus data peminjaman ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/peminjaman/${peminjamanId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
