<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PeminjamanItem extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'peminjamanItem';

    protected $fillable = [
        'peminjamanId',
        'alat_id',
        'jumlah'
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    /**
     * Relationship dengan peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjamanId');
    }

    /**
     * Relationship dengan alat
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Before creating, validate availability
        static::creating(function ($item) {
            $alat = Alat::find($item->alat_id);
            if (!$alat) {
                throw new \Exception('Alat tidak ditemukan');
            }

            // Check if requested quantity is available
            $peminjaman = Peminjaman::find($item->peminjamanId);
            if ($peminjaman) {
                $available = Peminjaman::isEquipmentAvailable(
                    $item->alat_id,
                    $peminjaman->tanggal_pinjam,
                    $peminjaman->tanggal_pengembalian,
                    $item->jumlah,
                    $peminjaman->id
                );

                if (!$available) {
                    throw new \Exception("Stok alat {$alat->nama} tidak mencukupi untuk periode yang diminta");
                }
            }
        });

        // Before updating, validate availability
        static::updating(function ($item) {
            $alat = Alat::find($item->alat_id);
            if (!$alat) {
                throw new \Exception('Alat tidak ditemukan');
            }

            // Check if updated quantity is available
            $peminjaman = Peminjaman::find($item->peminjamanId);
            if ($peminjaman) {
                $available = Peminjaman::isEquipmentAvailable(
                    $item->alat_id,
                    $peminjaman->tanggal_pinjam,
                    $peminjaman->tanggal_pengembalian,
                    $item->jumlah,
                    $peminjaman->id
                );

                if (!$available) {
                    throw new \Exception("Stok alat {$alat->nama} tidak mencukupi untuk periode yang diminta");
                }
            }
        });
    }
}
