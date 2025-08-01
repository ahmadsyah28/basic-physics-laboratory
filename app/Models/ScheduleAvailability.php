<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ScheduleAvailability extends Model
{
    use HasFactory;

    protected $table = 'schedule_availability';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'date',
        'time_slot',
        'is_available',
        'reason',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time_slot' => 'datetime:H:i',
        'is_available' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Scope untuk filtering berdasarkan tanggal
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    // Scope untuk slot yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Scope untuk slot yang tidak tersedia
    public function scopeUnavailable($query)
    {
        return $query->where('is_available', false);
    }

    // Get formatted time
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->time_slot)->format('H:i');
    }

    // Get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d M Y');
    }

    // Get time slot label
    public function getTimeSlotLabelAttribute()
    {
        $time = Carbon::parse($this->time_slot);
        $endTime = $time->copy()->addHour();
        return $time->format('H:i') . ' - ' . $endTime->format('H:i');
    }

    // Static method untuk mengatur ketersediaan slot
    public static function setSlotAvailability($date, $timeSlot, $isAvailable, $reason = null, $notes = null)
    {
        return self::updateOrCreate(
            [
                'date' => $date,
                'time_slot' => $timeSlot,
            ],
            [
                'is_available' => $isAvailable,
                'reason' => $reason,
                'notes' => $notes,
            ]
        );
    }

    // Static method untuk mendapatkan slot yang tidak tersedia untuk tanggal tertentu
    public static function getUnavailableSlots($date)
    {
        return self::forDate($date)
                   ->unavailable()
                   ->pluck('time_slot')
                   ->map(function ($time) {
                       return Carbon::parse($time)->format('H:i');
                   })
                   ->toArray();
    }

    // Static method untuk cek apakah slot tersedia (gabungan dengan kunjungan)
    public static function isSlotAvailable($date, $timeSlot)
    {
        // Cek di schedule_availability dulu
        $scheduleAvailability = self::forDate($date)
                                   ->where('time_slot', $timeSlot)
                                   ->first();

        if ($scheduleAvailability && !$scheduleAvailability->is_available) {
            return false;
        }

        // Kemudian cek di kunjungan
        return Kunjungan::isTimeSlotAvailable($date, $timeSlot);
    }

    // Static method untuk batch set availability
    public static function setBatchAvailability($date, $slots = [])
    {
        $defaultSlots = [
            '08:00', '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00'
        ];

        foreach ($defaultSlots as $slot) {
            $isAvailable = in_array($slot, $slots);
            self::setSlotAvailability($date, $slot, $isAvailable);
        }
    }

    // Static method untuk mendapatkan semua slot dengan status availability
    public static function getAllSlotsWithStatus($date)
    {
        $defaultSlots = [
            '08:00' => '08:00 - 09:00',
            '09:00' => '09:00 - 10:00',
            '10:00' => '10:00 - 11:00',
            '11:00' => '11:00 - 12:00',
            '13:00' => '13:00 - 14:00', // Break 12:00-13:00 untuk makan siang
            '14:00' => '14:00 - 15:00',
            '15:00' => '15:00 - 16:00',
            '16:00' => '16:00 - 17:00',
            '17:00' => '17:00 - 18:00',
        ];

        $unavailableFromSchedule = self::getUnavailableSlots($date);
        $bookedFromVisits = Kunjungan::where('tanggal_kunjungan', $date)
                                   ->whereIn('status', [Kunjungan::STATUS_PENDING, Kunjungan::STATUS_PROCESSING])
                                   ->pluck('waktu_kunjungan')
                                   ->map(function ($time) {
                                       return Carbon::parse($time)->format('H:i');
                                   })
                                   ->toArray();

        $result = [];
        foreach ($defaultSlots as $time => $label) {
            $status = 'available';
            $reason = null;

            if (in_array($time, $unavailableFromSchedule)) {
                $status = 'blocked';
                $scheduleRecord = self::forDate($date)->where('time_slot', $time)->first();
                $reason = $scheduleRecord ? $scheduleRecord->reason : 'Diblokir oleh admin';
            } elseif (in_array($time, $bookedFromVisits)) {
                $status = 'booked';
                $reason = 'Sudah ada kunjungan terjadwal';
            }

            $result[$time] = [
                'label' => $label,
                'status' => $status,
                'reason' => $reason,
                'available' => $status === 'available'
            ];
        }

        return $result;
    }

    // Static method untuk statistik availability
    public static function getAvailabilityStats($startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $total = $query->count();
        $available = $query->clone()->available()->count();
        $unavailable = $query->clone()->unavailable()->count();

        return [
            'total' => $total,
            'available' => $available,
            'unavailable' => $unavailable,
            'availability_percentage' => $total > 0 ? round(($available / $total) * 100, 2) : 0
        ];
    }
}
