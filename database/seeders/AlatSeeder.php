<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alat;
use App\Models\KategoriAlat;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan kategori sudah ada terlebih dahulu
        $kategoris = [
            'Elektronika',
            'Pengukuran',
            'Generator',
            'Power',
            'Analisis'
        ];

        foreach ($kategoris as $kategori) {
            KategoriAlat::firstOrCreate(['nama_kategori' => $kategori]);
        }

        $alats = [
            [
                'nama' => 'Oscilloscope Digital',
                'kode' => 'OSC-001',
                'deskripsi' => 'Oscilloscope digital 50MHz dengan 2 channel untuk analisis sinyal elektronik dan pengukuran gelombang. Spesifikasi: Bandwidth 50 MHz, Sample Rate 1 GS/s, 2 Channels, Display 7 inch Color TFT, Memory Depth 2.5k points.',
                'image_url' => 'oscilloscope.jpeg',
                'jumlah_tersedia' => 4,
                'jumlah_dipinjam' => 1,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Elektronika',
                'stok' => 5,
                'harga' => 15000000.00
            ],
            [
                'nama' => 'Multimeter Digital',
                'kode' => 'DMM-001',
                'deskripsi' => 'Multimeter digital presisi tinggi untuk pengukuran tegangan, arus, resistansi, dan parameter listrik lainnya. Spesifikasi: DC Voltage 0.1 mV - 1000 V, AC Voltage 0.1 mV - 750 V, DC Current 0.01 mA - 10 A, Resistance 0.1 Ω - 50 MΩ, Frequency 0.5 Hz - 200 kHz.',
                'image_url' => 'multimeter.jpeg',
                'jumlah_tersedia' => 8,
                'jumlah_dipinjam' => 2,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Pengukuran',
                'stok' => 10,
                'harga' => 3500000.00
            ],
            [
                'nama' => 'Function Generator',
                'kode' => 'FG-001',
                'deskripsi' => 'Function generator 30MHz untuk menghasilkan berbagai bentuk gelombang sinusoidal, kotak, dan segitiga. Spesifikasi: Frequency Range 1 μHz - 30 MHz, Waveforms Sine/Square/Triangle/Pulse, Amplitude 1 mVpp - 10 Vpp, 2 Channels, Arbitrary Waveform 14-bit 125 MSa/s.',
                'image_url' => 'function-generator.jpeg',
                'jumlah_tersedia' => 3,
                'jumlah_dipinjam' => 0,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Generator',
                'stok' => 3,
                'harga' => 8500000.00
            ],
            [
                'nama' => 'Power Supply DC',
                'kode' => 'PS-001',
                'deskripsi' => 'Power supply DC triple output dengan regulasi tinggi untuk berbagai kebutuhan eksperimen elektronika. Spesifikasi: Output 1: 0-6V 0-5A, Output 2&3: 0-25V 0-1A, Regulation ±0.01%, Ripple <1 mVrms.',
                'image_url' => 'power-supply.jpeg',
                'jumlah_tersedia' => 2,
                'jumlah_dipinjam' => 1,
                'jumlah_rusak' => 1,
                'nama_kategori' => 'Power',
                'stok' => 4,
                'harga' => 12000000.00
            ],
            [
                'nama' => 'Spektrum Analyzer',
                'kode' => 'SA-001',
                'deskripsi' => 'Spektrum analyzer untuk analisis frekuensi dan karakteristik sinyal RF dengan akurasi tinggi. Spesifikasi: Frequency Range 2 Hz - 26.5 GHz, Resolution Bandwidth 0.1 Hz - 50 MHz, Dynamic Range >70 dB, Phase Noise -136 dBc/Hz, Display 12.1" Touchscreen.',
                'image_url' => 'spectrum-analyzer.jpg',
                'jumlah_tersedia' => 2,
                'jumlah_dipinjam' => 0,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Analisis',
                'stok' => 2,
                'harga' => 45000000.00
            ],
            [
                'nama' => 'Digital Caliper',
                'kode' => 'DC-001',
                'deskripsi' => 'Jangka sorong digital presisi tinggi untuk pengukuran dimensi dengan akurasi 0.01mm. Spesifikasi: Range 0-150 mm, Resolution 0.01 mm, Accuracy ±0.02 mm, Battery Life 3.8 years, IP67 Protection.',
                'image_url' => 'digital-caliper.png',
                'jumlah_tersedia' => 12,
                'jumlah_dipinjam' => 3,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Pengukuran',
                'stok' => 15,
                'harga' => 850000.00
            ],
            [
                'nama' => 'Soldering Station',
                'kode' => 'SS-001',
                'deskripsi' => 'Soldering station digital dengan kontrol temperatur untuk penyolderan komponen elektronik. Spesifikasi: Temperature Range 200-480°C, Power 60W, Digital Display, ESD Safe, Quick Heat Up.',
                'image_url' => 'soldering-station.jpeg',
                'jumlah_tersedia' => 6,
                'jumlah_dipinjam' => 2,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Elektronika',
                'stok' => 8,
                'harga' => 2500000.00
            ],
            [
                'nama' => 'Breadboard',
                'kode' => 'BB-001',
                'deskripsi' => 'Breadboard 830 tie points untuk prototyping rangkaian elektronik. Spesifikasi: 830 tie points, Self-adhesive backing, Color-coded power rails, Durable ABS plastic construction.',
                'image_url' => 'breadboard.jpeg',
                'jumlah_tersedia' => 18,
                'jumlah_dipinjam' => 2,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Elektronika',
                'stok' => 20,
                'harga' => 125000.00
            ],
            [
                'nama' => 'LCR Meter',
                'kode' => 'LCR-001',
                'deskripsi' => 'LCR Meter untuk pengukuran induktansi, kapasitansi, dan resistansi komponen elektronik. Spesifikasi: Test Frequency 100Hz-100kHz, Basic Accuracy 0.05%, 4-wire measurement, USB interface.',
                'image_url' => 'lcr-meter.jpeg',
                'jumlah_tersedia' => 3,
                'jumlah_dipinjam' => 0,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Pengukuran',
                'stok' => 3,
                'harga' => 6500000.00
            ],
            [
                'nama' => 'DC Motor Driver',
                'kode' => 'MD-001',
                'deskripsi' => 'DC Motor driver module untuk mengontrol motor DC dengan PWM control. Spesifikasi: Input Voltage 6-27V, Output Current up to 43A, PWM Frequency 1kHz-20kHz, Dual H-Bridge.',
                'image_url' => 'motor-driver.jpeg',
                'jumlah_tersedia' => 8,
                'jumlah_dipinjam' => 2,
                'jumlah_rusak' => 0,
                'nama_kategori' => 'Power',
                'stok' => 10,
                'harga' => 450000.00
            ]
        ];

        foreach ($alats as $alatData) {
            Alat::create($alatData);
        }
    }
}
