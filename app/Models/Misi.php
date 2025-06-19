<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Misi extends Model
{
    use HasUuids;
    protected $table = 'misi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'pointMisi',
    ];

    public function profilLaboratorium(): HasMany
    {
        return $this->hasMany(ProfilLaboratorium::class, 'misiId');
    }
}
