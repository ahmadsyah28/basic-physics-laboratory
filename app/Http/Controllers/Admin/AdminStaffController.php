<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodataPengurus;
use App\Models\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staff = BiodataPengurus::orderBy('nama')->get();

        $stats = [
            'total_staff' => $staff->count(),
            'kepala_lab' => $staff->filter(function($s) {
                return str_contains(strtolower($s->jabatan), 'kepala');
            })->count(),
            'dosen' => $staff->filter(function($s) {
                $jabatan = strtolower($s->jabatan);
                return str_contains($jabatan, 'dosen') || str_contains($jabatan, 'pengajar');
            })->count(),
            'laboran' => $staff->filter(function($s) {
                return str_contains(strtolower($s->jabatan), 'laboran');
            })->count(),
        ];

        return view('admin.staff.index', compact('staff', 'stats'));
    }

    public function store(Request $request)
    {
        Log::info('Staff store attempt', [
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'has_file' => $request->hasFile('foto')
        ]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // 1. Buat staff dulu
            $staff = new BiodataPengurus();
            $staff->nama = $request->nama;
            $staff->jabatan = $request->jabatan;
            $staff->save();

            Log::info('Staff created successfully', ['staff_id' => $staff->id]);

            // 2. Handle foto jika ada
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $foto = $request->file('foto');

                // Path direktori staff
                $staffDir = public_path('images/staff');

                // Pastikan direktori ada
                if (!is_dir($staffDir)) {
                    mkdir($staffDir, 0755, true);
                }

                // Generate nama file unik
                $fileName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();

                // Path lengkap file
                $filePath = $staffDir . '/' . $fileName;

                // Pindahkan file
                if ($foto->move($staffDir, $fileName)) {
                    Log::info('Photo moved successfully', ['path' => $filePath]);

                    // Simpan ke database (hanya nama file, bukan full path)
                    $gambar = new Gambar();
                    $gambar->pengurus_id = $staff->id;
                    $gambar->url = 'images/staff/' . $fileName; // Relative path dari public
                    $gambar->kategori = 'PENGURUS';
                    $gambar->save();

                    Log::info('Photo record created', ['gambar_id' => $gambar->id]);
                } else {
                    Log::error('Failed to move uploaded file');
                    throw new \Exception('Gagal menyimpan file foto');
                }
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Error creating staff', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(BiodataPengurus $staff)
    {
        try {
            // Ambil foto staff
            $foto = Gambar::where('pengurus_id', $staff->id)
                    ->where('kategori', 'PENGURUS')
                    ->latest()
                    ->first();

            Log::info('Edit staff request', [
                'staff_id' => $staff->id,
                'has_photo' => $foto ? true : false,
                'expects_json' => request()->expectsJson()
            ]);

            // Return JSON untuk AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'id' => $staff->id,
                    'nama' => $staff->nama,
                    'jabatan' => $staff->jabatan,
                    'foto' => $foto ? asset($foto->url) : null
                ]);
            }

            return view('admin.staff.edit', compact('staff', 'foto'));

        } catch (\Exception $e) {
            Log::error('Error in edit method', [
                'staff_id' => $staff->id,
                'error' => $e->getMessage()
            ]);

            if (request()->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return redirect()->route('admin.staff.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    public function update(Request $request, BiodataPengurus $staff)
    {
        Log::info('Staff update attempt', [
            'staff_id' => $staff->id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'has_file' => $request->hasFile('foto')
        ]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Update data staff
            $staff->nama = $request->nama;
            $staff->jabatan = $request->jabatan;
            $staff->save();

            Log::info('Staff updated successfully');

            // Handle foto jika ada
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $foto = $request->file('foto');

                // Path direktori staff
                $staffDir = public_path('images/staff');

                // Pastikan direktori ada
                if (!is_dir($staffDir)) {
                    mkdir($staffDir, 0755, true);
                }

                // Generate nama file unik
                $fileName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();

                // Cari foto lama untuk dihapus
                $existingFoto = Gambar::where('pengurus_id', $staff->id)
                                ->where('kategori', 'PENGURUS')
                                ->latest()
                                ->first();

                // Hapus file lama jika ada
                if ($existingFoto) {
                    $oldFilePath = public_path($existingFoto->url);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                        Log::info('Old photo deleted', ['path' => $oldFilePath]);
                    }
                }

                // Pindahkan file baru
                if ($foto->move($staffDir, $fileName)) {
                    Log::info('New photo moved successfully');

                    if ($existingFoto) {
                        // Update record yang ada
                        $existingFoto->url = 'images/staff/' . $fileName;
                        $existingFoto->save();
                        Log::info('Existing photo record updated');
                    } else {
                        // Buat record baru
                        $gambar = new Gambar();
                        $gambar->pengurus_id = $staff->id;
                        $gambar->url = 'images/staff/' . $fileName;
                        $gambar->kategori = 'PENGURUS';
                        $gambar->save();
                        Log::info('New photo record created');
                    }
                } else {
                    throw new \Exception('Gagal menyimpan file foto');
                }
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating staff', [
                'staff_id' => $staff->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(BiodataPengurus $staff)
    {
        try {
            // Hapus foto-foto terkait
            $photos = Gambar::where('pengurus_id', $staff->id)
                    ->where('kategori', 'PENGURUS')
                    ->get();

            foreach ($photos as $photo) {
                $filePath = public_path($photo->url);
                if (file_exists($filePath)) {
                    unlink($filePath);
                    Log::info('Photo file deleted', ['path' => $filePath]);
                }
                $photo->delete();
            }

            $staff->delete();

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error deleting staff', [
                'staff_id' => $staff->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.staff.index')
                ->with('error', 'Terjadi kesalahan saat menghapus staff');
        }
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function show(BiodataPengurus $staff)
    {
        return view('admin.staff.show', compact('staff'));
    }
}
