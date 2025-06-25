<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AdminArticleController extends Controller
{
    /**
     * Display a listing of the articles.
     */
    public function index()
    {
        $articles = Artikel::latest()->get();
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar_utama' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_tambahan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload main image
        $gambarUtama = $request->file('gambar_utama');
        $gambarUtamaPath = $gambarUtama->store('article', 'public');

        // Get author name
        $authorName = Auth::check() ? Auth::user()->name : 'Admin';

        // Create article
        $artikel = Artikel::create([
            'id' => Str::uuid(),
            'judul' => $request->judul,
            'konten' => $request->konten,
            'gambar_utama' => $gambarUtamaPath,
            'penulis' => $authorName,
        ]);

        // Upload additional images if any
        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $image) {
                $path = $image->store('article', 'public');

                Gambar::create([
                    'artikel_id' => $artikel->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dibuat');
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Artikel $article)
    {
        $article->load('gambar');
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Artikel $article)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_tambahan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
        ];

        // Update main image if provided
        if ($request->hasFile('gambar_utama')) {
            // Delete old image
            if ($article->gambar_utama && Storage::disk('public')->exists($article->gambar_utama)) {
                Storage::disk('public')->delete($article->gambar_utama);
            }

            // Store new image
            $gambarUtama = $request->file('gambar_utama');
            $gambarUtamaPath = $gambarUtama->store('article', 'public');
            $data['gambar_utama'] = $gambarUtamaPath;
        }

        $article->update($data);

        // Upload additional images if any
        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $image) {
                $path = $image->store('article', 'public');

                Gambar::create([
                    'artikel_id' => $article->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Artikel $article)
    {
        // Delete main image
        if ($article->gambar_utama && Storage::disk('public')->exists($article->gambar_utama)) {
            Storage::disk('public')->delete($article->gambar_utama);
        }

        // Delete additional images
        foreach ($article->gambar as $gambar) {
            if (Storage::disk('public')->exists($gambar->path)) {
                Storage::disk('public')->delete($gambar->path);
            }
            $gambar->delete();
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus');
    }

    /**
     * Remove the specified additional image from storage.
     */
    public function destroyImage(Gambar $gambar)
    {
        if (Storage::disk('public')->exists($gambar->path)) {
            Storage::disk('public')->delete($gambar->path);
        }

        $gambar->delete();

        return redirect()->back()
            ->with('success', 'Gambar berhasil dihapus');
    }
}
