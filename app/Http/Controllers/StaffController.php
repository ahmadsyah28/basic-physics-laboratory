<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiodataPengurus;
use App\Models\Gambar;

class StaffController extends Controller
{
    public function index()
    {
        // Ambil semua data pengurus dengan foto
        $pengurus = BiodataPengurus::with(['fotoProfil'])->orderBy('nama')->get();

        // Kategorisasi berdasarkan jabatan untuk statistik
        $stats = [
            'total_staff' => $pengurus->count(),
            'kepala_lab' => $pengurus->filter(function($p) {
                return str_contains(strtolower($p->jabatan), 'kepala');
            })->count(),
            'dosen' => $pengurus->filter(function($p) {
                $jabatan = strtolower($p->jabatan);
                return str_contains($jabatan, 'dosen') || str_contains($jabatan, 'pengajar');
            })->count(),
            'laboran' => $pengurus->filter(function($p) {
                return str_contains(strtolower($p->jabatan), 'laboran');
            })->count(),
        ];

        return view('staff', compact('pengurus', 'stats'));
    }

    // Admin functions untuk management (opsional)
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $pengurus = BiodataPengurus::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan
        ]);

        // Upload dan simpan foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/staff'), $filename);

            Gambar::create([
                'pengurus_id' => $pengurus->id,
                'url' => 'staff/' . $filename,
                'kategori' => 'PENGURUS'
            ]);
        }

        return redirect()->route('staff')->with('success', 'Pengurus berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pengurus = BiodataPengurus::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $pengurus->update([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            $pengurus->gambar()->delete();

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/staff'), $filename);

            Gambar::create([
                'pengurus_id' => $pengurus->id,
                'url' => 'staff/' . $filename,
                'kategori' => 'PENGURUS'
            ]);
        }

        return redirect()->route('staff')->with('success', 'Pengurus berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pengurus = BiodataPengurus::findOrFail($id);
        $pengurus->gambar()->delete();
        $pengurus->delete();

        return redirect()->route('staff')->with('success', 'Pengurus berhasil dihapus');
    }
}
