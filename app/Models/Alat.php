<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Alat extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'alat';

    protected $fillable = [
        'nama',
        'deskripsi',
        'stok',
        'isBroken',
        'model',
        'kategori',
        'gambar',
        'spesifikasi',
        'persyaratan',
        'durasi_pinjam',
        'icon'
    ];

    protected $casts = [
        'isBroken' => 'boolean',
        'spesifikasi' => 'array',
        'persyaratan' => 'array',
    ];

    /**
     * Get available stock (not broken and not currently borrowed)
     */
    public function getStokTersediaAttribute()
    {
        if ($this->isBroken) {
            return 0;
        }

        // Calculate currently borrowed quantity
        $borrowed = PeminjamanItem::whereHas('peminjaman', function($query) {
            $query->whereIn('status', ['PENDING', 'PROCESSING']);
        })
        ->where('alat_id', $this->id)
        ->sum('jumlah');

        return max(0, $this->stok - $borrowed);
    }

    /**
     * Get status for display
     */
    public function getStatusAttribute()
    {
        if ($this->isBroken) {
            return 'maintenance';
        }

        return $this->stok_tersedia > 0 ? 'available' : 'unavailable';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'available' => 'bg-green-100 text-green-800 border-green-200',
            'maintenance' => 'bg-red-100 text-red-800 border-red-200',
            'unavailable' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'available' => 'Tersedia',
            'maintenance' => 'Maintenance',
            'unavailable' => 'Habis',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori && $kategori !== 'all') {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status === 'available') {
            return $query->where('isBroken', false)->where('stok', '>', 0);
        } elseif ($status === 'maintenance') {
            return $query->where('isBroken', true);
        }
        return $query;
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * Relationship dengan peminjaman items
     */
    public function peminjamanItems()
    {
        return $this->hasMany(PeminjamanItem::class, 'alat_id');
    }

    /**
     * Get active loans for this equipment
     */
    public function activePeminjaman()
    {
        return $this->hasManyThrough(
            Peminjaman::class,
            PeminjamanItem::class,
            'alat_id',
            'id',
            'id',
            'peminjamanId'
        )->whereIn('status', ['PENDING', 'PROCESSING']);
    }
}
