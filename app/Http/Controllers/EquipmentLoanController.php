<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\KategoriAlat;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EquipmentLoanController extends Controller
{
    /**
     * Display equipment loan page with filters
     */
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

    /**
     * Show equipment detail
     */
    public function show($id)
    {
        $alat = Alat::with('kategoriAlat')->find($id);

        if (!$alat) {
            abort(404, 'Alat tidak ditemukan');
        }

        $equipment = $this->formatEquipmentForView($alat);

        return view('services.equipment-detail', compact('equipment'));
    }

    /**
     * Process loan request with WhatsApp integration
     */
    public function requestLoan(Request $request)
    {
        // Check if request is AJAX
        $isAjax = $request->ajax() || $request->wantsJson();

        // Basic validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:20',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'purpose' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'borrower_type' => 'required|in:mahasiswa_usk,eksternal',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'student_id.required' => 'NIM/NIP wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'phone.required' => 'Nomor telepon wajib diisi',
            'purpose.required' => 'Tujuan penggunaan wajib diisi',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu',
            'end_date.required' => 'Tanggal selesai wajib diisi',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
            'borrower_type.required' => 'Status peminjam wajib dipilih',
            'instansi.required_if' => 'Instansi wajib diisi untuk peminjam eksternal'
        ]);

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat kesalahan dalam pengisian form.',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.');
        }

        // Check if this is a bulk request
        if ($request->has('equipment_ids') && $request->has('equipment_quantities')) {
            return $this->processBulkLoan($request, $isAjax);
        }

        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Data alat tidak valid.'
            ], 422);
        }

        return redirect()->back()->withErrors(['equipment' => 'Data alat tidak valid.']);
    }

    /**
     * Process bulk loan request
     */
    private function processBulkLoan(Request $request, $isAjax = false)
    {
        // Additional validation for bulk requests
        $bulkValidator = Validator::make($request->all(), [
            'equipment_ids' => 'required|string',
            'equipment_quantities' => 'required|string'
        ]);

        if ($bulkValidator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data alat tidak valid.',
                    'errors' => $bulkValidator->errors()
                ], 422);
            }

            return redirect()->back()->withErrors(['equipment_ids' => 'Data alat tidak valid.']);
        }

        try {
            // Parse equipment data
            $equipmentIds = json_decode($request->equipment_ids, true);
            $equipmentQuantities = json_decode($request->equipment_quantities, true);

            if (!is_array($equipmentIds) || empty($equipmentIds) || !is_array($equipmentQuantities)) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data alat tidak valid.'
                    ], 422);
                }

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

                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => "Ketersediaan tidak mencukupi:\n" . implode("\n", $unavailableEquipment)
                    ], 422);
                }

                return redirect()->back()->withErrors([
                    'availability' => "Ketersediaan tidak mencukupi:\n" . implode("\n", $unavailableEquipment)
                ])->withInput();
            }

            // Create peminjaman record
            $peminjaman = Peminjaman::create([
                'namaPeminjam' => $request->name,
                'student_id' => $request->student_id,
                'email' => $request->email,
                'noHp' => $request->phone,
                'instansi' => $request->borrower_type === 'eksternal' ? $request->instansi : null,
                'tujuanPeminjaman' => $request->purpose,
                'tanggal_pinjam' => $request->start_date,
                'tanggal_pengembalian' => $request->end_date,
                'status' => Peminjaman::STATUS_PENDING,
                'is_mahasiswa_usk' => $request->borrower_type === 'mahasiswa_usk'
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
            $referenceNumber = $peminjaman->reference_number;
            $trackingUrl = route('equipment.track', ['id' => $peminjaman->id]);
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

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan peminjaman berhasil disimpan!',
                    'reference_id' => $referenceNumber,
                    'tracking_url' => $trackingUrl,
                    'loan_data' => [
                        'id' => $peminjaman->id,
                        'reference_number' => $referenceNumber,
                        'name' => $peminjaman->namaPeminjam,
                        'student_id' => $peminjaman->student_id,
                        'instansi' => $peminjaman->instansi,
                        'email' => $peminjaman->email,
                        'phone' => $peminjaman->noHp,
                        'start_date' => $peminjaman->tanggal_pinjam->format('d M Y'),
                        'end_date' => $peminjaman->tanggal_pengembalian->format('d M Y'),
                        'equipment_count' => count($equipmentDetails),
                        'total_units' => $totalUnits,
                        'equipment_summary' => $equipmentSummary,
                        'purpose' => $peminjaman->tujuanPeminjaman,
                        'is_mahasiswa_usk' => $peminjaman->is_mahasiswa_usk,
                        'created_at' => $peminjaman->created_at->format('d M Y H:i')
                    ]
                ]);
            }

            return redirect()->back()->with('success',
                "Permintaan peminjaman berhasil dikirim!\n\n" .
                "Detail:\n" .
                "- Nomor Referensi: {$referenceNumber}\n" .
                "- {$peminjaman->total_types} jenis alat\n" .
                "- {$peminjaman->total_quantity} unit total\n" .
                "- Periode: " . Carbon::parse($request->start_date)->format('d M Y') . " - " . Carbon::parse($request->end_date)->format('d M Y') . "\n" .
                "- Alat: {$equipmentSummary}\n\n" .
                "Kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi dan penjadwalan briefing.\n\n" .
                "Link tracking: {$trackingUrl}"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Equipment Loan Request Failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.'
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['system' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.'])
                ->withInput();
        }
    }

    /**
     * Track loan status
     */
    public function track($id)
    {
        try {
            $peminjaman = Peminjaman::with(['items.alat'])->findOrFail($id);
            return view('services.equipment-tracking', compact('peminjaman'));
        } catch (\Exception $e) {
            abort(404, 'Peminjaman tidak ditemukan');
        }
    }

    /**
     * Get loan data for API/AJAX requests
     */
    public function getLoanData($id)
    {
        try {
            $peminjaman = Peminjaman::with(['items.alat'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $peminjaman->id,
                    'reference_number' => $peminjaman->reference_number,
                    'borrower_name' => $peminjaman->namaPeminjam,
                    'status' => $peminjaman->status,
                    'status_text' => $peminjaman->status_text,
                    'progress_percentage' => $peminjaman->progress_percentage,
                    'equipment_count' => $peminjaman->total_types,
                    'total_units' => $peminjaman->total_quantity,
                    'start_date' => $peminjaman->formatted_start_date,
                    'end_date' => $peminjaman->formatted_end_date,
                    'days_until_return' => $peminjaman->days_until_return,
                    'is_overdue' => $peminjaman->is_overdue,
                    'created_at' => $peminjaman->created_at->format('d M Y H:i'),
                    'equipment_list' => $peminjaman->items->map(function($item) {
                        return [
                            'name' => $item->alat->nama,
                            'model' => $item->alat->kode,
                            'quantity' => $item->jumlah
                        ];
                    }),
                    'whatsapp_data' => $peminjaman->getWhatsAppData()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Generate WhatsApp message for loan status update
     */
    public function generateStatusWhatsApp(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:peminjaman,id',
            'message_type' => 'required|in:status_inquiry,reminder,confirmation'
        ]);

        try {
            $peminjaman = Peminjaman::with(['items.alat'])->findOrFail($request->loan_id);
            $adminPhone = '6287801482963'; // Nomor admin WhatsApp

            $baseMessage = "Halo Admin Laboratorium Fisika Dasar,\n\n";

            switch ($request->message_type) {
                case 'status_inquiry':
                    $message = $baseMessage .
                              "Saya ingin menanyakan status permohonan peminjaman alat:\n\n" .
                              "ID: {$peminjaman->reference_number}\n" .
                              "Nama: {$peminjaman->namaPeminjam}\n" .
                              "Status: {$peminjaman->status_text}\n" .
                              "Tanggal Mulai: {$peminjaman->formatted_start_date}\n" .
                              "Jumlah Alat: {$peminjaman->total_types} jenis ({$peminjaman->total_quantity} unit)\n\n" .
                              "Mohon informasi lebih lanjut.\n\nTerima kasih.";
                    break;

                case 'reminder':
                    $message = $baseMessage .
                              "Pengingat peminjaman alat:\n\n" .
                              "ID: {$peminjaman->reference_number}\n" .
                              "Nama: {$peminjaman->namaPeminjam}\n" .
                              "Status: {$peminjaman->status_text}\n";

                    if ($peminjaman->status === 'APPROVED') {
                        $message .= "\nKapan saya bisa mengambil alat yang sudah disetujui?\n";
                    } elseif ($peminjaman->status === 'ACTIVE') {
                        $message .= "\nAlat sedang dipinjam.\n";
                        if ($peminjaman->days_until_return !== null) {
                            if ($peminjaman->days_until_return < 0) {
                                $message .= "Status: Terlambat " . abs($peminjaman->days_until_return) . " hari\n";
                            } elseif ($peminjaman->days_until_return === 0) {
                                $message .= "Status: Hari terakhir peminjaman\n";
                            } else {
                                $message .= "Sisa waktu: {$peminjaman->days_until_return} hari\n";
                            }
                        }
                    }

                    $message .= "\nTerima kasih.";
                    break;

                case 'confirmation':
                    $message = $baseMessage .
                              "Konfirmasi status peminjaman:\n\n" .
                              "ID: {$peminjaman->reference_number}\n" .
                              "Nama: {$peminjaman->namaPeminjam}\n" .
                              "Status: {$peminjaman->status_text}\n" .
                              "Periode: {$peminjaman->formatted_start_date} - {$peminjaman->formatted_end_date}\n\n" .
                              "Apakah ada update terbaru mengenai peminjaman ini?\n\nTerima kasih.";
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipe pesan tidak valid'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'whatsapp_url' => "https://wa.me/{$adminPhone}?text=" . urlencode($message),
                'message' => $message,
                'admin_phone' => $adminPhone
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesan WhatsApp'
            ], 500);
        }
    }

    /**
     * Check loan status and send notification if needed
     */
    public function checkAndNotify(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:peminjaman,id'
        ]);

        try {
            $peminjaman = Peminjaman::findOrFail($request->loan_id);

            $notifications = [];

            // Check if overdue
            if ($peminjaman->is_overdue) {
                $notifications[] = [
                    'type' => 'error',
                    'message' => "Peminjaman terlambat " . abs($peminjaman->days_until_return) . " hari. Segera kembalikan alat!"
                ];
            }
            // Check if due soon
            elseif ($peminjaman->days_until_return !== null && $peminjaman->days_until_return <= 2 && $peminjaman->days_until_return >= 0) {
                $notifications[] = [
                    'type' => 'warning',
                    'message' => $peminjaman->days_until_return === 0
                        ? "Hari terakhir peminjaman. Harap kembalikan hari ini."
                        : "Sisa waktu " . $peminjaman->days_until_return . " hari lagi."
                ];
            }

            // Check status changes
            $lastChecked = $request->input('last_checked');
            if ($lastChecked && $peminjaman->updated_at->gt(Carbon::parse($lastChecked))) {
                $notifications[] = [
                    'type' => 'info',
                    'message' => "Status peminjaman telah diperbarui menjadi: " . $peminjaman->status_text
                ];
            }

            return response()->json([
                'success' => true,
                'loan' => [
                    'id' => $peminjaman->id,
                    'status' => $peminjaman->status,
                    'status_text' => $peminjaman->status_text,
                    'days_until_return' => $peminjaman->days_until_return,
                    'is_overdue' => $peminjaman->is_overdue,
                    'updated_at' => $peminjaman->updated_at->toISOString()
                ],
                'notifications' => $notifications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengecek status'
            ], 500);
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
     * Get recent loans for user (based on phone number)
     */
    public function getRecentLoans(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        try {
            $loans = Peminjaman::with(['items.alat'])
                              ->where('noHp', $request->phone)
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get()
                              ->map(function($loan) {
                                  return [
                                      'id' => $loan->id,
                                      'reference_number' => $loan->reference_number,
                                      'status' => $loan->status,
                                      'status_text' => $loan->status_text,
                                      'equipment_count' => $loan->total_types,
                                      'total_units' => $loan->total_quantity,
                                      'start_date' => $loan->formatted_start_date,
                                      'end_date' => $loan->formatted_end_date,
                                      'created_at' => $loan->created_at->format('d M Y'),
                                      'tracking_url' => route('equipment.track', $loan->id)
                                  ];
                              });

            return response()->json([
                'success' => true,
                'loans' => $loans,
                'total' => $loans->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peminjaman'
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
                    'reference_number' => $peminjaman->reference_number,
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
                    'status_name' => $peminjaman->status_text,
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

    /**
     * Generate summary report for admin
     */
    public function generateSummaryReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        try {
            $stats = [
                'total_loans' => Peminjaman::dateRange($startDate, $endDate)->count(),
                'pending_loans' => Peminjaman::dateRange($startDate, $endDate)->pending()->count(),
                'approved_loans' => Peminjaman::dateRange($startDate, $endDate)->approved()->count(),
                'active_loans' => Peminjaman::dateRange($startDate, $endDate)->active()->count(),
                'completed_loans' => Peminjaman::dateRange($startDate, $endDate)->completed()->count(),
                'rejected_loans' => Peminjaman::dateRange($startDate, $endDate)->where('status', 'REJECTED')->count(),
                'overdue_loans' => Peminjaman::overdue()->count(),
                'usk_students' => Peminjaman::dateRange($startDate, $endDate)->uskStudents()->count(),
                'external_borrowers' => Peminjaman::dateRange($startDate, $endDate)->external()->count()
            ];

            // Most borrowed equipment
            $topEquipment = PeminjamanItem::select('alat_id', DB::raw('SUM(jumlah) as total_borrowed'))
                                        ->whereHas('peminjaman', function($query) use ($startDate, $endDate) {
                                            $query->dateRange($startDate, $endDate);
                                        })
                                        ->with('alat')
                                        ->groupBy('alat_id')
                                        ->orderBy('total_borrowed', 'desc')
                                        ->limit(10)
                                        ->get()
                                        ->map(function($item) {
                                            return [
                                                'equipment_name' => $item->alat->nama,
                                                'total_borrowed' => $item->total_borrowed
                                            ];
                                        });

            return response()->json([
                'success' => true,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
                'statistics' => $stats,
                'top_equipment' => $topEquipment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat laporan'
            ], 500);
        }
    }

    // =====================================
    // PRIVATE METHODS
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
}
