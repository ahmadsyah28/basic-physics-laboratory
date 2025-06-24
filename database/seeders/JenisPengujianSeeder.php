<?php

namespace Database\Seeders;

use App\Models\JenisPengujian;
use Illuminate\Database\Seeder;

class JenisPengujianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisPengujian = [
            [
                'nama_pengujian' => 'Analisis Spektroskopi UV-Vis',
                'harga_per_sampel' => 75000,
                'is_available' => true,
                'deskripsi' => 'Analisis kualitatif dan kuantitatif sampel menggunakan spektrofotometer UV-Vis untuk identifikasi senyawa dan penentuan konsentrasi.',
                'durasi' => '2-3 hari kerja',
                'aplikasi' => [
                    'Analisis konsentrasi larutan',
                    'Identifikasi senyawa organik',
                    'Uji kemurnian material',
                    'Studi kinetika reaksi',
                    'Quality control produk'
                ],
                'icon' => 'fas fa-chart-line'
            ],
            [
                'nama_pengujian' => 'Pengujian Sifat Mekanik Material',
                'harga_per_sampel' => 150000,
                'is_available' => true,
                'deskripsi' => 'Pengujian sifat mekanik material meliputi uji tarik, tekan, lentur, dan kekerasan sesuai standar ASTM dan ISO.',
                'durasi' => '3-5 hari kerja',
                'aplikasi' => [
                    'Uji tarik (tensile test)',
                    'Uji tekan (compression test)',
                    'Uji lentur (flexural test)',
                    'Uji kekerasan (hardness test)',
                    'Analisis struktur mikro'
                ],
                'icon' => 'fas fa-weight-hanging'
            ],
            [
                'nama_pengujian' => 'Karakterisasi Termal DSC/TGA',
                'harga_per_sampel' => 120000,
                'is_available' => true,
                'deskripsi' => 'Analisis sifat termal material menggunakan DSC (Differential Scanning Calorimetry) dan TGA (Thermogravimetric Analysis).',
                'durasi' => '3-4 hari kerja',
                'aplikasi' => [
                    'Penentuan titik leleh',
                    'Analisis stabilitas termal',
                    'Studi transisi fase',
                    'Karakterisasi polimer',
                    'Analisis dekomposisi'
                ],
                'icon' => 'fas fa-thermometer-half'
            ],
            [
                'nama_pengujian' => 'Analisis Struktur Kristal XRD',
                'harga_per_sampel' => 200000,
                'is_available' => false,
                'deskripsi' => 'Analisis struktur kristal dan identifikasi fase menggunakan X-Ray Diffraction (XRD) untuk material kristalin dan amorf.',
                'durasi' => '4-6 hari kerja',
                'aplikasi' => [
                    'Identifikasi fase kristal',
                    'Analisis ukuran kristal',
                    'Penentuan parameter kisi',
                    'Studi struktur material',
                    'Quality control mineral'
                ],
                'icon' => 'fas fa-gem'
            ],
            [
                'nama_pengujian' => 'Pengujian Konduktivitas Listrik',
                'harga_per_sampel' => 50000,
                'is_available' => true,
                'deskripsi' => 'Pengukuran konduktivitas dan resistivitas listrik material pada berbagai kondisi suhu dan frekuensi.',
                'durasi' => '1-2 hari kerja',
                'aplikasi' => [
                    'Pengukuran resistivitas',
                    'Karakterisasi konduktor',
                    'Analisis semikonduktor',
                    'Uji material isolator',
                    'Studi temperatur koefisien'
                ],
                'icon' => 'fas fa-bolt'
            ],
            [
                'nama_pengujian' => 'Analisis Komposisi FTIR',
                'harga_per_sampel' => 85000,
                'is_available' => true,
                'deskripsi' => 'Identifikasi gugus fungsi dan analisis komposisi material menggunakan Fourier Transform Infrared (FTIR) spectroscopy.',
                'durasi' => '2-3 hari kerja',
                'aplikasi' => [
                    'Identifikasi gugus fungsi',
                    'Analisis polimer',
                    'Deteksi kontaminan',
                    'Studi interaksi molekul',
                    'Quality control material'
                ],
                'icon' => 'fas fa-wave-square'
            ]
        ];

        foreach ($jenisPengujian as $item) {
            JenisPengujian::create($item);
        }
    }
}
