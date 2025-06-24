<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JenisPengujian extends Model
{
    use HasFactory;

    protected $table = 'jenis_pengujian';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_pengujian',
        'harga_per_sampel',
        'is_available',
        'deskripsi',
        'durasi',
        'aplikasi',
        'icon'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'aplikasi' => 'array',
        'harga_per_sampel' => 'integer'
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
        return $this->hasMany(PengujianItem::class, 'jenis_pengujian_id');
    }

    public function pengujian()
    {
        return $this->belongsToMany(Pengujian::class, 'pengujian_item', 'jenis_pengujian_id', 'pengujian_id')
                    ->withPivot('jumlah_sampel')
                    ->withTimestamps();
    }
}
