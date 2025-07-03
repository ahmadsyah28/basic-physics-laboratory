@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent-color);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.blue {
        --accent-color: #2563eb;
    }

    .stat-card.emerald {
        --accent-color: #059669;
    }

    .stat-card.purple {
        --accent-color: #7c3aed;
    }

    .stat-card.orange {
        --accent-color: #ea580c;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 8px;
        background: #fafbfc;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-approved {
        background: #d1fae5;
        color: #059669;
    }

    .status-rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .fade-up {
        animation: fadeUp 0.6s ease-out;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .text-primary {
        color: #1e40af;
    }

    .bg-primary {
        background-color: #2563eb;
    }

    .bg-primary:hover {
        background-color: #1d4ed8;
    }

    .progress-bar {
        background: #f1f5f9;
        border-radius: 6px;
        overflow: hidden;
        height: 8px;
    }

    .progress-fill {
        height: 100%;
        transition: width 0.8s ease;
        border-radius: 6px;
    }

    .chart-container {
        height: 200px;
        display: flex;
        align-items: end;
        justify-content: space-between;
        padding: 1rem 0;
        gap: 0.5rem;
    }

    .chart-bar {
        background: linear-gradient(to top, #2563eb, #60a5fa);
        border-radius: 4px;
        min-height: 20px;
        flex: 1;
        position: relative;
        transition: all 0.3s ease;
    }

    .chart-bar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
    }

    .chart-label {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        color: #6b7280;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
    <!-- Header Section -->
    <div class="mb-8 fade-up">
        <h1 class="text-3xl font-semibold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Selamat datang di sistem manajemen Laboratorium Fisika Dasar</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Peminjaman Alat Card -->
        <div class="stat-card blue card p-6 fade-up" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Peminjaman Alat</p>
                    <p class="text-3xl font-semibold text-gray-900">
                        {{ \App\Models\Peminjaman::where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-4">Menunggu persetujuan</p>
            {{-- <a href="{{ route('admin.equipment-loan.index') }}"
               class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                Kelola peminjaman
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a> --}}
        </div>

        <!-- Pengujian Card -->
        <div class="stat-card emerald card p-6 fade-up" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pengujian</p>
                    <p class="text-3xl font-semibold text-gray-900">
                        {{ \App\Models\Pengujian::where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-flask text-emerald-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-4">Menunggu persetujuan</p>
            {{-- <a href="{{ route('admin.testing.index') }}"
               class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700">
                Kelola pengujian
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a> --}}
        </div>

        <!-- Kunjungan Card -->
        <div class="stat-card purple card p-6 fade-up" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Kunjungan</p>
                    <p class="text-3xl font-semibold text-gray-900">
                        {{ \App\Models\Kunjungan::where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-4">Menunggu persetujuan</p>
            <a href="{{ route('admin.visits.index') }}"
               class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-700">
                Kelola kunjungan
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>

        <!-- Total Peralatan Card -->
        <div class="stat-card orange card p-6 fade-up" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Peralatan</p>
                    <p class="text-3xl font-semibold text-gray-900">
                        {{ \App\Models\Alat::count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tools text-orange-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-4">Alat tersedia</p>
            <a href="{{ route('admin.equipment.index') }}"
               class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-700">
                Kelola peralatan
                <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Chart and Summary Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Activity Chart -->
        <div class="card p-6 fade-up" style="animation-delay: 0.5s">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-1">Aktivitas Bulanan</h2>
                <p class="text-sm text-gray-600">Grafik aktivitas 7 hari terakhir</p>
            </div>
            <div class="chart-container">
                @php
                    $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                    $activities = [45, 62, 38, 75, 58, 42, 35]; // Sample data
                @endphp
                @foreach($days as $index => $day)
                <div class="chart-bar" style="height: {{ $activities[$index] }}%">
                    <div class="chart-label">{{ $day }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- System Summary -->
        <div class="card p-6 fade-up" style="animation-delay: 0.6s">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-1">Ringkasan Sistem</h2>
                <p class="text-sm text-gray-600">Status dan performa sistem</p>
            </div>
            <div class="space-y-4">
                <!-- Tingkat Persetujuan -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Tingkat Persetujuan</span>
                        <span class="text-sm text-gray-500">85%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-emerald-500" style="width: 85%"></div>
                    </div>
                </div>

                <!-- Utilitas Peralatan -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Utilitas Peralatan</span>
                        <span class="text-sm text-gray-500">72%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-blue-500" style="width: 72%"></div>
                    </div>
                </div>

                <!-- Kepuasan Pengguna -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Kepuasan Pengguna</span>
                        <span class="text-sm text-gray-500">91%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-purple-500" style="width: 91%"></div>
                    </div>
                </div>

                <!-- Response Time -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Response Time</span>
                        <span class="text-sm text-gray-500">1.2 hari</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-orange-500" style="width: 78%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Loans -->
        <div class="card p-6 fade-up" style="animation-delay: 0.7s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Peminjaman Terbaru</h3>
                <i class="fas fa-exchange-alt text-blue-500"></i>
            </div>
            <div class="space-y-3">
                @forelse(\App\Models\Peminjaman::latest()->take(4)->get() as $loan)
                <div class="activity-item">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">{{ $loan->user->name ?? 'User' }}</p>
                        <p class="text-gray-500 text-xs">{{ $loan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="status-badge status-{{ $loan->status }}">
                        {{ ucfirst($loan->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-300 text-3xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada data peminjaman</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Tests -->
        <div class="card p-6 fade-up" style="animation-delay: 0.8s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pengujian Terbaru</h3>
                <i class="fas fa-flask text-emerald-500"></i>
            </div>
            <div class="space-y-3">
                @forelse(\App\Models\Pengujian::latest()->take(4)->get() as $test)
                <div class="activity-item">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">{{ $test->user->name ?? 'User' }}</p>
                        <p class="text-gray-500 text-xs">{{ $test->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="status-badge status-{{ $test->status }}">
                        {{ ucfirst($test->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-300 text-3xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada data pengujian</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple fade-in animation for stat numbers
    const statNumbers = document.querySelectorAll('.stat-card .text-3xl');

    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    statNumbers.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });

    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});
</script>
@endsection
