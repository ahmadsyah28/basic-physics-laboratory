<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengujianItem extends Model
{
    use HasFactory;

    protected $table = 'pengujian_item';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'jenis_pengujian_id',
        'pengujian_id',
        'jumlah_sampel'
    ];

    protected $casts = [
        'jumlah_sampel' => 'integer'
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

    public function jenisPengujian()
    {
        return $this->belongsTo(JenisPengujian::class, 'jenis_pengujian_id');
    }

    public function pengujian()
    {
        return $this->belongsTo(Pengujian::class, 'pengujian_id');
    }
}
