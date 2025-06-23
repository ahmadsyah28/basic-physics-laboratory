<?php
// app/Models/Artikel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';

    // Gunakan UUID sebagai primary key
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_acara',
        'deskripsi',
        'penulis',
        'tanggal_acara',
    ];

    protected $casts = [
        'id' => 'string',
        'tanggal_acara' => 'datetime',
    ];

    // Auto generate UUID saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Relationship dengan gambar
    public function gambar()
    {
        return $this->hasMany(Gambar::class, 'acara_id', 'id')
                    ->where('kategori', 'ACARA');
    }

    // Untuk mendapatkan gambar utama
    public function gambarUtama()
    {
        return $this->hasOne(Gambar::class, 'acara_id', 'id')
                    ->where('kategori', 'ACARA')
                    ->latest();
    }

    // Accessor untuk mendapatkan excerpt
    public function getExcerptAttribute()
    {
        return Str::limit($this->deskripsi, 150);
    }

    // Accessor untuk mendapatkan slug
    public function getSlugAttribute()
    {
        return Str::slug($this->nama_acara);
    }

    // Scope untuk artikel terbaru
    public function scopeTerbaru($query, $limit = 10)
    {
        return $query->orderBy('tanggal_acara', 'desc')->limit($limit);
    }

    // Scope untuk artikel berdasarkan kategori
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('nama_acara', 'LIKE', '%' . $kategori . '%');
    }
}
