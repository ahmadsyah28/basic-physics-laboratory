<?php

// File: app/Models/Gambar.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Gambar extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'gambar';

    protected $fillable = [
        'id',
        'pengurus_id',
        'acara_id',
        'facility_id',
        'url',
        'kategori'
    ];

    protected $casts = [
        'id' => 'string',
        'pengurus_id' => 'string',
        'acara_id' => 'string',
        'facility_id' => 'string',
    ];

    // Relationship dengan pengurus
    public function pengurus()
    {
        return $this->belongsTo(BiodataPengurus::class, 'pengurus_id', 'id');
    }

    // Relationship dengan artikel
    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'acara_id', 'id');
    }

    // Relationship dengan fasilitas
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'id');
    }

    // FIXED: Helper untuk mendapatkan URL lengkap
    public function getUrlLengkapAttribute()
    {
        // Jika sudah berupa URL lengkap (http/https)
        if (str_starts_with($this->url, 'http')) {
            return $this->url;
        }

        // Jika sudah ada prefix 'storage/' di URL
        if (str_starts_with($this->url, 'storage/')) {
            return asset($this->url);
        }

        // Jika hanya path file tanpa storage/
        return asset('storage/' . $this->url);
    }

    // Scope untuk gallery home
    public function scopeForGallery($query)
    {
        return $query->whereIn('kategori', ['PENGURUS', 'ACARA', 'FASILITAS'])
                    ->latest()
                    ->limit(6);
    }

    // Scope untuk fasilitas saja
    public function scopeFasilitas($query)
    {
        return $query->where('kategori', 'FASILITAS');
    }

    // TAMBAHAN: Scope untuk acara saja
    public function scopeAcara($query)
    {
        return $query->where('kategori', 'ACARA');
    }

    // TAMBAHAN: Method untuk debug URL
    public function getDebugUrlInfo()
    {
        return [
            'original_url' => $this->url,
            'url_lengkap' => $this->url_lengkap,
            'starts_with_http' => str_starts_with($this->url, 'http'),
            'starts_with_storage' => str_starts_with($this->url, 'storage/'),
        ];
    }
}
