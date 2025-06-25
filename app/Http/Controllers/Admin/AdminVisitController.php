<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class AdminVisitController extends Controller
{
    /**
     * Display a listing of the visit schedules.
     */
    public function index()
    {
        $kunjungan = Kunjungan::latest()->get();
        return view('admin.visits.index', compact('kunjungan'));
    }

    /**
     * Show the specified visit schedule.
     */
    public function show(Kunjungan $kunjungan)
    {
        return view('admin.visits.show', compact('kunjungan'));
    }

    /**
     * Update the status of the visit schedule.
     */
    public function updateStatus(Request $request, Kunjungan $kunjungan)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'catatan' => 'nullable|string',
        ]);

        $kunjungan->status = $request->status;
        if ($request->filled('catatan')) {
            $kunjungan->catatan_admin = $request->catatan;
        }
        $kunjungan->save();

        return redirect()->route('admin.visits.index')
            ->with('success', 'Status kunjungan berhasil diperbarui');
    }

    /**
     * Show the calendar view of visit schedules.
     */
    public function calendar()
    {
        $kunjungan = Kunjungan::where('status', 'approved')->get();
        return view('admin.visits.calendar', compact('kunjungan'));
    }

    /**
     * Get the visit schedules for the calendar.
     */
    public function getCalendarData()
    {
        $kunjungan = Kunjungan::where('status', 'approved')->get();

        $events = [];
        foreach ($kunjungan as $k) {
            $events[] = [
                'id' => $k->id,
                'title' => $k->nama_institusi . ' - ' . $k->jenis_kunjungan,
                'start' => $k->tanggal_kunjungan . 'T' . $k->waktu_mulai,
                'end' => $k->tanggal_kunjungan . 'T' . $k->waktu_selesai,
                'url' => route('admin.visits.show', $k->id),
            ];
        }

        return response()->json($events);
    }

    /**
     * Create a new visit schedule.
     */
    public function create()
    {
        return view('admin.visits.create');
    }

    /**
     * Store a newly created visit schedule in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'nama_penanggung_jawab' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|string|max:20',
            'jenis_kunjungan' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'tujuan_kunjungan' => 'required|string',
        ]);

        Kunjungan::create([
            'nama_institusi' => $request->nama_institusi,
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'jenis_kunjungan' => $request->jenis_kunjungan,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'jumlah_peserta' => $request->jumlah_peserta,
            'tujuan_kunjungan' => $request->tujuan_kunjungan,
            'status' => 'approved', // Auto-approved when created by admin
        ]);

        return redirect()->route('admin.visits.index')
            ->with('success', 'Jadwal kunjungan berhasil dibuat');
    }

    /**
     * Show the form for editing the specified visit schedule.
     */
    public function edit(Kunjungan $kunjungan)
    {
        return view('admin.visits.edit', compact('kunjungan'));
    }

    /**
     * Update the specified visit schedule in storage.
     */
    public function update(Request $request, Kunjungan $kunjungan)
    {
        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'nama_penanggung_jawab' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|string|max:20',
            'jenis_kunjungan' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'tujuan_kunjungan' => 'required|string',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $kunjungan->update($request->all());

        return redirect()->route('admin.visits.index')
            ->with('success', 'Jadwal kunjungan berhasil diperbarui');
    }

    /**
     * Remove the specified visit schedule from storage.
     */
    public function destroy(Kunjungan $kunjungan)
    {
        $kunjungan->delete();

        return redirect()->route('admin.visits.index')
            ->with('success', 'Jadwal kunjungan berhasil dihapus');
    }
}
