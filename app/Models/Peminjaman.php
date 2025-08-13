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

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'namaPeminjam',
        'student_id',
        'email',
        'instansi',
        'is_mahasiswa_usk',
        'noHp',
        'tujuanPeminjaman',
        'tanggal_pinjam',
        'tanggal_pengembalian',
        'kondisi_pengembalian',
        'status'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'is_mahasiswa_usk' => 'boolean'
    ];

    /**
     * Relationship with PeminjamanItem
     */
    public function items()
    {
        return $this->hasMany(PeminjamanItem::class, 'peminjamanId', 'id');
    }

    /**
     * Get total types of equipment borrowed
     */
    public function getTotalTypesAttribute()
    {
        return $this->items()->count();
    }

    /**
     * Get total quantity of all equipment borrowed
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items()->sum('jumlah');
    }

    /**
     * Get status name in Indonesian
     */
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_ACTIVE => 'Sedang Dipinjam',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    /**
     * Get status text (alias for status name)
     */
    public function getStatusTextAttribute()
    {
        return $this->status_name;
    }

    /**
     * Get formatted start date
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->tanggal_pinjam ? $this->tanggal_pinjam->format('d M Y') : '-';
    }

    /**
     * Get formatted end date
     */
    public function getFormattedEndDateAttribute()
    {
        return $this->tanggal_pengembalian ? $this->tanggal_pengembalian->format('d M Y') : '-';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_ACTIVE => 'blue',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    /**
     * Get status badge color class
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_ACTIVE => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_COMPLETED => 'bg-purple-100 text-purple-800 border-purple-200',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Get progress percentage for tracking
     */
    public function getProgressPercentageAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 25,
            self::STATUS_APPROVED => 50,
            self::STATUS_ACTIVE => 75,
            self::STATUS_COMPLETED => 100,
            self::STATUS_CANCELLED => 0,
            default => 0
        };
    }

    /**
     * Check if loan is overdue
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === self::STATUS_ACTIVE) {
            return Carbon::now()->greaterThan($this->tanggal_pengembalian);
        }
        return false;
    }

    /**
     * Get days until return (negative if overdue)
     */
    public function getDaysUntilReturnAttribute()
    {
        if ($this->status === self::STATUS_ACTIVE) {
            return Carbon::now()->diffInDays($this->tanggal_pengembalian, false);
        }
        return null;
    }

    /**
     * Generate reference number
     */
    public function generateReferenceNumber()
    {
        return 'PJM-' . $this->created_at->format('Ymd') . '-' . str_pad(substr($this->id, 0, 4), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get reference number
     */
    public function getReferenceNumberAttribute()
    {
        return $this->generateReferenceNumber();
    }

    /**
     * Get borrower type (Mahasiswa USK or External)
     */
    public function getBorrowerTypeAttribute()
    {
        return $this->is_mahasiswa_usk ? 'Mahasiswa USK' : 'Eksternal';
    }

    /**
     * Get status icon for display
     */
    public function getStatusIcon()
    {
        return match($this->status) {
            'PENDING' => 'clock',
            'APPROVED' => 'check-circle',
            'ACTIVE' => 'hand-holding',
            'COMPLETED' => 'check-circle',
            'CANCELLED' => 'times-circle',
            default => 'question-circle'
        };
    }

    /**
     * Get borrower type badge class
     */
    public function getBorrowerTypeBadgeClass()
    {
        return $this->is_mahasiswa_usk ? 'usk-badge' : 'external-badge';
    }

    /**
     * Get borrower type text
     */
    public function getBorrowerTypeText()
    {
        return $this->is_mahasiswa_usk ? 'Mahasiswa USK' : 'Eksternal';
    }

    /**
     * Get priority level based on due date
     */
    public function getPriorityLevel()
    {
        if ($this->is_overdue) {
            return 'high';
        } elseif ($this->days_until_return <= 2 && $this->days_until_return > 0) {
            return 'medium';
        }
        return 'low';
    }

    /**
     * Get equipment summary text for display
     */
    public function getEquipmentSummaryText()
    {
        $summary = $this->items->map(function($item) {
            return $item->alat->nama . ' (' . $item->jumlah . ' unit)';
        })->take(3)->implode(', ');

        if ($this->items->count() > 3) {
            $summary .= ' +' . ($this->items->count() - 3) . ' lainnya';
        }

        return $summary;
    }

    /**
     * Check if loan can be approved
     */
    public function canBeApproved()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if loan can be completed
     */
    public function canBeCompleted()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if loan can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_ACTIVE]);
    }

    /**
     * Get formatted period text
     */
    public function getPeriodText()
    {
        return $this->tanggal_pinjam->format('d M Y') . ' - ' . $this->tanggal_pengembalian->format('d M Y');
    }

    /**
     * Get status badge class for CSS styling
     */
    public function getStatusBadgeClass()
    {
        return 'status-' . strtolower($this->status);
    }

    /**
     * Approve the loan request
     */
    public function approve()
    {
        if ($this->status === self::STATUS_PENDING) {
            // Check equipment availability first
            foreach ($this->items as $item) {
                if ($item->alat->jumlah_tersedia < $item->jumlah) {
                    return false; // Not enough stock
                }
            }

            $this->status = self::STATUS_APPROVED;
            $this->save();

            // Reserve equipment (update stock)
            foreach ($this->items as $item) {
                $item->alat->pinjam($item->jumlah);
            }

            return true;
        }
        return false;
    }

    /**
     * Mark as active (equipment taken)
     */
    public function markAsActive()
    {
        if ($this->status === self::STATUS_APPROVED) {
            $this->status = self::STATUS_ACTIVE;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Complete the loan (return items)
     */
    public function complete($itemConditions = [])
{
    if ($this->status === self::STATUS_ACTIVE) {
        // 1. Update status peminjaman
        $this->status = self::STATUS_COMPLETED;

        // 2. Simpan kondisi pengembalian sebagai JSON (format baru)
        $this->kondisi_pengembalian = json_encode($itemConditions);

        // 3. Save peminjaman
        $this->save();

        // 4. Update stok untuk setiap item dengan partial conditions
        foreach ($this->items as $item) {
            $conditions = $itemConditions[$item->alat_id] ?? [];
            $baikQty = (int)($conditions['baik'] ?? $item->jumlah);
            $rusakQty = (int)($conditions['rusak'] ?? 0);

            // Validasi total
            if ($baikQty + $rusakQty !== $item->jumlah) {
                continue; // Skip this item if validation fails
            }

            // Return items with partial conditions
            $item->alat->returnItemPartial($baikQty, $rusakQty);
        }

        return true;
    }
    return false;
}

    /**
     * Cancel the loan
     */
    public function cancel($reason = null)
    {
        if (in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_ACTIVE])) {
            $oldStatus = $this->status;
            $this->status = self::STATUS_CANCELLED;
            $this->save();

            // If was approved or active, return stock
            if (in_array($oldStatus, [self::STATUS_APPROVED, self::STATUS_ACTIVE])) {
                foreach ($this->items as $item) {
                    $item->alat->kembalikan($item->jumlah);
                }
            }

            return true;
        }
        return false;
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope for filtering by borrower type
     */
    public function scopeByBorrowerType($query, $type)
    {
        if ($type === '1') {
            return $query->where('is_mahasiswa_usk', true);
        } elseif ($type === '0') {
            return $query->where('is_mahasiswa_usk', false);
        }
        return $query;
    }

    /**
     * Scope for filtering overdue loans
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('tanggal_pengembalian', '<', now());
    }

    /**
     * Scope for loans due soon (within 2 days)
     */
    public function scopeDueSoon($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->whereBetween('tanggal_pengembalian', [
                         now(),
                         now()->addDays(2)
                     ]);
    }

    /**
     * Scope to search peminjaman
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('namaPeminjam', 'like', '%' . $search . '%')
                  ->orWhere('noHp', 'like', '%' . $search . '%')
                  ->orWhere('tujuanPeminjaman', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * Check if equipment is available for the given period
     */
    public static function isEquipmentAvailable($equipmentId, $startDate, $endDate, $quantity = 1, $excludePeminjamanId = null)
    {
        $alat = Alat::find($equipmentId);
        if (!$alat) {
            return false;
        }

        // Check if there's enough stock available
        if ($alat->jumlah_tersedia < $quantity) {
            return false;
        }

        // Check for overlapping bookings in approved and active status
        $query = PeminjamanItem::where('alat_id', $equipmentId)
            ->whereHas('peminjaman', function($q) use ($startDate, $endDate, $excludePeminjamanId) {
                $q->whereIn('status', [self::STATUS_APPROVED, self::STATUS_ACTIVE]);

                if ($excludePeminjamanId) {
                    $q->where('id', '!=', $excludePeminjamanId);
                }

                $q->where(function($q2) use ($startDate, $endDate) {
                    $q2->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                       ->orWhereBetween('tanggal_pengembalian', [$startDate, $endDate])
                       ->orWhere(function($q3) use ($startDate, $endDate) {
                           $q3->where('tanggal_pinjam', '<=', $startDate)
                              ->where('tanggal_pengembalian', '>=', $endDate);
                       });
                });
            });

        $overlappingBookings = $query->sum('jumlah');
        $availableForPeriod = $alat->stok - $overlappingBookings;

        return $availableForPeriod >= $quantity;
    }

    /**
     * Get equipment summary for display
     */
    public function getEquipmentSummaryAttribute()
    {
        return $this->items->map(function($item) {
            return $item->alat->nama . ' (' . $item->jumlah . ' unit)';
        })->implode(', ');
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // When peminjaman is being deleted, make sure to clean up properly
        static::deleting(function ($peminjaman) {
            // If was approved or active, return stock
            if (in_array($peminjaman->status, [self::STATUS_APPROVED, self::STATUS_ACTIVE])) {
                foreach ($peminjaman->items as $item) {
                    $item->alat->kembalikan($item->jumlah);
                }
            }
        });
    }
}
