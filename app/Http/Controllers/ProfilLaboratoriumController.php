<?php

namespace App\Http\Controllers;

use App\Models\ProfilLaboratorium;
use App\Models\Misi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfilLaboratoriumController extends Controller
{
    public function index()
    {
        $profil = ProfilLaboratorium::with('misi')->first();
        $misis = Misi::all();
        return view('profil_laboratorium.index', compact('profil', 'misis'));
    }

    public function create()
    {
        $misis = Misi::all();
        return view('profil_laboratorium.create', compact('misis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaLaboratorium' => 'required|string',
            'tentangLaboratorium' => 'required|string',
            'visi' => 'required|string',
            'misi' => 'required|array',
            'misi.*' => 'required|string',
        ]);

        // Create misi
        $misiIds = [];
        foreach ($request->misi as $pointMisi) {
            $misi = Misi::create([
                'id' => (string) Str::uuid(),
                'pointMisi' => $pointMisi,
            ]);
            $misiIds[] = $misi->id;
        }

        // For simplicity, use the first misi as the main reference
        $profil = ProfilLaboratorium::create([
            'namaLaboratorium' => $request->namaLaboratorium,
            'tentangLaboratorium' => $request->tentangLaboratorium,
            'visi' => $request->visi,
            'misiId' => $misiIds[0],
        ]);

        return redirect()->route('profil-laboratorium.index')->with('success', 'Profil Laboratorium berhasil dibuat.');
    }

    public function edit($id)
    {
        $profil = ProfilLaboratorium::findOrFail($id);
        $misis = Misi::all();
        return view('profil_laboratorium.edit', compact('profil', 'misis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaLaboratorium' => 'required|string',
            'tentangLaboratorium' => 'required|string',
            'visi' => 'required|string',
            'misi' => 'required|array',
            'misi.*' => 'required|string',
        ]);

        $profil = ProfilLaboratorium::findOrFail($id);

        // Update misi (for simplicity, just create new ones and update reference)
        $misiIds = [];
        foreach ($request->misi as $pointMisi) {
            $misi = Misi::create([
                'id' => (string) Str::uuid(),
                'pointMisi' => $pointMisi,
            ]);
            $misiIds[] = $misi->id;
        }

        $profil->update([
            'namaLaboratorium' => $request->namaLaboratorium,
            'tentangLaboratorium' => $request->tentangLaboratorium,
            'visi' => $request->visi,
            'misiId' => $misiIds[0],
        ]);

        return redirect()->route('profil-laboratorium.index')->with('success', 'Profil Laboratorium berhasil diupdate.');
    }

    public function destroy($id)
    {
        $profil = ProfilLaboratorium::findOrFail($id);
        $profil->delete();
        return redirect()->route('profil-laboratorium.index')->with('success', 'Profil Laboratorium berhasil dihapus.');
    }
}
