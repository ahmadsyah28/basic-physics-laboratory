<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VisitSchedulingController extends Controller
{
    /**
     * Display visit scheduling form
     */
    public function index()
    {
        // Available time slots - will be filtered by JavaScript
        $timeSlots = [
            '08:00' => '08:00 - 09:00',
            '09:00' => '09:00 - 10:00',
            '10:00' => '10:00 - 11:00',
            '11:00' => '11:00 - 12:00',
            '13:00' => '13:00 - 14:00',
            '14:00' => '14:00 - 15:00',
            '15:00' => '15:00 - 16:00',
        ];

        return view('services.visit-scheduling', compact('timeSlots'));
    }

    /**
     * Store visit scheduling request
     */
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'institution' => 'required|string|max:255',
            'visit_purpose' => 'required|string',
            'visit_date' => 'required|date|after:today',
            'visit_time' => 'required|string',
            'participant_count' => 'required|integer|min:1|max:50',
            'additional_notes' => 'nullable|string|max:1000',
            'terms_accepted' => 'required|accepted',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'phone.required' => 'Nomor telepon wajib diisi',
            'institution.required' => 'Institusi/organisasi wajib diisi',
            'visit_purpose.required' => 'Tujuan kunjungan wajib diisi',
            'visit_date.required' => 'Tanggal kunjungan wajib diisi',
            'visit_date.after' => 'Tanggal kunjungan harus setelah hari ini',
            'visit_time.required' => 'Waktu kunjungan wajib dipilih',
            'participant_count.required' => 'Jumlah peserta wajib diisi',
            'participant_count.min' => 'Jumlah peserta minimal 1 orang',
            'participant_count.max' => 'Jumlah peserta maksimal 50 orang',
            'terms_accepted.required' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.');
        }

        // Validate that the selected date is not a weekend
        $selectedDate = Carbon::parse($request->visit_date);
        if ($selectedDate->isSunday()) {
            return back()
                ->withInput()
                ->with('error', 'Laboratorium tutup pada hari Minggu. Silakan pilih hari lain.');
        }

        // Check if the time slot is available
        if (!Kunjungan::isTimeSlotAvailable($request->visit_date, $request->visit_time)) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal waktu yang dipilih sudah terisi. Silakan pilih waktu lain.');
        }

        try {
            DB::beginTransaction();

            // Create visit request
            $kunjungan = Kunjungan::create([
                'namaPengunjung' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'institution' => $request->institution,
                'tujuan' => $this->getVisitPurposeText($request->visit_purpose),
                'tanggal_kunjungan' => $request->visit_date,
                'waktu_kunjungan' => $request->visit_time,
                'jumlahPengunjung' => $request->participant_count,
                'catatan_tambahan' => $request->additional_notes,
                'status' => Kunjungan::STATUS_PENDING
            ]);

            // Send notification email
            $this->sendNotificationEmail($kunjungan);

            DB::commit();

            return back()->with('success',
                'Permintaan jadwal kunjungan berhasil dikirim! Tim kami akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi. Nomor referensi: ' . substr($kunjungan->id, 0, 8)
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.');
        }
    }

    /**
     * Get available time slots for a specific date (AJAX endpoint)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal tidak valid'
            ], 400);
        }

        try {
            // Check if date is valid and not in the past
            $selectedDate = Carbon::parse($date);
            if ($selectedDate->isPast() || $selectedDate->isToday()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal harus setelah hari ini'
                ], 400);
            }

            // Check if it's Sunday
            if ($selectedDate->isSunday()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laboratorium tutup pada hari Minggu'
                ], 400);
            }

            $availableSlots = Kunjungan::getAvailableTimeSlots($date);

            return response()->json([
                'success' => true,
                'slots' => $availableSlots,
                'date' => $selectedDate->format('d M Y')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jadwal'
            ], 500);
        }
    }

    /**
     * Send notification email
     */
    private function sendNotificationEmail($kunjungan)
    {
        try {
            
        } catch (\Exception $e) {
            // Log the error but don't break the flow
            Log::error('Failed to send notification email: ' . $e->getMessage());
        }
    }

    /**
     * Convert visit purpose code to readable text
     */
    private function getVisitPurposeText($purpose)
    {
        $purposes = [
            'educational_visit' => 'Kunjungan Edukasi',
            'research_collaboration' => 'Kolaborasi Penelitian',
            'facility_tour' => 'Tour Fasilitas',
            'academic_visit' => 'Kunjungan Akademik',
            'other' => 'Lainnya'
        ];

        return $purposes[$purpose] ?? $purpose;
    }

    /**
     * Get visit schedule for admin (optional - for future admin panel)
     */
    public function getSchedule(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $visits = Kunjungan::forDate($date)
                          ->orderBy('waktu_kunjungan')
                          ->get();

        return response()->json([
            'success' => true,
            'visits' => $visits,
            'date' => $date
        ]);
    }
}
