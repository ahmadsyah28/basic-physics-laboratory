<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kunjungan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kunjungan';

    protected $fillable = [
        'namaPengunjung',
        'email',
        'phone',
        'institution',
        'tujuan',
        'tanggal_kunjungan',
        'waktu_kunjungan',
        'jumlahPengunjung',
        'catatan_tambahan',
        'status'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'waktu_kunjungan' => 'datetime:H:i',
    ];

    // Status constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_PROCESSING => 'Sedang Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    /**
     * Check if the time slot is available for a specific date
     */
    public static function isTimeSlotAvailable($date, $time, $excludeId = null)
    {
        $query = self::where('tanggal_kunjungan', $date)
                    ->where('waktu_kunjungan', $time)
                    ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->count() === 0;
    }

    /**
     * Get available time slots for a specific date
     */
    public static function getAvailableTimeSlots($date)
    {
        $allTimeSlots = [
            '08:00' => '08:00 - 09:00',
            '09:00' => '09:00 - 10:00',
            '10:00' => '10:00 - 11:00',
            '11:00' => '11:00 - 12:00',
            '13:00' => '13:00 - 14:00',
            '14:00' => '14:00 - 15:00',
            '15:00' => '15:00 - 16:00',
        ];

        $bookedSlots = self::where('tanggal_kunjungan', $date)
                          ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING])
                          ->pluck('waktu_kunjungan')
                          ->map(function($time) {
                              return $time instanceof \Carbon\Carbon ? $time->format('H:i') : $time;
                          })
                          ->toArray();

        return array_filter($allTimeSlots, function($key) use ($bookedSlots) {
            return !in_array($key, $bookedSlots);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('tanggal_kunjungan', $date);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get formatted status name
     */
    public function getStatusName()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }
}
