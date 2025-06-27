<?php
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
        'url',
        'kategori'
    ];

    protected $casts = [
        'id' => 'string',
        'pengurus_id' => 'string',
        'acara_id' => 'string',
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

    // Helper untuk mendapatkan URL lengkap
    public function getUrlLengkapAttribute()
    {
        if (str_starts_with($this->url, 'http')) {
            return $this->url;
        }

        return asset('storage/' . $this->url);
    }
}
