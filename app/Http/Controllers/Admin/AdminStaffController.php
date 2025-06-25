<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodataPengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminStaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index()
    {
        $staff = BiodataPengurus::all();
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created staff in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $foto = $request->file('foto');
        $fotoPath = $foto->store('staff', 'public');

        BiodataPengurus::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified staff.
     */
    public function edit(BiodataPengurus $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff in storage.
     */
    public function update(Request $request, BiodataPengurus $staff)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('foto')) {
            // Delete old image
            if ($staff->foto && Storage::disk('public')->exists($staff->foto)) {
                Storage::disk('public')->delete($staff->foto);
            }

            // Store new image
            $foto = $request->file('foto');
            $fotoPath = $foto->store('staff', 'public');
            $data['foto'] = $fotoPath;
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil diperbarui');
    }

    /**
     * Remove the specified staff from storage.
     */
    public function destroy(BiodataPengurus $staff)
    {
        // Delete image
        if ($staff->foto && Storage::disk('public')->exists($staff->foto)) {
            Storage::disk('public')->delete($staff->foto);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil dihapus');
    }
}
