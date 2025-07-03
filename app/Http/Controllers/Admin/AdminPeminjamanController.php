<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPeminjamanController extends Controller
{
    /**
     * Display a listing of peminjaman
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['items.alat'])
                          ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('borrower_type')) {
            $query->byBorrowerType($request->borrower_type);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        $peminjamans = $query->paginate(15)->appends($request->query());

        // Statistics
        $statistics = [
            'total' => Peminjaman::count(),
            'pending' => Peminjaman::where('status', 'PENDING')->count(),
            'processing' => Peminjaman::where('status', 'PROCESSING')->count(),
            'completed' => Peminjaman::where('status', 'COMPLETED')->count(),
            'overdue' => Peminjaman::overdue()->count(),
            'due_soon' => Peminjaman::dueSoon()->count(),
        ];

        return view('admin.peminjaman.index', compact('peminjamans', 'statistics'));
    }

    /**
     * Display the specified peminjaman
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['items.alat']);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Update status of peminjaman
     */
    public function updateStatus(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:PENDING,PROCESSING,COMPLETED,CANCELLED',
            'item_conditions' => 'sometimes|array',
            'cancel_reason' => 'sometimes|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $peminjaman->status;
            $newStatus = $request->status;

            if ($newStatus === 'PROCESSING' && $peminjaman->canBeApproved()) {
                $success = $peminjaman->approve();
                if (!$success) {
                    throw new \Exception('Gagal menyetujui peminjaman');
                }
                $message = 'Peminjaman berhasil disetujui';
            }
            elseif ($newStatus === 'COMPLETED' && $peminjaman->canBeCompleted()) {
                $itemConditions = $request->item_conditions ?? [];
                $success = $peminjaman->complete($itemConditions);
                if (!$success) {
                    throw new \Exception('Gagal menyelesaikan peminjaman');
                }
                $message = 'Peminjaman berhasil diselesaikan';
            }
            elseif ($newStatus === 'CANCELLED' && $peminjaman->canBeCancelled()) {
                $success = $peminjaman->cancel($request->cancel_reason);
                if (!$success) {
                    throw new \Exception('Gagal membatalkan peminjaman');
                }
                $message = 'Peminjaman berhasil dibatalkan';
            }
            else {
                throw new \Exception('Status tidak valid atau tidak dapat diubah');
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete peminjaman
     */
    public function destroy(Peminjaman $peminjaman)
    {
        try {
            DB::beginTransaction();

            // Only allow deletion if status is CANCELLED or COMPLETED
            if (!in_array($peminjaman->status, ['CANCELLED', 'COMPLETED'])) {
                throw new \Exception('Hanya peminjaman dengan status CANCELLED atau COMPLETED yang dapat dihapus');
            }

            $peminjaman->delete();

            DB::commit();
            return redirect()->route('admin.peminjaman.index')
                           ->with('success', 'Data peminjaman berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get dashboard data for peminjaman
     */
    public function getDashboardData()
    {
        $data = [
            'pending_count' => Peminjaman::where('status', 'PENDING')->count(),
            'processing_count' => Peminjaman::where('status', 'PROCESSING')->count(),
            'overdue_count' => Peminjaman::overdue()->count(),
            'due_soon_count' => Peminjaman::dueSoon()->count(),
            'recent_loans' => Peminjaman::with(['items.alat'])
                                       ->orderBy('created_at', 'desc')
                                       ->limit(5)
                                       ->get(),
            'popular_equipment' => DB::table('peminjamanItem as pi')
                                    ->join('alat as a', 'pi.alat_id', '=', 'a.id')
                                    ->join('peminjaman as p', 'pi.peminjamanId', '=', 'p.id')
                                    ->where('p.created_at', '>=', Carbon::now()->subDays(30))
                                    ->select('a.nama', DB::raw('SUM(pi.jumlah) as total_borrowed'))
                                    ->groupBy('a.id', 'a.nama')
                                    ->orderBy('total_borrowed', 'desc')
                                    ->limit(5)
                                    ->get()
        ];

        return response()->json($data);
    }

    /**
     * Export peminjaman data
     */
    public function export(Request $request)
    {
        $query = Peminjaman::with(['items.alat']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('borrower_type')) {
            $query->byBorrowerType($request->borrower_type);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        $peminjamans = $query->get();

        $filename = 'peminjaman_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($peminjamans) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Nama Peminjam',
                'Tipe Peminjam',
                'No. HP',
                'Tujuan',
                'Tanggal Pinjam',
                'Tanggal Kembali',
                'Status',
                'Total Jenis Alat',
                'Total Unit',
                'Detail Alat'
            ]);

            foreach ($peminjamans as $peminjaman) {
                fputcsv($file, [
                    $peminjaman->id,
                    $peminjaman->namaPeminjam,
                    $peminjaman->borrower_type,
                    $peminjaman->noHp,
                    $peminjaman->tujuanPeminjaman,
                    $peminjaman->tanggal_pinjam->format('Y-m-d H:i'),
                    $peminjaman->tanggal_pengembalian->format('Y-m-d H:i'),
                    $peminjaman->status_name,
                    $peminjaman->total_types,
                    $peminjaman->total_quantity,
                    $peminjaman->equipment_summary
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'peminjaman_ids' => 'required|array',
            'peminjaman_ids.*' => 'exists:peminjaman,id',
            'bulk_status' => 'required|in:PENDING,PROCESSING,COMPLETED,CANCELLED'
        ]);

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errorCount = 0;

            foreach ($request->peminjaman_ids as $id) {
                $peminjaman = Peminjaman::find($id);

                if (!$peminjaman) {
                    $errorCount++;
                    continue;
                }

                $oldStatus = $peminjaman->status;
                $newStatus = $request->bulk_status;

                if ($newStatus === 'PROCESSING' && $peminjaman->canBeApproved()) {
                    $peminjaman->approve();
                    $successCount++;
                } elseif ($newStatus === 'CANCELLED' && $peminjaman->canBeCancelled()) {
                    $peminjaman->cancel();
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }

            DB::commit();

            $message = "Berhasil mengupdate {$successCount} peminjaman";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} gagal diupdate";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
