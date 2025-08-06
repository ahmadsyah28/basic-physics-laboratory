<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Facility extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'description',
        'facility_points',
        'images'
    ];

    protected $casts = [
        'facility_points' => 'array',
        'images' => 'array'
    ];

    // PERBAIKI: Accessor yang robust
    public function getFacilityPointsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    public function getImagesAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}
