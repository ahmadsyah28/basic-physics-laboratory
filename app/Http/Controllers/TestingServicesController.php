<?php

namespace App\Http\Controllers;

use App\Models\JenisPengujian;
use App\Models\Pengujian;
use App\Models\PengujianItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TestingServicesController extends Controller
{
    public function index()
    {
        $testingServices = JenisPengujian::orderBy('nama_pengujian')->get();
        return view('services.testing-services', compact('testingServices'));
    }

    public function show($id)
    {
        $testingService = JenisPengujian::findOrFail($id);
        return view('services.testing-detail', compact('testingService'));
    }

    public function requestTest(Request $request)
    {
        // Debug request data
        Log::info('Testing request data:', $request->all());

        // Parse JSON strings if they come from the new modal format
        $selectedTests = $request->selected_tests;
        $jumlahSampel = $request->jumlah_sampel;

        if (is_string($selectedTests)) {
            $selectedTests = json_decode($selectedTests, true);
        }

        if (is_string($jumlahSampel)) {
            $jumlahSampel = json_decode($jumlahSampel, true);
        }

        $request->merge([
            'selected_tests' => $selectedTests,
            'jumlah_sampel' => $jumlahSampel
        ]);

        $request->validate([
            'nama_penguji' => 'required|string|max:255',
            'organisasi_penguji' => 'required|string|max:255',
            'email_penguji' => 'required|email',
            'no_hp_penguji' => 'required|string|max:15',
            'deskripsi_sampel' => 'required|string',
            'deskripsi' => 'required|string',
            'tanggal_diharapkan' => 'required|date|after_or_equal:today',
            'selected_tests' => 'required|array|min:1',
            'selected_tests.*' => 'exists:jenis_pengujian,id',
            'jumlah_sampel' => 'required|array',
            'jumlah_sampel.*' => 'integer|min:1|max:10'
        ], [
            'selected_tests.required' => 'Silakan pilih minimal satu jenis pengujian.',
            'selected_tests.array' => 'Data pengujian tidak valid.',
            'selected_tests.min' => 'Silakan pilih minimal satu jenis pengujian.',
            'jumlah_sampel.required' => 'Jumlah sampel harus diisi.',
            'jumlah_sampel.*.max' => 'Maksimal 10 sampel per jenis pengujian.',
            'nama_penguji.required' => 'Nama lengkap harus diisi.',
            'organisasi_penguji.required' => 'Institusi/Organisasi harus diisi.',
            'email_penguji.required' => 'Email harus diisi.',
            'email_penguji.email' => 'Format email tidak valid.',
            'no_hp_penguji.required' => 'No. telepon harus diisi.',
            'tanggal_diharapkan.required' => 'Tanggal diharapkan harus diisi.',
            'tanggal_diharapkan.after_or_equal' => 'Tanggal diharapkan tidak boleh sebelum hari ini.',
            'deskripsi_sampel.required' => 'Deskripsi sampel harus diisi.',
            'deskripsi.required' => 'Kebutuhan pengujian harus diisi.'
        ]);

        try {
            DB::beginTransaction();

            // Hitung total harga
            $totalHarga = 0;
            foreach ($selectedTests as $testId) {
                $jenisPengujian = JenisPengujian::find($testId);
                if (!$jenisPengujian) {
                    throw new \Exception("Jenis pengujian dengan ID {$testId} tidak ditemukan.");
                }

                $quantity = $jumlahSampel[$testId] ?? 1;
                $totalHarga += $jenisPengujian->harga_per_sampel * $quantity;
            }

            // Buat pengujian baru
            $pengujian = Pengujian::create([
                'nama_penguji' => $request->nama_penguji,
                'organisasi_penguji' => $request->organisasi_penguji,
                'email_penguji' => $request->email_penguji,
                'no_hp_penguji' => $request->no_hp_penguji,
                'deskripsi_sampel' => $request->deskripsi_sampel,
                'deskripsi' => $request->deskripsi,
                'tanggal_diharapkan' => $request->tanggal_diharapkan,
                'total_harga' => $totalHarga,
                'status' => 'PENDING'
            ]);

            // Buat pengujian items
            foreach ($selectedTests as $testId) {
                PengujianItem::create([
                    'jenis_pengujian_id' => $testId,
                    'pengujian_id' => $pengujian->id,
                    'jumlah_sampel' => $jumlahSampel[$testId] ?? 1
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success',
                "Permintaan pengujian berhasil dikirim!\n\n" .
                "Detail Permintaan:\n" .
                "• " . count($selectedTests) . " jenis pengujian\n" .
                "• " . array_sum($jumlahSampel) . " total sampel\n" .
                "• Estimasi biaya: Rp " . number_format($totalHarga, 0, ',', '.') . "\n\n" .
                "Admin akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi jadwal dan biaya final."
            );

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in testing request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim permintaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getScheduleAvailability(Request $request)
    {
        $date = $request->get('date');

        // Simulasi jadwal tersedia (bisa disesuaikan dengan kebutuhan)
        $availableSlots = [
            '08:00', '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00', '16:00'
        ];

        // Cek jadwal yang sudah terboking untuk tanggal tersebut
        $bookedSlots = Pengujian::whereDate('tanggal_pengujian', $date)
            ->where('status', '!=', 'CANCELLED')
            ->pluck('tanggal_pengujian')
            ->map(function($datetime) {
                return $datetime->format('H:i');
            })
            ->toArray();

        $freeSlots = array_diff($availableSlots, $bookedSlots);

        return response()->json([
            'available_slots' => array_values($freeSlots)
        ]);
    }
}
