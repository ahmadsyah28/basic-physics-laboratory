<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\KategoriAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminEquipmentController extends Controller
{
    /**
     * Display a listing of the equipment.
     */
 public function index(Request $request)
{
    try {
        $query = Alat::with('kategoriAlat');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Category filter
        if ($request->has('kategori') && $request->kategori != '') {
            $query->byKategori($request->kategori);
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->byStatus($request->status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'nama');
        $sortDirection = $request->get('sort_direction', 'asc');

        if (in_array($sortBy, ['nama', 'kode', 'nama_kategori', 'jumlah_tersedia', 'stok', 'harga'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $alats = $query->paginate(12)->withQueryString();

        // Get categories for filter dropdown
        $kategoris = KategoriAlat::orderBy('nama_kategori')->get();

        // Get statistics
        $stats = [
            'total_alat' => Alat::count(),
            'total_tersedia' => Alat::sum('jumlah_tersedia'),
            'total_dipinjam' => Alat::sum('jumlah_dipinjam'),
            'total_rusak' => Alat::sum('jumlah_rusak'),
        ];

        return view('admin.equipment.index', compact('alats', 'kategoris', 'stats'));

    } catch (\Exception $e) {

        return redirect()->route('admin.dashboard')
                        ->with('error', 'Terjadi kesalahan saat memuat halaman alat: ' . $e->getMessage());
    }
}
    /**
     * Show the form for creating a new equipment.
     */
    public function create()
    {
        $kategoris = KategoriAlat::orderBy('nama_kategori')->get();
        return view('admin.equipment.create', compact('kategoris'));
    }

    /**
     * Store a newly created equipment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:alat,kode',
            'deskripsi' => 'required|string',
            'nama_kategori' => 'required|exists:kategori_alat,nama_kategori',
            'stok' => 'required|integer|min:0',
            'jumlah_tersedia' => 'required|integer|min:0|lte:stok',
            'jumlah_dipinjam' => 'required|integer|min:0',
            'jumlah_rusak' => 'required|integer|min:0',
            'harga' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Validate total quantities
        $total = $request->jumlah_tersedia + $request->jumlah_dipinjam + $request->jumlah_rusak;
        if ($total > $request->stok) {
            return back()->withErrors(['stok' => 'Total jumlah (tersedia + dipinjam + rusak) tidak boleh melebihi stok total.'])->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('equipment', 'public');
            $data['image_url'] = $imagePath;
        } else {
            $data['image_url'] = 'equipment/default.png'; // Default image
        }

        Alat::create($data);

        return redirect()->route('admin.equipment.index')
                        ->with('success', 'Alat berhasil ditambahkan.');
    }

    /**
     * Display the specified equipment.
     */
    public function show(Alat $equipment)
    {
        $equipment->load('kategoriAlat');
        return view('admin.equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified equipment.
     */
    public function edit(Alat $equipment)
    {
        $kategoris = KategoriAlat::orderBy('nama_kategori')->get();
        return view('admin.equipment.edit', compact('equipment', 'kategoris'));
    }

    /**
     * Update the specified equipment in storage.
     */
    public function update(Request $request, Alat $equipment)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['required', 'string', 'max:255', Rule::unique('alat')->ignore($equipment->id)],
            'deskripsi' => 'required|string',
            'nama_kategori' => 'required|exists:kategori_alat,nama_kategori',
            'stok' => 'required|integer|min:0',
            'jumlah_tersedia' => 'required|integer|min:0|lte:stok',
            'jumlah_dipinjam' => 'required|integer|min:0',
            'jumlah_rusak' => 'required|integer|min:0',
            'harga' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Validate total quantities
        $total = $request->jumlah_tersedia + $request->jumlah_dipinjam + $request->jumlah_rusak;
        if ($total > $request->stok) {
            return back()->withErrors(['stok' => 'Total jumlah (tersedia + dipinjam + rusak) tidak boleh melebihi stok total.'])->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists and not default
            if ($equipment->image_url && $equipment->image_url !== 'equipment/default.png') {
                Storage::disk('public')->delete($equipment->image_url);
            }

            $imagePath = $request->file('image')->store('equipment', 'public');
            $data['image_url'] = $imagePath;
        }

        $equipment->update($data);

        return redirect()->route('admin.equipment.index')
                        ->with('success', 'Alat berhasil diperbarui.');
    }

    /**
     * Remove the specified equipment from storage.
     */
    public function destroy(Alat $equipment)
    {
        // Check if equipment is currently borrowed
        if ($equipment->jumlah_dipinjam > 0) {
            return redirect()->route('admin.equipment.index')
                            ->with('error', 'Tidak dapat menghapus alat yang sedang dipinjam.');
        }

        // Delete image if exists and not default
        if ($equipment->image_url && $equipment->image_url !== 'equipment/default.png') {
            Storage::disk('public')->delete($equipment->image_url);
        }

        $equipment->delete();

        return redirect()->route('admin.equipment.index')
                        ->with('success', 'Alat berhasil dihapus.');
    }

    /**
     * Update equipment status
     */
    public function updateStatus(Request $request, Alat $equipment)
    {
        $request->validate([
            'action' => 'required|in:repair,return_from_repair,mark_damaged',
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->quantity;
        $action = $request->action;

        switch ($action) {
            case 'repair':
                if (!$equipment->tandaiRusak($quantity, 'tersedia')) {
                    return back()->with('error', 'Jumlah tidak mencukupi untuk dipindahkan ke status rusak.');
                }
                break;

            case 'return_from_repair':
                if ($equipment->jumlah_rusak >= $quantity) {
                    $equipment->jumlah_rusak -= $quantity;
                    $equipment->jumlah_tersedia += $quantity;
                    $equipment->save();
                } else {
                    return back()->with('error', 'Jumlah tidak mencukupi untuk dikembalikan dari status rusak.');
                }
                break;

            case 'mark_damaged':
                if (!$equipment->tandaiRusak($quantity, 'dipinjam')) {
                    return back()->with('error', 'Jumlah tidak mencukupi untuk dipindahkan ke status rusak.');
                }
                break;
        }

        return back()->with('success', 'Status alat berhasil diperbarui.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,change_category',
            'equipment_ids' => 'required|array',
            'equipment_ids.*' => 'exists:alat,id',
            'new_category' => 'required_if:action,change_category|exists:kategori_alat,nama_kategori'
        ]);

        $equipments = Alat::whereIn('id', $request->equipment_ids);

        switch ($request->action) {
            case 'delete':
                // Check if any equipment is borrowed
                $borrowedCount = $equipments->where('jumlah_dipinjam', '>', 0)->count();
                if ($borrowedCount > 0) {
                    return back()->with('error', "Tidak dapat menghapus {$borrowedCount} alat yang sedang dipinjam.");
                }

                $deleted = $equipments->get();
                foreach ($deleted as $equipment) {
                    if ($equipment->image_url && $equipment->image_url !== 'equipment/default.png') {
                        Storage::disk('public')->delete($equipment->image_url);
                    }
                }

                $equipments->delete();
                return back()->with('success', count($request->equipment_ids) . ' alat berhasil dihapus.');

            case 'change_category':
                $equipments->update(['nama_kategori' => $request->new_category]);
                return back()->with('success', count($request->equipment_ids) . ' alat berhasil dipindahkan kategori.');
        }

        return back();
    }
}
