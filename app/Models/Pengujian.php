<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengujian extends Model
{
    use HasFactory;

    protected $table = 'pengujian';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_penguji',
        'no_hp_penguji',
        'email_penguji',
        'organisasi_penguji',
        'deskripsi',
        'deskripsi_sampel',
        'total_harga',
        'tanggal_pengujian',
        'tanggal_diharapkan',
        'status'
    ];

    protected $casts = [
        'tanggal_pengujian' => 'datetime',
        'tanggal_diharapkan' => 'date',
        'total_harga' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    public function pengujianItems()
    {
        return $this->hasMany(PengujianItem::class, 'pengujian_id');
    }

    public function jenisPengujian()
    {
        return $this->belongsToMany(JenisPengujian::class, 'pengujian_item', 'pengujian_id', 'jenis_pengujian_id')
                    ->withPivot('jumlah_sampel')
                    ->withTimestamps();
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'PENDING' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu'],
            'PROCESSING' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Diproses'],
            'COMPLETED' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Selesai'],
            'CANCELLED' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Dibatalkan']
        ];

        return $badges[$this->status] ?? $badges['PENDING'];
    }
}
