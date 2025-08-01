<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VisitSchedulingController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display visit scheduling form
     */
    public function index()
    {
        return view('services.visit-scheduling');
    }

    /**
     * Store visit scheduling request
     */
    public function store(Request $request)
    {
        // Check if request is AJAX
        $isAjax = $request->ajax() || $request->wantsJson();

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
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
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
            'document.required' => 'Dokumen surat pengajuan wajib diupload',
            'document.mimes' => 'Format file harus PDF, DOC, DOCX, JPG, JPEG, atau PNG',
            'document.max' => 'Ukuran file maksimal 5MB',
            'terms_accepted.required' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat kesalahan dalam pengisian form.',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.');
        }

        // Validate that the selected date is not a weekend
        $selectedDate = Carbon::parse($request->visit_date);
        if ($selectedDate->isSunday()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laboratorium tutup pada hari Minggu. Silakan pilih hari lain.'
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', 'Laboratorium tutup pada hari Minggu. Silakan pilih hari lain.');
        }

        // Check if the time slot is available
        if (!Kunjungan::isTimeSlotAvailable($request->visit_date, $request->visit_time)) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal waktu yang dipilih sudah terisi. Silakan pilih waktu lain.'
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', 'Jadwal waktu yang dipilih sudah terisi. Silakan pilih waktu lain.');
        }

        try {
            DB::beginTransaction();

            // Handle file upload
            $documentPath = null;
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $documentPath = $file->storeAs('visit-documents', $fileName, 'public');
            }

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
                'dokumen_surat' => $documentPath,
                'status' => Kunjungan::STATUS_PENDING
            ]);

            // Log successful creation
            Log::info('Visit request created', [
                'visit_id' => $kunjungan->id,
                'visitor_name' => $kunjungan->namaPengunjung,
                'institution' => $kunjungan->institution,
                'visit_date' => $kunjungan->tanggal_kunjungan,
                'visit_time' => $kunjungan->waktu_kunjungan
            ]);

            DB::commit();

            $referenceId = substr($kunjungan->id, 0, 8);
            $trackingUrl = route('visit.track', ['id' => $kunjungan->id]);

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan kunjungan berhasil disimpan!',
                    'reference_id' => $referenceId,
                    'tracking_url' => $trackingUrl,
                    'visit_data' => [
                        'id' => $kunjungan->id,
                        'name' => $kunjungan->namaPengunjung,
                        'institution' => $kunjungan->institution,
                        'email' => $kunjungan->email,
                        'phone' => $kunjungan->phone,
                        'visit_purpose' => $kunjungan->tujuan,
                        'visit_date' => $kunjungan->formatted_date,
                        'visit_time' => $kunjungan->formatted_time,
                        'participant_count' => $kunjungan->jumlahPengunjung,
                        'additional_notes' => $kunjungan->catatan_tambahan,
                        'document_name' => $documentPath ? basename($documentPath) : null
                    ]
                ]);
            }

            $successMessage = 'Permintaan jadwal kunjungan berhasil dikirim! Tim kami akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi. ';
            $successMessage .= 'Nomor referensi: ' . $referenceId . '. ';
            $successMessage .= 'Anda dapat memantau status pengajuan melalui link: ' . $trackingUrl;

            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if transaction failed
            if ($documentPath && Storage::disk('public')->exists($documentPath)) {
                Storage::disk('public')->delete($documentPath);
            }

            Log::error('Visit submission error: ' . $e->getMessage(), [
                'visitor_name' => $request->name,
                'institution' => $request->institution,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.');
        }
    }

    /**
     * Track visit status
     */
    public function track($id)
    {
        try {
            $kunjungan = Kunjungan::findOrFail($id);
            return view('services.visit-tracking', compact('kunjungan'));
        } catch (\Exception $e) {
            abort(404, 'Pengajuan tidak ditemukan');
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
            Log::error('Error getting available slots: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jadwal'
            ], 500);
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
     * Download document
     */
    public function downloadDocument(Kunjungan $kunjungan)
    {
        if (!$kunjungan->dokumen_surat || !Storage::disk('public')->exists($kunjungan->dokumen_surat)) {
            abort(404, 'Dokumen tidak ditemukan');
        }

        $fileName = basename($kunjungan->dokumen_surat);
        $filePath = Storage::disk('public')->path($kunjungan->dokumen_surat);

        return response()->download($filePath, $fileName);
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
