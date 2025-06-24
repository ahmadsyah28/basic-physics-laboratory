<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alat;
use Illuminate\Support\Str;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alats = [
            [
                'nama' => 'Oscilloscope Digital',
                'model' => 'Tektronix TBS1052B',
                'deskripsi' => 'Oscilloscope digital 50MHz dengan 2 channel untuk analisis sinyal elektronik dan pengukuran gelombang.',
                'stok' => 5,
                'isBroken' => false,
                'kategori' => 'Elektronika',
                'gambar' => 'oscilloscope.jpeg',
                'spesifikasi' => [
                    'Bandwidth: 50 MHz',
                    'Sample Rate: 1 GS/s',
                    'Channels: 2',
                    'Display: 7 inch Color TFT',
                    'Memory Depth: 2.5k points'
                ],
                'durasi_pinjam' => '1-7 hari',
                'icon' => 'fas fa-wave-square'
            ],
            [
                'nama' => 'Multimeter Digital',
                'model' => 'Fluke 87V',
                'deskripsi' => 'Multimeter digital presisi tinggi untuk pengukuran tegangan, arus, resistansi, dan parameter listrik lainnya.',
                'stok' => 10,
                'isBroken' => false,
                'kategori' => 'Pengukuran',
                'gambar' => 'multimeter.jpeg',
                'spesifikasi' => [
                    'DC Voltage: 0.1 mV - 1000 V',
                    'AC Voltage: 0.1 mV - 750 V',
                    'DC Current: 0.01 mA - 10 A',
                    'Resistance: 0.1 Ω - 50 MΩ',
                    'Frequency: 0.5 Hz - 200 kHz'
                ],

                'durasi_pinjam' => '1-14 hari',
                'icon' => 'fas fa-tachometer-alt'
            ],
            [
                'nama' => 'Function Generator',
                'model' => 'Rigol DG1032Z',
                'deskripsi' => 'Function generator 30MHz untuk menghasilkan berbagai bentuk gelombang sinusoidal, kotak, dan segitiga.',
                'stok' => 3,
                'isBroken' => false,
                'kategori' => 'Generator',
                'gambar' => 'function-generator.jpeg',
                'spesifikasi' => [
                    'Frequency Range: 1 μHz - 30 MHz',
                    'Waveforms: Sine, Square, Triangle, Pulse',
                    'Amplitude: 1 mVpp - 10 Vpp',
                    'Channels: 2',
                    'Arbitrary Waveform: 14-bit, 125 MSa/s'
                ],
                'durasi_pinjam' => '1-7 hari',
                'icon' => 'fas fa-broadcast-tower'
            ],
            [
                'nama' => 'Power Supply DC',
                'model' => 'Keysight E3631A',
                'deskripsi' => 'Power supply DC triple output dengan regulasi tinggi untuk berbagai kebutuhan eksperimen elektronika.',
                'stok' => 4,
                'isBroken' => true,
                'kategori' => 'Power',
                'gambar' => 'power-supply.jpeg',
                'spesifikasi' => [
                    'Output 1: 0-6V, 0-5A',
                    'Output 2: 0-25V, 0-1A',
                    'Output 3: 0-25V, 0-1A',
                    'Regulation: ±0.01%',
                    'Ripple: <1 mVrms'
                ],
                'durasi_pinjam' => '1-7 hari',
                'icon' => 'fas fa-plug'
            ],
            [
                'nama' => 'Spektrum Analyzer',
                'model' => 'Rohde & Schwarz FSW-B',
                'deskripsi' => 'Spektrum analyzer untuk analisis frekuensi dan karakteristik sinyal RF dengan akurasi tinggi.',
                'stok' => 2,
                'isBroken' => false,
                'kategori' => 'Analisis',
                'gambar' => 'spectrum-analyzer.jpg',
                'spesifikasi' => [
                    'Frequency Range: 2 Hz - 26.5 GHz',
                    'Resolution Bandwidth: 0.1 Hz - 50 MHz',
                    'Dynamic Range: >70 dB',
                    'Phase Noise: -136 dBc/Hz',
                    'Display: 12.1" Touchscreen'
                ],
                'durasi_pinjam' => '1-5 hari',
                'icon' => 'fas fa-chart-line'
            ],
            [
                'nama' => 'Digital Caliper',
                'model' => 'Mitutoyo 500-196-30',
                'deskripsi' => 'Jangka sorong digital presisi tinggi untuk pengukuran dimensi dengan akurasi 0.01mm.',
                'stok' => 15,
                'isBroken' => false,
                'kategori' => 'Pengukuran',
                'gambar' => 'digital-caliper.png',
                'spesifikasi' => [
                    'Range: 0-150 mm',
                    'Resolution: 0.01 mm',
                    'Accuracy: ±0.02 mm',
                    'Battery Life: 3.8 years',
                    'IP67 Protection'
                ],
                
                'durasi_pinjam' => '1-30 hari',
                'icon' => 'fas fa-ruler-combined'
            ]
        ];

        foreach ($alats as $alatData) {
            Alat::create($alatData);
        }
    }
}
