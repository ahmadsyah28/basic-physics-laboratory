<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'peminjaman';

    protected $fillable = [
        'namaPeminjam',
        'noHp',
        'tujuanPeminjaman',
        'tanggal_pinjam',
        'tanggal_pengembalian',
        'status',
        'email',
        'nim_nip',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
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
            self::STATUS_PROCESSING => 'Sedang Dipinjam',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Get formatted status name
     */
    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get duration in days
     */
    public function getDurationDaysAttribute()
    {
        return $this->tanggal_pinjam->diffInDays($this->tanggal_pengembalian);
    }

    /**
     * Check if loan is overdue
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status !== self::STATUS_PROCESSING) {
            return false;
        }

        return $this->tanggal_pengembalian->isPast();
    }

    /**
     * Get days until return or overdue days
     */
    public function getDaysUntilReturnAttribute()
    {
        if ($this->status !== self::STATUS_PROCESSING) {
            return null;
        }

        $now = Carbon::now();
        if ($this->tanggal_pengembalian->isFuture()) {
            return $now->diffInDays($this->tanggal_pengembalian);
        } else {
            return -$now->diffInDays($this->tanggal_pengembalian); // Negative for overdue
        }
    }

    /**
     * Get total quantity of all items
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('jumlah');
    }

    /**
     * Get total equipment types count
     */
    public function getTotalTypesAttribute()
    {
        return $this->items->count();
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
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_pinjam', [$startDate, $endDate]);
    }

    /**
     * Scope untuk peminjaman yang sedang aktif
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Scope untuk peminjaman yang terlambat
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_PROCESSING)
                    ->where('tanggal_pengembalian', '<', Carbon::now());
    }

    /**
     * Relationship dengan peminjaman items
     */
    public function items()
    {
        return $this->hasMany(PeminjamanItem::class, 'peminjamanId');
    }

    /**
     * Get all alat through items relationship
     */
    public function alats()
    {
        return $this->hasManyThrough(
            Alat::class,
            PeminjamanItem::class,
            'peminjamanId',
            'id',
            'id',
            'alat_id'
        );
    }

    /**
     * Generate reference number
     */
    public function generateReferenceNumber()
    {
        $prefix = match($this->total_types) {
            1 => 'SLR', // Single Loan Request
            default => 'BLR' // Bulk Loan Request
        };

        $date = $this->created_at->format('Ymd');
        $sequence = str_pad($this->id ? substr($this->id, 0, 3) : '001', 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$date}-{$sequence}";
    }

    /**
     * Check if equipment is available for the requested period
     */
    public static function isEquipmentAvailable($alatId, $startDate, $endDate, $quantity = 1, $excludePeminjamanId = null)
    {
        $alat = Alat::find($alatId);
        if (!$alat || $alat->isBroken) {
            return false;
        }

        // Check overlapping bookings
        $overlappingBookings = self::where(function($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                  ->orWhereBetween('tanggal_pengembalian', [$startDate, $endDate])
                  ->orWhere(function($q) use ($startDate, $endDate) {
                      $q->where('tanggal_pinjam', '<=', $startDate)
                        ->where('tanggal_pengembalian', '>=', $endDate);
                  });
        })
        ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING])
        ->when($excludePeminjamanId, function($query) use ($excludePeminjamanId) {
            $query->where('id', '!=', $excludePeminjamanId);
        })
        ->with(['items' => function($query) use ($alatId) {
            $query->where('alat_id', $alatId);
        }])
        ->get();

        $bookedQuantity = $overlappingBookings->sum(function($peminjaman) {
            return $peminjaman->items->sum('jumlah');
        });

        return ($alat->stok - $bookedQuantity) >= $quantity;
    }
}
