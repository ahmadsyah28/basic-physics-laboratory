<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiodataPengurus;
use App\Models\Gambar;
use Illuminate\Support\Str;

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
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/staff'), $filename);

            Gambar::create([
                'pengurus_id' => $pengurus->id,
                'url' => 'images/staff/' . $filename, // PERBAIKAN: tambah 'images/'
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
            // Hapus foto lama dari file system
            $oldPhoto = $pengurus->fotoProfil;
            if ($oldPhoto) {
                $oldFilePath = public_path($oldPhoto->url);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                $oldPhoto->delete();
            }

            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/staff'), $filename);

            Gambar::create([
                'pengurus_id' => $pengurus->id,
                'url' => 'images/staff/' . $filename, // PERBAIKAN: tambah 'images/'
                'kategori' => 'PENGURUS'
            ]);
        }

        return redirect()->route('staff')->with('success', 'Pengurus berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pengurus = BiodataPengurus::findOrFail($id);

        // Hapus foto dari file system
        $photos = $pengurus->gambar;
        foreach ($photos as $photo) {
            $filePath = public_path($photo->url);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $pengurus->gambar()->delete();
        $pengurus->delete();

        return redirect()->route('staff')->with('success', 'Pengurus berhasil dihapus');
    }
}
