<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BiodataPengurus extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'biodata_pengurus';

    protected $fillable = [
        'nama',
        'jabatan'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    // Relationship dengan gambar
    public function gambar()
    {
        return $this->hasMany(Gambar::class, 'pengurus_id', 'id')
                    ->where('kategori', 'PENGURUS');
    }

    // Untuk mendapatkan foto profil utama
    public function fotoProfil()
    {
        return $this->hasOne(Gambar::class, 'pengurus_id', 'id')
                    ->where('kategori', 'PENGURUS')
                    ->latest();
    }

    // Helper untuk mendapatkan URL foto
    public function getFotoUrlAttribute()
    {
        $foto = $this->fotoProfil;
        return $foto ? $foto->url : null;
    }

    // Helper untuk kategori jabatan
    public function getKategoriJabatanAttribute()
    {
        $jabatan = strtolower($this->jabatan);

        if (str_contains($jabatan, 'kepala') || str_contains($jabatan, 'ketua')) {
            return 'head-lecturer';
        } elseif (str_contains($jabatan, 'dosen') || str_contains($jabatan, 'pengajar')) {
            return 'lecturer';
        } elseif (str_contains($jabatan, 'laboran') || str_contains($jabatan, 'teknisi')) {
            return 'technician';
        }

        return 'other';
    }

    // Helper untuk warna berdasarkan kategori
    public function getWarnaKategoriAttribute()
    {
        switch ($this->kategori_jabatan) {
            case 'head-lecturer':
                return 'blue';
            case 'lecturer':
                return 'purple';
            case 'technician':
                return 'green';
            default:
                return 'gray';
        }
    }

    // Helper untuk icon badge
    public function getIconBadgeAttribute()
    {
        switch ($this->kategori_jabatan) {
            case 'head-lecturer':
                return 'star';
            case 'lecturer':
                return 'graduation-cap';
            case 'technician':
                return 'tools';
            default:
                return 'user';
        }
    }

    // Helper untuk warna badge
    public function getWarnaBadgeAttribute()
    {
        switch ($this->kategori_jabatan) {
            case 'head-lecturer':
                return 'yellow';
            case 'lecturer':
                return 'green';
            case 'technician':
                return 'blue';
            default:
                return 'gray';
        }
    }

    public function getKategoriJabatan()
    {
        $jabatan = strtolower($this->jabatan);
        if (str_contains($jabatan, 'kepala')) return 'head-lecturer';
        elseif (str_contains($jabatan, 'dosen') || str_contains($jabatan, 'pengajar')) return 'lecturer';
        elseif (str_contains($jabatan, 'laboran')) return 'technician';
        return 'other';
    }

    public function getIconBadge()
    {
        switch ($this->getKategoriJabatan()) {
            case 'head-lecturer': return 'star';
            case 'lecturer': return 'graduation-cap';
            case 'technician': return 'tools';
            default: return 'user';
        }
    }
}
