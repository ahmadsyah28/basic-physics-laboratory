<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPengujian;
use App\Models\Pengujian;
use App\Models\PengujianItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestingServicesController extends Controller
{
    public function index()
    {
        $testingServices = JenisPengujian::orderBy('namaPengujian')->get();

        // Transform data untuk view compatibility
        $testingServices = $testingServices->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->namaPengujian,
                'image' => $service->gambar,
                'duration' => $service->durasi,
                'description' => $service->deskripsi,
                'applications' => $service->aplikasi ?? [],
                'sample_requirements' => $service->persyaratanSampel ?? [],
                'icon' => $service->icon,
                'available' => $service->isAvailable,
                'category' => $service->kategori,
                'price' => $service->hargaPerSampel,
                'formatted_price' => $service->formattedHarga
            ];
        });

        return view('services.testing-services', compact('testingServices'));
    }

    public function show($id)
    {
        $jenisPengujian = JenisPengujian::findOrFail($id);

        // Transform data untuk view compatibility
        $testingService = [
            'id' => $jenisPengujian->id,
            'name' => $jenisPengujian->namaPengujian,
            'image' => $jenisPengujian->gambar,
            'duration' => $jenisPengujian->durasi,
            'description' => $jenisPengujian->deskripsi,
            'applications' => $jenisPengujian->aplikasi ?? [],
            'sample_requirements' => $jenisPengujian->persyaratanSampel ?? [],
            'icon' => $jenisPengujian->icon,
            'available' => $jenisPengujian->isAvailable,
            'category' => $jenisPengujian->kategori,
            'price' => $jenisPengujian->hargaPerSampel,
            'formatted_price' => $jenisPengujian->formattedHarga
        ];

        return view('services.testing-detail', compact('testingService'));
    }

    public function requestTest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'sample_description' => 'required|string',
            'test_requirements' => 'required|string',
            'expected_date' => 'required|date|after_or_equal:today',
            'testing_services' => 'required|array|min:1',
            'testing_services.*' => 'exists:jenis_pengujian,id'
        ]);

        DB::beginTransaction();

        try {
            // Hitung total harga
            $selectedServices = JenisPengujian::whereIn('id', $request->testing_services)->get();
            $totalHarga = $selectedServices->sum('hargaPerSampel');

            // Buat pengujian baru
            $pengujian = Pengujian::create([
                'namaPenguji' => $request->name,
                'noHpPenguji' => $request->phone,
                'email' => $request->email,
                'institusi' => $request->organization,
                'deskripsi' => $request->test_requirements,
                'deskripsiSampel' => $request->sample_description,
                'kebutuhanPengujian' => $request->test_requirements,
                'totalHarga' => $totalHarga,
                'jadwalPengujian' => Carbon::parse($request->expected_date),
                'status' => Pengujian::STATUS_PENDING
            ]);

            // Buat pengujian items
            foreach ($request->testing_services as $serviceId) {
                PengujianItem::create([
                    'jenisPengujianId' => $serviceId,
                    'pengujianId' => $pengujian->id,
                    'jumlahSampel' => 1 // Default 1 sampel
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan pengujian berhasil dikirim. Admin akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi jadwal dan informasi biaya.',
                'pengujian_id' => $pengujian->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.'
            ], 500);
        }
    }

    public function getAvailableSlots(Request $request)
    {
        $date = Carbon::parse($request->date);

        // Ambil slot yang sudah terboking pada tanggal tersebut
        $bookedSlots = Pengujian::whereDate('jadwalPengujian', $date)
            ->whereIn('status', [Pengujian::STATUS_PENDING, Pengujian::STATUS_PROCESSING])
            ->pluck('jadwalPengujian')
            ->map(function ($datetime) {
                return Carbon::parse($datetime)->format('H:i');
            })
            ->toArray();

        // Slot waktu tersedia (08:00 - 16:00, interval 2 jam)
        $allSlots = ['08:00', '10:00', '12:00', '14:00', '16:00'];

        $availableSlots = array_filter($allSlots, function($slot) use ($bookedSlots) {
            return !in_array($slot, $bookedSlots);
        });

        return response()->json([
            'available_slots' => array_values($availableSlots),
            'booked_slots' => $bookedSlots
        ]);
    }

    public function getSchedule()
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays(30);

        $schedule = Pengujian::with(['jenisPengujian'])
            ->whereBetween('jadwalPengujian', [$startDate, $endDate])
            ->whereIn('status', [Pengujian::STATUS_PENDING, Pengujian::STATUS_PROCESSING])
            ->orderBy('jadwalPengujian')
            ->get()
            ->map(function ($pengujian) {
                return [
                    'id' => $pengujian->id,
                    'title' => $pengujian->namaPenguji . ' - ' . $pengujian->jenisPengujian->pluck('namaPengujian')->join(', '),
                    'start' => $pengujian->jadwalPengujian->format('Y-m-d H:i:s'),
                    'status' => $pengujian->status,
                    'color' => $pengujian->status === Pengujian::STATUS_PENDING ? '#f59e0b' : '#3b82f6'
                ];
            });

        return response()->json($schedule);
    }
}
