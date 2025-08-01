<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ADD THIS IMPORT
use Carbon\Carbon;

class AdminVisitController extends Controller
{
    /**
     * Display a listing of visits
     */
    public function index(Request $request)
    {
        $query = Kunjungan::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('tanggal_kunjungan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal_kunjungan', '<=', $request->end_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('namaPengunjung', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }

        // Order by priority: PENDING first, then by date
        $visits = $query->orderByRaw("CASE
                            WHEN status = 'PENDING' THEN 1
                            WHEN status = 'PROCESSING' THEN 2
                            WHEN status = 'COMPLETED' THEN 3
                            WHEN status = 'CANCELLED' THEN 4
                            ELSE 5 END")
                       ->orderBy('tanggal_kunjungan', 'asc')
                       ->orderBy('waktu_kunjungan', 'asc')
                       ->paginate(15);

        $statistics = Kunjungan::getStatistics();
        $statuses = Kunjungan::getAllStatuses();

        return view('admin.visits.index', compact('visits', 'statistics', 'statuses'));
    }

    /**
     * Display the specified visit
     */
    public function show(Kunjungan $kunjungan)
{
    // Get all available statuses for dropdown
    $statuses = Kunjungan::getAllStatuses();

    return view('admin.visits.show', compact('kunjungan', 'statuses'));
}

    /**
     * Show the form for editing the specified visit
     */
    public function edit(Kunjungan $kunjungan)
    {
        $statuses = Kunjungan::getAllStatuses();
        $timeSlots = Kunjungan::getDefaultTimeSlots();

        return view('admin.visits.edit', compact('kunjungan', 'statuses', 'timeSlots'));
    }

    /**
     * Update the specified visit
     */
    public function update(Request $request, Kunjungan $kunjungan)
    {
        $validator = Validator::make($request->all(), [
            'namaPengunjung' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'institution' => 'required|string|max:255',
            'tujuan' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required|string',
            'jumlahPengunjung' => 'required|integer|min:1|max:50',
            'catatan_tambahan' => 'nullable|string|max:1000',
            'status' => 'required|in:' . implode(',', array_keys(Kunjungan::getAllStatuses())),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if time slot is available (only if date/time changed and status is pending/processing)
        $dateTimeChanged = ($kunjungan->tanggal_kunjungan != $request->tanggal_kunjungan ||
                           $kunjungan->waktu_kunjungan != $request->waktu_kunjungan);

        if ($dateTimeChanged && in_array($request->status, [Kunjungan::STATUS_PENDING, Kunjungan::STATUS_PROCESSING])) {
            $available = Kunjungan::where('tanggal_kunjungan', $request->tanggal_kunjungan)
                                 ->where('waktu_kunjungan', $request->waktu_kunjungan)
                                 ->whereIn('status', [Kunjungan::STATUS_PENDING, Kunjungan::STATUS_PROCESSING])
                                 ->where('id', '!=', $kunjungan->id)
                                 ->doesntExist();

            if (!$available) {
                return back()->withInput()
                           ->with('error', 'Jadwal waktu yang dipilih sudah terisi. Silakan pilih waktu lain.');
            }
        }

        $kunjungan->update($request->all());

        return redirect()->route('admin.visits.index')
                        ->with('success', 'Kunjungan berhasil diperbarui.');
    }

    /**
     * Update visit status with approval workflow - FIXED VERSION
     */
    public function updateStatus(Request $request, Kunjungan $kunjungan)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', array_keys(Kunjungan::getAllStatuses())),
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $kunjungan->status;
        $newStatus = $request->status;

        // Log the request for debugging
        Log::info('Status update request', [
            'visit_id' => $kunjungan->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'reason' => $request->reason
        ]);

        try {
            DB::beginTransaction();

            // Business logic for status transitions
            if ($oldStatus === 'PENDING' && $newStatus === 'PROCESSING') {
                // Approve the visit - check slot availability
                if (!Kunjungan::isTimeSlotAvailable($kunjungan->tanggal_kunjungan, $kunjungan->waktu_kunjungan, $kunjungan->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Slot waktu sudah terisi. Tidak dapat menyetujui kunjungan ini.'
                    ], 422);
                }
            }

            // Prepare update data
            $updateData = ['status' => $newStatus];

            // Add rejection reason if cancelling
            if ($newStatus === 'CANCELLED' && $request->reason) {
                $additionalNote = "Alasan penolakan: " . $request->reason;
                $updateData['catatan_tambahan'] = $kunjungan->catatan_tambahan
                    ? $kunjungan->catatan_tambahan . "\n\n" . $additionalNote
                    : $additionalNote;
            }

            // Update the visit
            $kunjungan->update($updateData);

            DB::commit();

            // Log successful status change
            Log::info("Visit status updated successfully", [
                'visit_id' => $kunjungan->id,
                'visitor_name' => $kunjungan->namaPengunjung,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_by' => Auth::user()->name ?? 'System'
            ]);

            // Status messages
            $statusMessages = [
                'PENDING' => 'Status kunjungan dikembalikan ke menunggu',
                'PROCESSING' => 'Kunjungan telah disetujui dan sedang diproses',
                'COMPLETED' => 'Kunjungan telah diselesaikan',
                'CANCELLED' => 'Kunjungan telah ditolak'
            ];

            // Refresh model to get updated attributes
            $kunjungan->refresh();

            return response()->json([
                'success' => true,
                'message' => $statusMessages[$newStatus] ?? 'Status kunjungan berhasil diperbarui.',
                'newStatus' => $kunjungan->statusText,
                'badgeColor' => $kunjungan->statusBadgeColor,
                'data' => [
                    'status' => $kunjungan->status,
                    'status_text' => $kunjungan->statusText,
                    'badge_color' => $kunjungan->statusBadgeColor
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating visit status', [
                'visit_id' => $kunjungan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Remove the specified visit - FIXED VERSION
     */
    public function destroy(Kunjungan $kunjungan)
    {
        try {
            // Delete associated document if exists
            if ($kunjungan->dokumen_surat && Storage::disk('public')->exists($kunjungan->dokumen_surat)) {
                Storage::disk('public')->delete($kunjungan->dokumen_surat);
            }

            $kunjungan->delete();

            Log::info('Visit deleted', [
                'visit_id' => $kunjungan->id,
                'visitor_name' => $kunjungan->namaPengunjung,
                'deleted_by' => Auth::user()->name ?? 'System'
            ]);

            return redirect()->route('admin.visits.index')
                            ->with('success', 'Kunjungan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting visit', [
                'visit_id' => $kunjungan->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menghapus kunjungan.');
        }
    }

    /**
     * Download document from admin panel
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
     * Display calendar view
     */
    public function calendar()
    {
        $statuses = Kunjungan::getAllStatuses();
        return view('admin.visits.calendar', compact('statuses'));
    }

    /**
     * Get calendar data
     */
    public function getCalendarData(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $events = Kunjungan::getCalendarEvents($start, $end);

        return response()->json($events);
    }

    /**
     * Get available time slots for a date (AJAX)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date');
        $excludeId = $request->input('exclude_id'); // For edit mode

        if (!$date) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal tidak valid'
            ], 400);
        }

        try {
            $selectedDate = Carbon::parse($date);

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
            Log::error('Error getting available slots', [
                'date' => $date,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jadwal'
            ], 500);
        }
    }

    /**
     * Export visits data
     */
    public function export(Request $request)
    {
        // Implementation for export functionality
        // You can implement CSV, Excel, or PDF export here
        return response()->json([
            'success' => false,
            'message' => 'Fitur export belum diimplementasikan'
        ]);
    }
}
