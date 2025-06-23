<?php
// ========================================
// SOLUSI 2: PERBAIKI CONTROLLER ARTIKEL
// ========================================

// app/Http/Controllers/ArticleController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Gambar;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        // Ambil semua artikel dengan gambar utama, urutkan berdasarkan tanggal terbaru
        $articles = Artikel::with(['gambarUtama'])
                           ->orderBy('tanggal_acara', 'desc')
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 150),
                                   'content' => $artikel->deskripsi,
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getFullImageUrl($artikel), // FIXED: Konversi ke URL lengkap
                                   'slug' => Str::slug($artikel->nama_acara),
                                   'featured' => $this->isFeatured($artikel)
                               ];
                           });

        return view('articles.index', compact('articles'));
    }

    public function show($id)
    {
        // Cari artikel berdasarkan ID dengan eager loading gambar
        $artikel = Artikel::with(['gambar', 'gambarUtama'])->find($id);

        if (!$artikel) {
            $artikel = Artikel::with(['gambar', 'gambarUtama'])
                             ->where('nama_acara', 'LIKE', '%' . str_replace('-', ' ', $id) . '%')
                             ->first();
        }

        if (!$artikel) {
            abort(404, 'Artikel tidak ditemukan');
        }

        // Format data artikel untuk view
        $article = [
            'id' => $artikel->id,
            'title' => $artikel->nama_acara,
            'excerpt' => Str::limit($artikel->deskripsi, 150),
            'content' => $this->formatContent($artikel->deskripsi),
            'author' => $artikel->penulis ?? 'Admin',
            'date' => $artikel->tanggal_acara->format('Y-m-d'),
            'image' => $this->getFullImageUrl($artikel), // FIXED: Konversi ke URL lengkap
            'slug' => Str::slug($artikel->nama_acara),
            'featured' => $this->isFeatured($artikel),
            'gallery' => $artikel->gambar->map(function($gambar) {
                return $this->getGambarFullUrl($gambar);
            })
        ];

        return view('articles.show', compact('article'));
    }

    // FIXED: Method yang benar untuk konversi ke asset URL
    private function getFullImageUrl($artikel)
    {
        if ($artikel->gambarUtama) {
            $url = $artikel->gambarUtama->url; // 'article/article-2.jpg'

            // Jika sudah URL lengkap (dimulai dengan http), return as is
            if (str_starts_with($url, 'http')) {
                return $url;
            }

            // Konversi ke asset URL lengkap
            return asset('images/' . $url); // asset('images/article/article-2.jpg')
        }

        // Return default image URL
        return asset('images/article/default.jpg');
    }

    // Helper method untuk gallery images
    private function getGambarFullUrl($gambar)
    {
        $url = $gambar->url;

        if (str_starts_with($url, 'http')) {
            return $url;
        }

        return asset('images/' . $url);
    }

    // Method untuk mendapatkan artikel unggulan (untuk beranda)
    public function getFeaturedArticles($limit = 3)
    {
        $articles = Artikel::with(['gambarUtama'])
                           ->orderBy('tanggal_acara', 'desc')
                           ->take($limit)
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 150),
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getFullImageUrl($artikel), // FIXED
                                   'slug' => Str::slug($artikel->nama_acara)
                               ];
                           });

        return $articles->toArray();
    }

    // Method untuk API
    public function latest()
    {
        $articles = Artikel::with(['gambarUtama'])
                           ->orderBy('tanggal_acara', 'desc')
                           ->take(5)
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 100),
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getFullImageUrl($artikel) // FIXED
                               ];
                           });

        return response()->json($articles);
    }

    public function featured()
    {
        $featuredArticles = $this->getFeaturedArticles();
        return response()->json($featuredArticles);
    }

    // Helper methods
    private function isFeatured($artikel)
    {
        return $artikel->tanggal_acara->diffInDays(now()) <= 30;
    }

    private function formatContent($content)
    {
        $formatted = '<p>' . str_replace(["\r\n", "\n", "\r"], '</p><p>', $content) . '</p>';
        $formatted = str_replace('<p></p>', '', $formatted);
        return $formatted;
    }

    // Method untuk filtering berdasarkan pencarian (untuk AJAX)
    public function search(Request $request)
    {
        $query = $request->get('query');

        $articles = Artikel::with(['gambarUtama'])
                           ->where('nama_acara', 'LIKE', '%' . $query . '%')
                           ->orWhere('deskripsi', 'LIKE', '%' . $query . '%')
                           ->orWhere('penulis', 'LIKE', '%' . $query . '%')
                           ->orderBy('tanggal_acara', 'desc')
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 150),
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getFullImageUrl($artikel) // FIXED
                               ];
                           });

        return response()->json($articles);
    }
}
