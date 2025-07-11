@extends("layouts.admin")

@section("title", "Admin Panel Overview")

@section("content")
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800">Admin Panel Laboratorium Fisika Dasar</h2>
    </div>

    <div class="p-6">
        <p class="text-gray-700 mb-6">
            Panel admin ini telah dibuat dengan desain modern, simple, dan profesional untuk memudahkan pengelolaan kebutuhan Laboratorium Fisika Dasar.
        </p>

        <div class="space-y-6">
            <!-- Modern Layout -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">1. Layout Admin Modern</h3>
                <div class="pl-5 border-l-2 border-indigo-500">
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Desain responsif dengan sidebar yang dapat diciutkan</li>
                        <li>Skema warna modern dan tipografi menggunakan font Inter</li>
                        <li>Navigasi yang ditingkatkan dengan indikator status aktif</li>
                        <li>Desain mobile-friendly dengan menu hamburger</li>
                        <li>Tampilan profil pengguna di sidebar</li>
                        <li>Navigasi atas untuk breadcrumbs dan tautan website</li>
                    </ul>
                </div>
            </div>

            <!-- Modern Dashboard -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">2. Dashboard Modern</h3>
                <div class="pl-5 border-l-2 border-green-500">
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Statistik berbasis kartu dengan styling modern</li>
                        <li>Grafik aktivitas menggunakan Chart.js</li>
                        <li>Integrasi kalender kunjungan mendatang</li>
                        <li>Daftar item terbaru (peminjaman, artikel)</li>
                        <li>Menu akses cepat</li>
                    </ul>
                </div>
            </div>

            <!-- Staff Management -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">3. Manajemen Staff</h3>
                <div class="pl-5 border-l-2 border-purple-500">
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Halaman indeks dengan desain tabel modern</li>
                        <li>Fungsionalitas pencarian dan filter untuk daftar staff</li>
                        <li>Form Tambah/Edit dengan field komprehensif</li>
                        <li>Layout dua kolom responsif untuk form</li>
                        <li>Fungsionalitas preview dan upload gambar</li>
                    </ul>
                </div>
            </div>

            <!-- Equipment Management -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">4. Manajemen Alat</h3>
                <div class="pl-5 border-l-2 border-amber-500">
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Halaman indeks dengan pencarian dan filter</li>
                        <li>Desain tabel modern dengan indikator status</li>
                        <li>Kategori alat dengan indikator visual</li>
                    </ul>
                </div>
            </div>

            <!-- Equipment Loan Management -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">5. Manajemen Peminjaman Alat</h3>
                <div class="pl-5 border-l-2 border-blue-500">
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Halaman indeks dengan filter dan pencarian</li>
                        <li>Indikator status dan aksi cepat</li>
                        <li>Halaman tampilan detail</li>
                        <li>Alur kerja persetujuan dan riwayat status</li>
                    </ul>
                </div>
            </div>

            <!-- Database Improvements -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">6. Peningkatan Database</h3>
                <div class="pl-5 border-l-2 border-red-500">
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Migrasi yang diperbarui untuk BiodataPengurus dengan field tambahan</li>
                        <li>Model yang ditingkatkan dengan accessor dan metode relasi</li>
                        <li>Metode controller yang diperbarui untuk operasi CRUD</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Prinsip Desain</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-gray-700">Konsistensi dalam elemen UI dan spacing</span>
                </div>
                <div class="bg-white p-4 rounded shadow-sm">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-gray-700">Aksesibilitas dengan kontras dan focus state yang tepat</span>
                </div>
                <div class="bg-white p-4 rounded shadow-sm">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-gray-700">Responsif di berbagai ukuran perangkat</span>
                </div>
                <div class="bg-white p-4 rounded shadow-sm">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-gray-700">Estetika modern yang bersih dengan bayangan halus</span>
                </div>
                <div class="bg-white p-4 rounded shadow-sm">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-gray-700">Alur kerja intuitif dengan tombol aksi yang jelas</span>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">Dibuat untuk Laboratorium Fisika Dasar</p>
        </div>
    </div>
</div>
@endsection
