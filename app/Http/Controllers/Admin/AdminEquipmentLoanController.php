<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Illuminate\Http\Request;

class AdminEquipmentLoanController extends Controller
{
    /**
     * Display a listing of the equipment loans.
     */
    public function index()
    {
        $peminjaman = Peminjaman::with('items.alat')->latest()->get();
        return view('admin.equipment-loan.index', compact('peminjaman'));
    }

    /**
     * Show the specified equipment loan.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load('items.alat');
        return view('admin.equipment-loan.show', compact('peminjaman'));
    }

    /**
     * Update the status of the equipment loan.
     */
    public function updateStatus(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $peminjaman->status = $request->status;
        $peminjaman->save();

        // If approved or rejected, update availability of the equipment
        if ($request->status === 'approved') {
            foreach ($peminjaman->items as $item) {
                $alat = $item->alat;
                $alat->stok_tersedia -= $item->jumlah;
                $alat->save();
            }
        } elseif ($request->status === 'completed') {
            // Return the equipment to available stock
            foreach ($peminjaman->items as $item) {
                $alat = $item->alat;
                $alat->stok_tersedia += $item->jumlah;
                $alat->save();
            }
        }

        return redirect()->route('admin.equipment-loan.index')
            ->with('success', 'Status peminjaman berhasil diperbarui');
    }

    /**
     * Display a listing of the equipment.
     */
    public function equipmentIndex()
    {
        $alat = Alat::all();
        return view('admin.equipment.index', compact('alat'));
    }

    /**
     * Show the form for creating a new equipment.
     */
    public function equipmentCreate()
    {
        return view('admin.equipment.create');
    }

    /**
     * Store a newly created equipment in storage.
     */
    public function equipmentStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'spesifikasi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gambar = $request->file('gambar');
        $gambarPath = $gambar->store('equipment', 'public');

        Alat::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'spesifikasi' => $request->spesifikasi,
            'stok_total' => $request->stok_total,
            'stok_tersedia' => $request->stok_tersedia,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Alat berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified equipment.
     */
    public function equipmentEdit(Alat $alat)
    {
        return view('admin.equipment.edit', compact('alat'));
    }

    /**
     * Update the specified equipment in storage.
     */
    public function equipmentUpdate(Request $request, Alat $alat)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'spesifikasi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'spesifikasi' => $request->spesifikasi,
            'stok_total' => $request->stok_total,
            'stok_tersedia' => $request->stok_tersedia,
        ];

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($alat->gambar && file_exists(storage_path('app/public/' . $alat->gambar))) {
                unlink(storage_path('app/public/' . $alat->gambar));
            }

            // Store new image
            $gambar = $request->file('gambar');
            $gambarPath = $gambar->store('equipment', 'public');
            $data['gambar'] = $gambarPath;
        }

        $alat->update($data);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Alat berhasil diperbarui');
    }

    /**
     * Remove the specified equipment from storage.
     */
    public function equipmentDestroy(Alat $alat)
    {
        // Check if the equipment is being used in any loan
        $isUsed = PeminjamanItem::where('alat_id', $alat->id)->exists();

        if ($isUsed) {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Alat tidak dapat dihapus karena sedang digunakan dalam peminjaman');
        }

        // Delete image
        if ($alat->gambar && file_exists(storage_path('app/public/' . $alat->gambar))) {
            unlink(storage_path('app/public/' . $alat->gambar));
        }

        $alat->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Alat berhasil dihapus');
    }
}
