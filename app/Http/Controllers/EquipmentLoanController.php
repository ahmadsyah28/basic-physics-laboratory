<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\KategoriAlat;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EquipmentLoanController extends Controller
{
    public function index()
    {
        // Get equipment with search and filter
        $search = request('search');
        $kategori = request('kategori', 'all');
        $status = request('status', 'all');

        $equipments = Alat::with('kategoriAlat')
                         ->search($search)
                         ->when($kategori !== 'all', function($query) use ($kategori) {
                             return $query->byKategori($kategori);
                         })
                         ->when($status !== 'all', function($query) use ($status) {
                             if ($status === 'available') {
                                 return $query->where('jumlah_tersedia', '>', 0);
                             } elseif ($status === 'maintenance') {
                                 return $query->where('jumlah_rusak', '>', 0);
                             } elseif ($status === 'unavailable') {
                                 return $query->where('jumlah_tersedia', '=', 0)
                                             ->where('jumlah_rusak', '=', 0);
                             }
                         })
                         ->orderBy('nama')
                         ->get()
                         ->map(function($alat) {
                             return $this->formatEquipmentForView($alat);
                         });

        // Get categories from database
        $categories = ['all' => 'Semua Kategori'];
        $dbCategories = KategoriAlat::orderBy('nama_kategori')->get();
        foreach ($dbCategories as $cat) {
            $categories[$cat->nama_kategori] = $cat->nama_kategori;
        }

        return view('services.equipment-loan', compact('equipments', 'categories'));
    }

    public function show($id)
    {
        $alat = Alat::with('kategoriAlat')->find($id);

        if (!$alat) {
            abort(404, 'Alat tidak ditemukan');
        }

        $equipment = $this->formatEquipmentForView($alat);

        return view('services.equipment-detail', compact('equipment'));
    }

    public function requestLoan(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:20',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'purpose' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check if this is a bulk request
        if ($request->has('equipment_ids') && $request->has('equipment_quantities')) {
            return $this->processBulkLoan($request);
        }

        return redirect()->back()->withErrors(['equipment' => 'Data alat tidak valid.']);
    }

    private function processBulkLoan(Request $request)
    {
        // Additional validation for bulk requests
        $request->validate([
            'equipment_ids' => 'required|string',
            'equipment_quantities' => 'required|string'
        ]);

        try {
            // Parse equipment data
            $equipmentIds = json_decode($request->equipment_ids, true);
            $equipmentQuantities = json_decode($request->equipment_quantities, true);

            if (!is_array($equipmentIds) || empty($equipmentIds) || !is_array($equipmentQuantities)) {
                return redirect()->back()->withErrors(['equipment_ids' => 'Data alat tidak valid.']);
            }

            DB::beginTransaction();

            // Validate all equipment availability
            $unavailableEquipment = [];
            $totalUnits = 0;
            $equipmentDetails = [];

            foreach ($equipmentIds as $alatId) {
                $alat = Alat::find($alatId);
                if (!$alat) {
                    $unavailableEquipment[] = "Alat dengan ID {$alatId} tidak ditemukan";
                    continue;
                }

                $requestedQuantity = $equipmentQuantities[$alatId] ?? 1;
                $totalUnits += $requestedQuantity;

                // Check if equipment can be borrowed
                if (!$alat->canBeBorrowed($requestedQuantity)) {
                    $unavailableEquipment[] = "{$alat->nama}: Diminta {$requestedQuantity} unit, tersedia {$alat->jumlah_tersedia} unit";
                } else {
                    $equipmentDetails[] = [
                        'alat' => $alat,
                        'quantity' => $requestedQuantity
                    ];
                }
            }

            if (!empty($unavailableEquipment)) {
                DB::rollBack();
                return redirect()->back()->withErrors([
                    'availability' => "Ketersediaan tidak mencukupi:\n" . implode("\n", $unavailableEquipment)
                ])->withInput();
            }

            // Create peminjaman record
            $peminjaman = Peminjaman::create([
                'namaPeminjam' => $request->name,
                'noHp' => $request->phone,
                'tujuanPeminjaman' => $request->purpose,
                'tanggal_pinjam' => $request->start_date,
                'tanggal_pengembalian' => $request->end_date,
                'status' => Peminjaman::STATUS_PENDING,
                'is_mahasiswa_usk' => $this->isMahasiswaUSK($request->student_id)
            ]);

            // Create peminjaman items
            foreach ($equipmentDetails as $detail) {
                PeminjamanItem::create([
                    'peminjamanId' => $peminjaman->id,
                    'alat_id' => $detail['alat']->id,
                    'jumlah' => $detail['quantity']
                ]);
            }

            DB::commit();

            // Generate reference number and summary
            $referenceNumber = $peminjaman->generateReferenceNumber();
            $equipmentSummary = collect($equipmentDetails)->map(function($detail) {
                return "{$detail['alat']->nama} ({$detail['quantity']} unit)";
            })->implode(', ');

            // Log the request
            Log::info('Equipment Loan Request Created', [
                'peminjaman_id' => $peminjaman->id,
                'reference_number' => $referenceNumber,
                'borrower' => $request->name,
                'equipment_count' => count($equipmentDetails),
                'total_units' => $totalUnits
            ]);

            return redirect()->back()->with('success',
                "Permintaan peminjaman berhasil dikirim!\n\n" .
                "Detail:\n" .
                "- Nomor Referensi: {$referenceNumber}\n" .
                "- {$peminjaman->total_types} jenis alat\n" .
                "- {$peminjaman->total_quantity} unit total\n" .
                "- Periode: " . Carbon::parse($request->start_date)->format('d M Y') . " - " . Carbon::parse($request->end_date)->format('d M Y') . "\n" .
                "- Alat: {$equipmentSummary}\n\n" .
                "Kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi dan penjadwalan briefing."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Equipment Loan Request Failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['system' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.'])
                ->withInput();
        }
    }

    /**
     * Check equipment availability for specific dates (AJAX endpoint)
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'equipment_ids' => 'required|array',
            'equipment_quantities' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        try {
            $availability = [];

            foreach ($request->equipment_ids as $index => $alatId) {
                $alat = Alat::find($alatId);
                $quantity = $request->equipment_quantities[$index] ?? 1;

                if ($alat) {
                    $isAvailable = $alat->canBeBorrowed($quantity);

                    $availability[$alatId] = [
                        'available' => $isAvailable,
                        'requested_quantity' => $quantity,
                        'stock_available' => $alat->jumlah_tersedia,
                        'equipment_name' => $alat->nama
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'availability' => $availability,
                'period' => [
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengecek ketersediaan.'
            ], 500);
        }
    }

    /**
     * Get loan history (for admin dashboard)
     */
    public function getLoanHistory(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $peminjamans = Peminjaman::with(['items.alat'])
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('namaPeminjam', 'like', '%' . $search . '%')
                      ->orWhere('noHp', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $peminjamans->map(function($peminjaman) {
                return [
                    'id' => $peminjaman->id,
                    'reference_number' => $peminjaman->generateReferenceNumber(),
                    'borrower_name' => $peminjaman->namaPeminjam,
                    'phone' => $peminjaman->noHp,
                    'equipment_count' => $peminjaman->total_types,
                    'total_units' => $peminjaman->total_quantity,
                    'equipment_list' => $peminjaman->items->map(function($item) {
                        return [
                            'name' => $item->alat->nama,
                            'quantity' => $item->jumlah
                        ];
                    }),
                    'start_date' => $peminjaman->tanggal_pinjam->format('Y-m-d'),
                    'end_date' => $peminjaman->tanggal_pengembalian->format('Y-m-d'),
                    'status' => $peminjaman->status,
                    'status_name' => $peminjaman->status_name,
                    'is_overdue' => $peminjaman->is_overdue,
                    'days_until_return' => $peminjaman->days_until_return
                ];
            }),
            'pagination' => [
                'current_page' => $peminjamans->currentPage(),
                'last_page' => $peminjamans->lastPage(),
                'per_page' => $peminjamans->perPage(),
                'total' => $peminjamans->total()
            ]
        ]);
    }

    // =====================================
    // PRIVATE METHODS - TANPA HELPER
    // =====================================

    /**
     * Format equipment data for view
     */
    private function formatEquipmentForView($alat)
    {
        // Determine status
        $status = 'available';
        if ($alat->jumlah_rusak > 0) {
            $status = 'maintenance';
        } elseif ($alat->jumlah_tersedia == 0) {
            $status = 'unavailable';
        }

        return [
            'id' => $alat->id,
            'name' => $alat->nama,
            'model' => $alat->kode,
            'image' => $alat->image_url ? basename($alat->image_url) : 'default.jpg',
            'category' => $alat->nama_kategori,
            'status' => $status,
            'quantity_total' => $alat->stok,
            'quantity_available' => $alat->jumlah_tersedia,
            'quantity_borrowed' => $alat->jumlah_dipinjam,
            'quantity_damaged' => $alat->jumlah_rusak,
            'description' => $alat->deskripsi,
            'specifications' => $this->extractSpecifications($alat->deskripsi),
            'loan_duration' => '1-7 hari',
            'icon' => $this->getCategoryIcon($alat->nama_kategori),
            'price' => $alat->harga,
        ];
    }

    /**
     * Extract specifications from description
     */
    private function extractSpecifications($description)
    {
        $specs = [];

        if (strpos($description, 'Spesifikasi:') !== false) {
            $parts = explode('Spesifikasi:', $description);
            if (count($parts) > 1) {
                $specText = trim($parts[1]);
                $specs = array_map('trim', explode(',', $specText));
                $specs = array_filter($specs);
            }
        }

        return $specs;
    }

    /**
     * Get category icon
     */
    private function getCategoryIcon($category)
    {
        $icons = [
            'Elektronika' => 'fas fa-microchip',
            'Pengukuran' => 'fas fa-ruler',
            'Generator' => 'fas fa-bolt',
            'Power' => 'fas fa-battery-full',
            'Analisis' => 'fas fa-chart-line',
            'Optik' => 'fas fa-eye',
            'Mekanik' => 'fas fa-cog',
            'Thermal' => 'fas fa-thermometer-half'
        ];

        return $icons[$category] ?? 'fas fa-cog';
    }

    /**
     * Determine if student ID belongs to USK student
     */
    private function isMahasiswaUSK($studentId)
    {
        if (preg_match('/^(1[0-9]{9}|2[0-9]{9})$/', $studentId)) {
            return true;
        }

        if (preg_match('/^[0-9]{10}$/', $studentId)) {
            return true;
        }

        return false;
    }
}
