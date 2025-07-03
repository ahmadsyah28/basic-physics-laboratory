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
        'kode',
        'deskripsi',
        'image_url',
        'jumlah_tersedia',
        'jumlah_dipinjam',
        'jumlah_rusak',
        'nama_kategori',
        'stok',
        'harga'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'jumlah_tersedia' => 'integer',
        'jumlah_dipinjam' => 'integer',
        'jumlah_rusak' => 'integer',
        'stok' => 'integer'
    ];

    /**
     * Relationship dengan KategoriAlat
     */
    public function kategoriAlat()
    {
        return $this->belongsTo(KategoriAlat::class, 'nama_kategori', 'nama_kategori');
    }

    /**
     * Scope untuk pencarian alat
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('kode', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('nama_kategori', $kategori);
        }
        return $query;
    }

    /**
     * Scope untuk alat yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('jumlah_tersedia', '>', 0);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return match($status) {
            'available' => $query->where('jumlah_tersedia', '>', 0),
            'borrowed' => $query->where('jumlah_dipinjam', '>', 0),
            'maintenance' => $query->where('jumlah_rusak', '>', 0),
            'unavailable' => $query->where('jumlah_tersedia', '=', 0)
                                  ->where('jumlah_dipinjam', '=', 0),
            default => $query
        };
    }

    /**
     * Accessor untuk total alat
     */
    public function getTotalAlatAttribute()
    {
        return $this->jumlah_tersedia + $this->jumlah_dipinjam + $this->jumlah_rusak;
    }

    /**
     * Accessor untuk status ketersediaan
     */
    public function getStatusKetersediaanAttribute()
    {
        if ($this->jumlah_tersedia > 0) {
            return 'Tersedia';
        } elseif ($this->jumlah_dipinjam > 0) {
            return 'Dipinjam';
        } else {
            return 'Tidak Tersedia';
        }
    }

    /**
     * Get status for filter purposes
     */
    public function getStatusForFilter()
    {
        if ($this->jumlah_rusak > 0) {
            return 'maintenance';
        } elseif ($this->jumlah_tersedia > 0) {
            return 'available';
        } elseif ($this->jumlah_dipinjam > 0) {
            return 'borrowed';
        } else {
            return 'unavailable';
        }
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabel()
    {
        return match($this->getStatusForFilter()) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'maintenance' => 'Maintenance',
            'unavailable' => 'Tidak Tersedia',
            default => 'Unknown'
        };
    }

    /**
     * Get status icon for display
     */
    public function getStatusIcon()
    {
        return match($this->getStatusForFilter()) {
            'available' => 'check-circle',
            'borrowed' => 'hand-holding',
            'maintenance' => 'wrench',
            'unavailable' => 'times-circle',
            default => 'question-circle'
        };
    }

    /**
     * Get category icon based on category name
     */
    public function getCategoryIcon()
    {
        return match($this->nama_kategori) {
            'Elektronika' => 'microchip',
            'Pengukuran' => 'ruler',
            'Generator' => 'bolt',
            'Power' => 'battery-full',
            'Analisis' => 'chart-line',
            'Optik' => 'eye',
            'Mekanik' => 'cog',
            'Thermal' => 'thermometer-half',
            default => 'tools'
        };
    }

    /**
     * Check if alat dapat dipinjam
     */
    public function canBeBorrowed($jumlah = 1)
    {
        return $this->jumlah_tersedia >= $jumlah;
    }

    /**
     * Kurangi stok saat dipinjam
     */
    public function pinjam($jumlah = 1)
    {
        if ($this->canBeBorrowed($jumlah)) {
            $this->jumlah_tersedia -= $jumlah;
            $this->jumlah_dipinjam += $jumlah;
            return $this->save();
        }
        return false;
    }

    /**
     * Kembalikan stok saat dikembalikan
     */
    public function kembalikan($jumlah = 1)
    {
        if ($this->jumlah_dipinjam >= $jumlah) {
            $this->jumlah_dipinjam -= $jumlah;
            $this->jumlah_tersedia += $jumlah;
            return $this->save();
        }
        return false;
    }

    /**
     * Tandai alat sebagai rusak
     */
    public function tandaiRusak($jumlah = 1, $dari = 'tersedia')
    {
        if ($dari === 'tersedia' && $this->jumlah_tersedia >= $jumlah) {
            $this->jumlah_tersedia -= $jumlah;
            $this->jumlah_rusak += $jumlah;
            return $this->save();
        } elseif ($dari === 'dipinjam' && $this->jumlah_dipinjam >= $jumlah) {
            $this->jumlah_dipinjam -= $jumlah;
            $this->jumlah_rusak += $jumlah;
            return $this->save();
        }
        return false;
    }

    /**
     * Return item with condition check
     */
    public function returnItem($jumlah = 1, $kondisi = 'baik')
    {
        if ($this->jumlah_dipinjam >= $jumlah) {
            $this->jumlah_dipinjam -= $jumlah;

            if (in_array($kondisi, ['rusak_ringan', 'rusak_berat', 'hilang'])) {
                $this->jumlah_rusak += $jumlah;
            } else {
                $this->jumlah_tersedia += $jumlah;
            }

            return $this->save();
        }
        return false;
    }

    /**
     * Borrow method (alias for pinjam for compatibility)
     */
    public function borrow($jumlah = 1)
    {
        return $this->pinjam($jumlah);
    }
}
