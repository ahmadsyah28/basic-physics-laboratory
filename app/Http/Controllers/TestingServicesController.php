<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestingServicesController extends Controller
{
    public function index()
    {
        $testingServices = [
            [
                'id' => 1,
                'name' => 'Analisis Spektroskopi UV-Vis',
                'category' => 'Spektroskopi',
                'image' => 'spectroscopy-uv.jpeg',
                'duration' => '2-3 hari kerja',
                'description' => 'Analisis kualitatif dan kuantitatif sampel menggunakan spektrofotometer UV-Vis untuk identifikasi senyawa dan penentuan konsentrasi.',
                'applications' => [
                    'Analisis konsentrasi larutan',
                    'Identifikasi senyawa organik',
                    'Uji kemurnian material',
                    'Studi kinetika reaksi',
                    'Quality control produk'
                ],
                'sample_requirements' => [
                    'Sampel cair atau dapat dilarutkan',
                    'Volume minimum 10 mL',
                    'Konsentrasi sesuai rentang deteksi',
                    'Bebas dari partikel tersuspensi'
                ],
                'icon' => 'fas fa-chart-line',
                'available' => true
            ],
            [
                'id' => 2,
                'name' => 'Pengujian Sifat Mekanik Material',
                'category' => 'Mekanik',
                'image' => 'mechanical-testing.jpg',
                'duration' => '3-5 hari kerja',
                'description' => 'Pengujian sifat mekanik material meliputi uji tarik, tekan, lentur, dan kekerasan sesuai standar ASTM dan ISO.',
                'applications' => [
                    'Uji tarik (tensile test)',
                    'Uji tekan (compression test)',
                    'Uji lentur (flexural test)',
                    'Uji kekerasan (hardness test)',
                    'Analisis struktur mikro'
                ],
                'sample_requirements' => [
                    'Dimensi sesuai standar pengujian',
                    'Permukaan rata dan bersih',
                    'Jumlah sampel minimum 3 buah',
                    'Material tidak mudah hancur'
                ],
                'icon' => 'fas fa-weight-hanging',
                'available' => true
            ],
            [
                'id' => 3,
                'name' => 'Karakterisasi Termal DSC/TGA',
                'category' => 'Termal',
                'image' => 'thermal-analysis.jpeg',
                'duration' => '3-4 hari kerja',
                'description' => 'Analisis sifat termal material menggunakan DSC (Differential Scanning Calorimetry) dan TGA (Thermogravimetric Analysis).',
                'applications' => [
                    'Penentuan titik leleh',
                    'Analisis stabilitas termal',
                    'Studi transisi fase',
                    'Karakterisasi polimer',
                    'Analisis dekomposisi'
                ],
                'sample_requirements' => [
                    'Massa sampel 5-20 mg',
                    'Sampel homogen',
                    'Tahan suhu tinggi (untuk TGA)',
                    'Tidak mengandung air berlebih'
                ],
                'icon' => 'fas fa-thermometer-half',
                'available' => true
            ],
            [
                'id' => 4,
                'name' => 'Analisis Struktur Kristal XRD',
                'category' => 'Difraksi',
                'image' => 'xrd-analysis.jpeg',
                'duration' => '4-6 hari kerja',
                'description' => 'Analisis struktur kristal dan identifikasi fase menggunakan X-Ray Diffraction (XRD) untuk material kristalin dan amorf.',
                'applications' => [
                    'Identifikasi fase kristal',
                    'Analisis ukuran kristal',
                    'Penentuan parameter kisi',
                    'Studi struktur material',
                    'Quality control mineral'
                ],
                'sample_requirements' => [
                    'Sampel serbuk halus (<75 μm)',
                    'Massa minimum 1 gram',
                    'Kristalinitas memadai',
                    'Bebas dari kontaminasi'
                ],
                'icon' => 'fas fa-gem',
                'available' => false
            ],
            [
                'id' => 5,
                'name' => 'Pengujian Konduktivitas Listrik',
                'category' => 'Elektronik',
                'image' => 'conductivity-test.jpeg',
                'duration' => '1-2 hari kerja',
                'description' => 'Pengukuran konduktivitas dan resistivitas listrik material pada berbagai kondisi suhu dan frekuensi.',
                'applications' => [
                    'Pengukuran resistivitas',
                    'Karakterisasi konduktor',
                    'Analisis semikonduktor',
                    'Uji material isolator',
                    'Studi temperatur koefisien'
                ],
                'sample_requirements' => [
                    'Dimensi dapat dipasang probe',
                    'Permukaan kontak bersih',
                    'Ketebalan minimum 1 mm',
                    'Material kering'
                ],
                'icon' => 'fas fa-bolt',
                'available' => true
            ],
            [
                'id' => 6,
                'name' => 'Analisis Komposisi FTIR',
                'category' => 'Spektroskopi',
                'image' => 'ftir-analysis.jpeg',
                'duration' => '2-3 hari kerja',
                'description' => 'Identifikasi gugus fungsi dan analisis komposisi material menggunakan Fourier Transform Infrared (FTIR) spectroscopy.',
                'applications' => [
                    'Identifikasi gugus fungsi',
                    'Analisis polimer',
                    'Deteksi kontaminan',
                    'Studi interaksi molekul',
                    'Quality control material'
                ],
                'sample_requirements' => [
                    'Sampel padat atau cair',
                    'Jumlah minimal 100 mg',
                    'Bebas air (untuk analisis padat)',
                    'Tidak mengandung logam'
                ],
                'icon' => 'fas fa-wave-square',
                'available' => true
            ]
        ];

        $categories = [
            'all' => 'Semua Kategori',
            'Spektroskopi' => 'Spektroskopi',
            'Mekanik' => 'Mekanik',
            'Termal' => 'Termal',
            'Difraksi' => 'Difraksi',
            'Elektronik' => 'Elektronik'
        ];

        return view('services.testing-services', compact('testingServices', 'categories'));
    }

    public function show($id)
    {
        $testingService = $this->getTestingServiceById($id);

        if (!$testingService) {
            abort(404);
        }

        return view('services.testing-detail', compact('testingService'));
    }

    public function requestTest(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'sample_description' => 'required|string',
            'test_requirements' => 'required|string',
            'expected_date' => 'required|date|after_or_equal:today'
        ]);

        // Logic untuk menyimpan permintaan pengujian
        // Bisa ditambahkan penyimpanan ke database dan kirim email
        // Admin akan mengonfirmasi harga setelah evaluasi permintaan

        return redirect()->back()->with('success', 'Permintaan pengujian berhasil dikirim. Admin akan menghubungi Anda untuk konfirmasi jadwal dan informasi biaya.');
    }

    private function getTestingServiceById($id)
    {
        $testingServices = $this->getAllTestingServices();
        return collect($testingServices)->where('id', $id)->first();
    }

    private function getAllTestingServices()
    {
        return [
            [
                'id' => 1,
                'name' => 'Analisis Spektroskopi UV-Vis',
                'category' => 'Spektroskopi',
                'image' => 'spectroscopy-uv.jpeg',
                'duration' => '2-3 hari kerja',
                'description' => 'Analisis kualitatif dan kuantitatif sampel menggunakan spektrofotometer UV-Vis untuk identifikasi senyawa dan penentuan konsentrasi.',
                'applications' => [
                    'Analisis konsentrasi larutan',
                    'Identifikasi senyawa organik',
                    'Uji kemurnian material',
                    'Studi kinetika reaksi',
                    'Quality control produk'
                ],
                'sample_requirements' => [
                    'Sampel cair atau dapat dilarutkan',
                    'Volume minimum 10 mL',
                    'Konsentrasi sesuai rentang deteksi',
                    'Bebas dari partikel tersuspensi'
                ],
                'icon' => 'fas fa-chart-line',
                'available' => true
            ],
            [
                'id' => 2,
                'name' => 'Pengujian Sifat Mekanik Material',
                'category' => 'Mekanik',
                'image' => 'mechanical-testing.jpg',
                'duration' => '3-5 hari kerja',
                'description' => 'Pengujian sifat mekanik material meliputi uji tarik, tekan, lentur, dan kekerasan sesuai standar ASTM dan ISO.',
                'applications' => [
                    'Uji tarik (tensile test)',
                    'Uji tekan (compression test)',
                    'Uji lentur (flexural test)',
                    'Uji kekerasan (hardness test)',
                    'Analisis struktur mikro'
                ],
                'sample_requirements' => [
                    'Dimensi sesuai standar pengujian',
                    'Permukaan rata dan bersih',
                    'Jumlah sampel minimum 3 buah',
                    'Material tidak mudah hancur'
                ],
                'icon' => 'fas fa-weight-hanging',
                'available' => true
            ],
            [
                'id' => 3,
                'name' => 'Karakterisasi Termal DSC/TGA',
                'category' => 'Termal',
                'image' => 'thermal-analysis.jpeg',
                'duration' => '3-4 hari kerja',
                'description' => 'Analisis sifat termal material menggunakan DSC (Differential Scanning Calorimetry) dan TGA (Thermogravimetric Analysis).',
                'applications' => [
                    'Penentuan titik leleh',
                    'Analisis stabilitas termal',
                    'Studi transisi fase',
                    'Karakterisasi polimer',
                    'Analisis dekomposisi'
                ],
                'sample_requirements' => [
                    'Massa sampel 5-20 mg',
                    'Sampel homogen',
                    'Tahan suhu tinggi (untuk TGA)',
                    'Tidak mengandung air berlebih'
                ],
                'icon' => 'fas fa-thermometer-half',
                'available' => true
            ],
            [
                'id' => 4,
                'name' => 'Analisis Struktur Kristal XRD',
                'category' => 'Difraksi',
                'image' => 'xrd-analysis.jpeg',
                'duration' => '4-6 hari kerja',
                'description' => 'Analisis struktur kristal dan identifikasi fase menggunakan X-Ray Diffraction (XRD) untuk material kristalin dan amorf.',
                'applications' => [
                    'Identifikasi fase kristal',
                    'Analisis ukuran kristal',
                    'Penentuan parameter kisi',
                    'Studi struktur material',
                    'Quality control mineral'
                ],
                'sample_requirements' => [
                    'Sampel serbuk halus (<75 μm)',
                    'Massa minimum 1 gram',
                    'Kristalinitas memadai',
                    'Bebas dari kontaminasi'
                ],
                'icon' => 'fas fa-gem',
                'available' => false
            ],
            [
                'id' => 5,
                'name' => 'Pengujian Konduktivitas Listrik',
                'category' => 'Elektronik',
                'image' => 'conductivity-test.jpeg',
                'duration' => '1-2 hari kerja',
                'description' => 'Pengukuran konduktivitas dan resistivitas listrik material pada berbagai kondisi suhu dan frekuensi.',
                'applications' => [
                    'Pengukuran resistivitas',
                    'Karakterisasi konduktor',
                    'Analisis semikonduktor',
                    'Uji material isolator',
                    'Studi temperatur koefisien'
                ],
                'sample_requirements' => [
                    'Dimensi dapat dipasang probe',
                    'Permukaan kontak bersih',
                    'Ketebalan minimum 1 mm',
                    'Material kering'
                ],
                'icon' => 'fas fa-bolt',
                'available' => true
            ],
            [
                'id' => 6,
                'name' => 'Analisis Komposisi FTIR',
                'category' => 'Spektroskopi',
                'image' => 'ftir-analysis.jpeg',
                'duration' => '2-3 hari kerja',
                'description' => 'Identifikasi gugus fungsi dan analisis komposisi material menggunakan Fourier Transform Infrared (FTIR) spectroscopy.',
                'applications' => [
                    'Identifikasi gugus fungsi',
                    'Analisis polimer',
                    'Deteksi kontaminan',
                    'Studi interaksi molekul',
                    'Quality control material'
                ],
                'sample_requirements' => [
                    'Sampel padat atau cair',
                    'Jumlah minimal 100 mg',
                    'Bebas air (untuk analisis padat)',
                    'Tidak mengandung logam'
                ],
                'icon' => 'fas fa-wave-square',
                'available' => true
            ]
        ];
    }
}
