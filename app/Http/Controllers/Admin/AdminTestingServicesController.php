<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPengujian;
use App\Models\Pengujian;
use App\Models\PengujianItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTestingServicesController extends Controller
{
    /**
     * Display a listing of the testing requests.
     */
    public function index()
    {
        $pengujian = Pengujian::with('items.jenisPengujian')->latest()->get();
        return view('admin.testing.index', compact('pengujian'));
    }

    /**
     * Show the specified testing request.
     */
    public function show(Pengujian $pengujian)
    {
        $pengujian->load('items.jenisPengujian');
        return view('admin.testing.show', compact('pengujian'));
    }

    /**
     * Update the status of the testing request.
     */
    public function updateStatus(Request $request, Pengujian $pengujian)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,in_progress,completed,rejected',
            'catatan' => 'nullable|string',
        ]);

        $pengujian->status = $request->status;
        if ($request->filled('catatan')) {
            $pengujian->catatan = $request->catatan;
        }
        $pengujian->save();

        return redirect()->route('admin.testing.index')
            ->with('success', 'Status pengujian berhasil diperbarui');
    }

    /**
     * Upload test results for the testing request.
     */
    public function uploadResults(Request $request, Pengujian $pengujian)
    {
        $request->validate([
            'hasil_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Delete old file if exists
        if ($pengujian->hasil_file && Storage::disk('public')->exists($pengujian->hasil_file)) {
            Storage::disk('public')->delete($pengujian->hasil_file);
        }

        $file = $request->file('hasil_file');
        $filePath = $file->store('testing_results', 'public');

        $pengujian->hasil_file = $filePath;
        $pengujian->status = 'completed';
        $pengujian->save();

        return redirect()->route('admin.testing.show', $pengujian->id)
            ->with('success', 'Hasil pengujian berhasil diunggah');
    }

    /**
     * Display a listing of the testing types.
     */
    public function testingTypesIndex()
    {
        $jenisPengujian = JenisPengujian::all();
        return view('admin.testing-types.index', compact('jenisPengujian'));
    }

    /**
     * Show the form for creating a new testing type.
     */
    public function testingTypesCreate()
    {
        return view('admin.testing-types.create');
    }

    /**
     * Store a newly created testing type in storage.
     */
    public function testingTypesStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'durasi_pengujian' => 'required|integer|min:1',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gambar = $request->file('gambar');
        $gambarPath = $gambar->store('testing', 'public');

        JenisPengujian::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'durasi_pengujian' => $request->durasi_pengujian,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.testing-types.index')
            ->with('success', 'Jenis pengujian berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified testing type.
     */
    public function testingTypesEdit(JenisPengujian $jenisPengujian)
    {
        return view('admin.testing-types.edit', compact('jenisPengujian'));
    }

    /**
     * Update the specified testing type in storage.
     */
    public function testingTypesUpdate(Request $request, JenisPengujian $jenisPengujian)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'durasi_pengujian' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'durasi_pengujian' => $request->durasi_pengujian,
        ];

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($jenisPengujian->gambar && Storage::disk('public')->exists($jenisPengujian->gambar)) {
                Storage::disk('public')->delete($jenisPengujian->gambar);
            }

            // Store new image
            $gambar = $request->file('gambar');
            $gambarPath = $gambar->store('testing', 'public');
            $data['gambar'] = $gambarPath;
        }

        $jenisPengujian->update($data);

        return redirect()->route('admin.testing-types.index')
            ->with('success', 'Jenis pengujian berhasil diperbarui');
    }

    /**
     * Remove the specified testing type from storage.
     */
    public function testingTypesDestroy(JenisPengujian $jenisPengujian)
    {
        // Check if the testing type is being used in any testing request
        $isUsed = PengujianItem::where('jenis_pengujian_id', $jenisPengujian->id)->exists();

        if ($isUsed) {
            return redirect()->route('admin.testing-types.index')
                ->with('error', 'Jenis pengujian tidak dapat dihapus karena sedang digunakan dalam permintaan pengujian');
        }

        // Delete image
        if ($jenisPengujian->gambar && Storage::disk('public')->exists($jenisPengujian->gambar)) {
            Storage::disk('public')->delete($jenisPengujian->gambar);
        }

        $jenisPengujian->delete();

        return redirect()->route('admin.testing-types.index')
            ->with('success', 'Jenis pengujian berhasil dihapus');
    }
}
