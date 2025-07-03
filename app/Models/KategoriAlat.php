<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KategoriAlat extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kategori_alat';

    protected $fillable = [
        'nama_kategori'
    ];

    /**
     * Get all alat that belong to this category
     */
    public function alat()
    {
        return $this->hasMany(Alat::class, 'nama_kategori', 'nama_kategori');
    }

    /**
     * Get the count of alat in this category
     */
    public function getAlatCountAttribute()
    {
        return $this->alat()->count();
    }

    /**
     * Get the total stock available in this category
     */
    public function getTotalStockAttribute()
    {
        return $this->alat()->sum('stok');
    }

    /**
     * Get the total available items in this category
     */
    public function getTotalAvailableAttribute()
    {
        return $this->alat()->sum('jumlah_tersedia');
    }

    /**
     * Scope to search categories by name
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama_kategori', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Check if category can be deleted (no alat associated)
     */
    public function canBeDeleted()
    {
        return $this->alat()->count() === 0;
    }
}
