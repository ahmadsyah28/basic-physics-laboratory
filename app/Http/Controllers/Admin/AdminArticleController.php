<?php

// File: app/Http/Controllers/Admin/AdminArticleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        $articles = Artikel::with(['gambarUtama'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_acara', 'like', "%{$search}%")
                           ->orWhere('penulis', 'like', "%{$search}%")
                           ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->orderBy('tanggal_acara', 'desc')
            ->paginate($perPage);

        return view('admin.articles.index', compact('articles', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'penulis' => 'required|string|max:255',
            'tanggal_acara' => 'required|date',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $artikel = Artikel::create([
            'nama_acara' => $request->nama_acara,
            'deskripsi' => $request->deskripsi,
            'penulis' => $request->penulis,
            'tanggal_acara' => $request->tanggal_acara,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('article', $filename, 'public');

                // FIXED: Simpan path tanpa prefix 'storage/' karena akan ditangani di accessor
                Gambar::create([
                    'acara_id' => $artikel->id,
                    'url' => $path, // CHANGED: dari 'storage/' . $path menjadi $path saja
                    'kategori' => 'ACARA'
                ]);
            }
        }

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil dibuat!');
    }

    public function show(Artikel $article)
    {
        $article->load(['gambar']);
        return view('admin.articles.show', compact('article'));
    }

    public function edit(Artikel $article)
    {
        $article->load(['gambar']);
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Artikel $article)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'penulis' => 'required|string|max:255',
            'tanggal_acara' => 'required|date',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article->update([
            'nama_acara' => $request->nama_acara,
            'deskripsi' => $request->deskripsi,
            'penulis' => $request->penulis,
            'tanggal_acara' => $request->tanggal_acara,
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('article', $filename, 'public');

                // FIXED: Simpan path tanpa prefix 'storage/' karena akan ditangani di accessor
                Gambar::create([
                    'acara_id' => $article->id,
                    'url' => $path, // CHANGED: dari 'storage/' . $path menjadi $path saja
                    'kategori' => 'ACARA'
                ]);
            }
        }

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Artikel $article)
    {
        // Delete associated images from storage
        foreach ($article->gambar as $gambar) {
            // FIXED: Handle different URL formats
            $imagePath = $gambar->url;

            // Remove 'storage/' prefix if exists
            if (str_starts_with($imagePath, 'storage/')) {
                $imagePath = str_replace('storage/', '', $imagePath);
            }

            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Delete the article (images will be deleted via cascade)
        $article->delete();

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil dihapus!');
    }

    public function destroyImage(Gambar $gambar)
    {
        // FIXED: Handle different URL formats
        $imagePath = $gambar->url;

        // Remove 'storage/' prefix if exists
        if (str_starts_with($imagePath, 'storage/')) {
            $imagePath = str_replace('storage/', '', $imagePath);
        }

        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Delete image record
        $gambar->delete();

        return response()->json(['success' => true]);
    }
}
