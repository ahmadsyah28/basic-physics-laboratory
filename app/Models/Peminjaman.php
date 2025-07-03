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
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'namaPeminjam',
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
            self::STATUS_PROCESSING => 'Sedang Dipinjam',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PROCESSING => 'blue',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    /**
     * Check if loan is overdue
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === self::STATUS_PROCESSING) {
            return Carbon::now()->greaterThan($this->tanggal_pengembalian);
        }
        return false;
    }

    /**
     * Get days until return (negative if overdue)
     */
    public function getDaysUntilReturnAttribute()
    {
        if ($this->status === self::STATUS_PROCESSING) {
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
            'PROCESSING' => 'hand-holding',
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
        return $this->status === 'PENDING';
    }

    /**
     * Check if loan can be completed
     */
    public function canBeCompleted()
    {
        return $this->status === 'PROCESSING';
    }

    /**
     * Check if loan can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['PENDING', 'PROCESSING']);
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
            $this->status = self::STATUS_PROCESSING;
            $this->save();

            // Update stock for each item
            foreach ($this->items as $item) {
                $item->alat->pinjam($item->jumlah);
            }

            return true;
        }
        return false;
    }

    /**
     * Complete the loan (return items)
     */
    public function complete($itemConditions = [])
    {
        if ($this->status === self::STATUS_PROCESSING) {
            $this->status = self::STATUS_COMPLETED;
            $this->kondisi_pengembalian = json_encode($itemConditions);
            $this->save();

            // Update stock for each item
            foreach ($this->items as $item) {
                $kondisi = $itemConditions[$item->alat_id] ?? 'baik';
                $item->alat->returnItem($item->jumlah, $kondisi);
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
        if (in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING])) {
            $oldStatus = $this->status;
            $this->status = self::STATUS_CANCELLED;
            $this->save();

            // If was processing, return stock
            if ($oldStatus === self::STATUS_PROCESSING) {
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
        return $query->where('status', 'PROCESSING')
                     ->where('tanggal_pengembalian', '<', now());
    }

    /**
     * Scope for loans due soon (within 2 days)
     */
    public function scopeDueSoon($query)
    {
        return $query->where('status', 'PROCESSING')
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

        // Check for overlapping bookings in pending and processing status
        $query = PeminjamanItem::where('alat_id', $equipmentId)
            ->whereHas('peminjaman', function($q) use ($startDate, $endDate, $excludePeminjamanId) {
                $q->whereIn('status', ['PENDING', 'PROCESSING']);

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
            // If was processing, return stock
            if ($peminjaman->status === self::STATUS_PROCESSING) {
                foreach ($peminjaman->items as $item) {
                    $item->alat->kembalikan($item->jumlah);
                }
            }
        });
    }
}
