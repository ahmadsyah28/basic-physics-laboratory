<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EquipmentLoanController extends Controller
{
    public function index()
    {
        $equipments = [
            [
                'id' => 1,
                'name' => 'Oscilloscope Digital',
                'model' => 'Tektronix TBS1052B',
                'image' => 'oscilloscope.jpeg',
                'category' => 'Elektronika',
                'status' => 'available',
                'quantity_total' => 5,
                'quantity_available' => 3,
                'description' => 'Oscilloscope digital 50MHz dengan 2 channel untuk analisis sinyal elektronik dan pengukuran gelombang.',
                'specifications' => [
                    'Bandwidth: 50 MHz',
                    'Sample Rate: 1 GS/s',
                    'Channels: 2',
                    'Display: 7 inch Color TFT',
                    'Memory Depth: 2.5k points'
                ],
                'loan_duration' => '1-7 hari',
                'requirements' => [
                    'Mahasiswa semester 3 ke atas',
                    'Surat permohonan dari dosen',
                    'Deposit Rp 500.000'
                ],
                'icon' => 'fas fa-wave-square',
                'color' => 'blue'
            ],
            [
                'id' => 2,
                'name' => 'Multimeter Digital',
                'model' => 'Fluke 87V',
                'image' => 'multimeter.jpeg',
                'category' => 'Pengukuran',
                'status' => 'available',
                'quantity_total' => 10,
                'quantity_available' => 7,
                'description' => 'Multimeter digital presisi tinggi untuk pengukuran tegangan, arus, resistansi, dan parameter listrik lainnya.',
                'specifications' => [
                    'DC Voltage: 0.1 mV - 1000 V',
                    'AC Voltage: 0.1 mV - 750 V',
                    'DC Current: 0.01 mA - 10 A',
                    'Resistance: 0.1 Ω - 50 MΩ',
                    'Frequency: 0.5 Hz - 200 kHz'
                ],
                'loan_duration' => '1-14 hari',
                'requirements' => [
                    'Mahasiswa semester 2 ke atas',
                    'Kartu mahasiswa aktif',
                    'Deposit Rp 200.000'
                ],
                'icon' => 'fas fa-tachometer-alt',
                'color' => 'green'
            ],
            [
                'id' => 3,
                'name' => 'Function Generator',
                'model' => 'Rigol DG1032Z',
                'image' => 'function-generator.jpeg',
                'category' => 'Generator',
                'status' => 'available',
                'quantity_total' => 3,
                'quantity_available' => 2,
                'description' => 'Function generator 30MHz untuk menghasilkan berbagai bentuk gelombang sinusoidal, kotak, dan segitiga.',
                'specifications' => [
                    'Frequency Range: 1 μHz - 30 MHz',
                    'Waveforms: Sine, Square, Triangle, Pulse',
                    'Amplitude: 1 mVpp - 10 Vpp',
                    'Channels: 2',
                    'Arbitrary Waveform: 14-bit, 125 MSa/s'
                ],
                'loan_duration' => '1-7 hari',
                'requirements' => [
                    'Mahasiswa semester 3 ke atas',
                    'Surat permohonan dari dosen',
                    'Deposit Rp 300.000'
                ],
                'icon' => 'fas fa-broadcast-tower',
                'color' => 'purple'
            ],
            [
                'id' => 4,
                'name' => 'Power Supply DC',
                'model' => 'Keysight E3631A',
                'image' => 'power-supply.jpeg',
                'category' => 'Power',
                'status' => 'maintenance',
                'quantity_total' => 4,
                'quantity_available' => 0,
                'description' => 'Power supply DC triple output dengan regulasi tinggi untuk berbagai kebutuhan eksperimen elektronika.',
                'specifications' => [
                    'Output 1: 0-6V, 0-5A',
                    'Output 2: 0-25V, 0-1A',
                    'Output 3: 0-25V, 0-1A',
                    'Regulation: ±0.01%',
                    'Ripple: <1 mVrms'
                ],
                'loan_duration' => '1-7 hari',
                'requirements' => [
                    'Mahasiswa semester 3 ke atas',
                    'Surat permohonan dari dosen',
                    'Deposit Rp 400.000'
                ],
                'icon' => 'fas fa-plug',
                'color' => 'red'
            ],
            [
                'id' => 5,
                'name' => 'Spektrum Analyzer',
                'model' => 'Rohde & Schwarz FSW-B',
                'image' => 'spectrum-analyzer.jpg',
                'category' => 'Analisis',
                'status' => 'available',
                'quantity_total' => 2,
                'quantity_available' => 1,
                'description' => 'Spektrum analyzer untuk analisis frekuensi dan karakteristik sinyal RF dengan akurasi tinggi.',
                'specifications' => [
                    'Frequency Range: 2 Hz - 26.5 GHz',
                    'Resolution Bandwidth: 0.1 Hz - 50 MHz',
                    'Dynamic Range: >70 dB',
                    'Phase Noise: -136 dBc/Hz',
                    'Display: 12.1" Touchscreen'
                ],
                'loan_duration' => '1-5 hari',
                'requirements' => [
                    'Mahasiswa semester 5 ke atas',
                    'Surat permohonan dari dosen',
                    'Training penggunaan alat',
                    'Deposit Rp 1.000.000'
                ],
                'icon' => 'fas fa-chart-line',
                'color' => 'indigo'
            ],
            [
                'id' => 6,
                'name' => 'Digital Caliper',
                'model' => 'Mitutoyo 500-196-30',
                'image' => 'digital-caliper.png',
                'category' => 'Pengukuran',
                'status' => 'available',
                'quantity_total' => 15,
                'quantity_available' => 12,
                'description' => 'Jangka sorong digital presisi tinggi untuk pengukuran dimensi dengan akurasi 0.01mm.',
                'specifications' => [
                    'Range: 0-150 mm',
                    'Resolution: 0.01 mm',
                    'Accuracy: ±0.02 mm',
                    'Battery Life: 3.8 years',
                    'IP67 Protection'
                ],
                'loan_duration' => '1-30 hari',
                'requirements' => [
                    'Mahasiswa aktif',
                    'Kartu mahasiswa',
                    'Deposit Rp 50.000'
                ],
                'icon' => 'fas fa-ruler-combined',
                'color' => 'yellow'
            ]
        ];

        $categories = [
            'all' => 'Semua Kategori',
            'Elektronika' => 'Elektronika',
            'Pengukuran' => 'Pengukuran',
            'Generator' => 'Generator',
            'Power' => 'Power Supply',
            'Analisis' => 'Analisis'
        ];

        return view('services.equipment-loan', compact('equipments', 'categories'));
    }

    public function show($id)
    {
        // Detail alat individual
        $equipment = $this->getEquipmentById($id);

        if (!$equipment) {
            abort(404);
        }

        return view('services.equipment-detail', compact('equipment'));
    }

    public function requestLoan(Request $request, $id = null)
    {
        // Validasi dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:20',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'purpose' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'supervisor' => 'required|string|max:255'
        ]);

        // Check if this is a bulk request or single item request
        if ($request->has('equipment_ids')) {
            return $this->requestBulkLoan($request);
        } else {
            return $this->requestSingleLoan($request, $id);
        }
    }

    public function requestBulkLoan(Request $request)
    {
        // Additional validation for bulk requests
        $request->validate([
            'equipment_ids' => 'required|string',
            'equipment_quantities' => 'required|string'
        ]);

        // Parse equipment IDs and quantities
        $equipmentIds = json_decode($request->equipment_ids, true);
        $equipmentQuantities = json_decode($request->equipment_quantities, true);

        if (!is_array($equipmentIds) || empty($equipmentIds) || !is_array($equipmentQuantities)) {
            return redirect()->back()->withErrors(['equipment_ids' => 'Invalid equipment selection.']);
        }

        // Get selected equipment details
        $allEquipments = $this->getAllEquipments();
        $selectedEquipments = collect($allEquipments)->whereIn('id', $equipmentIds);

        // Validate all selected equipment are available with requested quantities
        $unavailableEquipment = [];
        $totalUnits = 0;

        foreach ($selectedEquipments as $equipment) {
            $requestedQuantity = $equipmentQuantities[$equipment['id']] ?? 1;
            $totalUnits += $requestedQuantity;

            if ($equipment['status'] !== 'available' ||
                $equipment['quantity_available'] <= 0 ||
                $requestedQuantity > $equipment['quantity_available']) {

                $unavailableEquipment[] = [
                    'name' => $equipment['name'],
                    'requested' => $requestedQuantity,
                    'available' => $equipment['quantity_available'],
                    'status' => $equipment['status']
                ];
            }
        }

        if (!empty($unavailableEquipment)) {
            $errorMessages = [];
            foreach ($unavailableEquipment as $item) {
                if ($item['status'] !== 'available') {
                    $errorMessages[] = "{$item['name']}: Tidak tersedia (maintenance)";
                } else {
                    $errorMessages[] = "{$item['name']}: Diminta {$item['requested']} unit, tersedia {$item['available']} unit";
                }
            }
            return redirect()->back()->withErrors([
                'availability' => "Ketersediaan tidak mencukupi:\n" . implode("\n", $errorMessages)
            ]);
        }

        // Create equipment list with quantities
        $equipmentList = [];
        foreach ($selectedEquipments as $equipment) {
            $quantity = $equipmentQuantities[$equipment['id']] ?? 1;
            $equipmentList[] = [
                'id' => $equipment['id'],
                'name' => $equipment['name'],
                'model' => $equipment['model'],
                'quantity' => $quantity,
                'loan_duration' => $equipment['loan_duration']
            ];
        }

        // Create loan request data
        $loanData = [
            'borrower_name' => $request->name,
            'student_id' => $request->student_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'supervisor' => $request->supervisor,
            'purpose' => $request->purpose,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'equipment_count' => $selectedEquipments->count(),
            'total_units' => $totalUnits,
            'equipment_list' => $equipmentList,
            'equipment_ids' => $equipmentIds,
            'equipment_quantities' => $equipmentQuantities,
            'status' => 'pending',
            'request_date' => now(),
            'loan_type' => 'bulk'
        ];

        // Here you would normally save to database
        // For demonstration, we'll just simulate the process

        // Log the request (in real application, save to database)
        Log::info('Bulk Equipment Loan Request', $loanData);

        // Send notification email (in real application)
        $this->sendLoanRequestNotification($loanData);

        // Create summary message
        $equipmentSummary = collect($equipmentList)->map(function($item) {
            return "{$item['name']} ({$item['quantity']} unit)";
        })->implode(', ');

        return redirect()->back()->with('success',
            "Permintaan peminjaman berhasil dikirim!\n\n" .
            "Detail:\n" .
            "- {$selectedEquipments->count()} jenis alat\n" .
            "- {$totalUnits} unit total\n" .
            "- Alat: {$equipmentSummary}\n\n" .
            "Nomor referensi: BLR-" . date('Ymd') . "-" . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) . "\n" .
            "Kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi dan penjadwalan briefing."
        );
    }

    public function requestSingleLoan(Request $request, $id)
    {
        $equipment = $this->getEquipmentById($id);

        if (!$equipment) {
            return redirect()->back()->withErrors(['equipment' => 'Equipment not found.']);
        }

        // Validate equipment availability
        if ($equipment['status'] !== 'available' || $equipment['quantity_available'] <= 0) {
            return redirect()->back()->withErrors([
                'availability' => "Alat {$equipment['name']} sedang tidak tersedia."
            ]);
        }

        // Create loan request data
        $loanData = [
            'borrower_name' => $request->name,
            'student_id' => $request->student_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'supervisor' => $request->supervisor,
            'purpose' => $request->purpose,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'equipment_id' => $id,
            'equipment_name' => $equipment['name'],
            'status' => 'pending',
            'request_date' => now(),
            'loan_type' => 'single'
        ];

        // Here you would normally save to database
        Log::info('Single Equipment Loan Request', $loanData);

        // Send notification email
        $this->sendLoanRequestNotification($loanData);

        return redirect()->back()->with('success',
            "Permintaan peminjaman alat {$equipment['name']} berhasil dikirim. " .
            "Nomor referensi: SLR-" . date('Ymd') . "-" . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) . ". " .
            "Kami akan menghubungi Anda dalam 1x24 jam."
        );
    }

    /**
     * Get loan history for admin/user dashboard
     */
    public function getLoanHistory($studentId = null)
    {
        // This would normally query the database
        // For demonstration, return mock data with quantity information
        return [
            [
                'id' => 1,
                'reference_number' => 'BLR-20250619-001',
                'borrower_name' => 'John Doe',
                'student_id' => '2021001',
                'equipment_count' => 3,
                'total_units' => 5,
                'equipment_list' => [
                    ['name' => 'Oscilloscope Digital', 'quantity' => 2],
                    ['name' => 'Multimeter Digital', 'quantity' => 1],
                    ['name' => 'Function Generator', 'quantity' => 2]
                ],
                'start_date' => '2025-06-20',
                'end_date' => '2025-06-25',
                'status' => 'approved',
                'loan_type' => 'bulk'
            ],
            [
                'id' => 2,
                'reference_number' => 'SLR-20250618-002',
                'borrower_name' => 'Jane Smith',
                'student_id' => '2021002',
                'equipment_name' => 'Digital Caliper',
                'quantity' => 3,
                'start_date' => '2025-06-19',
                'end_date' => '2025-06-26',
                'status' => 'active',
                'loan_type' => 'single'
            ],
            [
                'id' => 3,
                'reference_number' => 'BLR-20250617-003',
                'borrower_name' => 'Mike Johnson',
                'student_id' => '2021003',
                'equipment_count' => 2,
                'total_units' => 8,
                'equipment_list' => [
                    ['name' => 'Multimeter Digital', 'quantity' => 5],
                    ['name' => 'Digital Caliper', 'quantity' => 3]
                ],
                'start_date' => '2025-06-18',
                'end_date' => '2025-06-22',
                'status' => 'completed',
                'loan_type' => 'bulk'
            ]
        ];
    }

    /**
     * Check equipment availability for specific dates
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'equipment_ids' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $equipmentIds = $request->equipment_ids;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // This would normally check against database reservations
        // For demonstration, assume all requested equipment is available
        $availability = [];

        foreach ($equipmentIds as $equipmentId) {
            $equipment = $this->getEquipmentById($equipmentId);
            if ($equipment) {
                $availability[$equipmentId] = [
                    'available' => $equipment['status'] === 'available' && $equipment['quantity_available'] > 0,
                    'quantity_available' => $equipment['quantity_available'],
                    'conflicting_bookings' => [] // Would contain overlapping bookings
                ];
            }
        }

        return response()->json([
            'success' => true,
            'availability' => $availability,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * Send notification email (mock implementation)
     */
    private function sendLoanRequestNotification($loanData)
    {
        // In a real application, you would send actual emails
        // using Laravel's Mail facade or notification system

        $emailData = [
            'to' => $loanData['email'],
            'subject' => 'Konfirmasi Permintaan Peminjaman Alat Laboratorium',
            'type' => $loanData['loan_type'],
            'borrower_name' => $loanData['borrower_name'],
            'student_id' => $loanData['student_id']
        ];

        if ($loanData['loan_type'] === 'bulk') {
            $emailData['equipment_count'] = $loanData['equipment_count'];
            $emailData['total_units'] = $loanData['total_units'] ?? 0;
            $emailData['equipment_list'] = $loanData['equipment_list'];

            // Create detailed equipment list for email
            if (isset($loanData['equipment_list']) && is_array($loanData['equipment_list'])) {
                $emailData['equipment_details'] = collect($loanData['equipment_list'])->map(function($item) {
                    if (is_array($item) && isset($item['name'], $item['quantity'])) {
                        return "{$item['name']} - {$item['quantity']} unit";
                    }
                    return is_string($item) ? $item : 'Unknown Equipment';
                })->toArray();
            } else {
                $emailData['equipment_details'] = is_array($loanData['equipment_list']) ? $loanData['equipment_list'] : [];
            }
        } else {
            $emailData['equipment_name'] = $loanData['equipment_name'];
        }

        // Log email sending (in real app, use Mail::send())
        Log::info('Loan Request Email Sent', $emailData);

        // Also notify lab staff
        Log::info('Lab Staff Notification', [
            'type' => 'new_loan_request',
            'loan_data' => $loanData
        ]);
    }

    private function getEquipmentById($id)
    {
        $equipments = $this->getAllEquipments();
        return collect($equipments)->where('id', $id)->first();
    }

    private function getAllEquipments()
    {
        return [
            [
                'id' => 1,
                'name' => 'Oscilloscope Digital',
                'model' => 'Tektronix TBS1052B',
                'image' => 'oscilloscope.jpeg',
                'category' => 'Elektronika',
                'status' => 'available',
                'quantity_total' => 5,
                'quantity_available' => 3,
                'description' => 'Oscilloscope digital 50MHz dengan 2 channel untuk analisis sinyal elektronik dan pengukuran gelombang.',
                'specifications' => [
                    'Bandwidth: 50 MHz',
                    'Sample Rate: 1 GS/s',
                    'Channels: 2',
                    'Display: 7 inch Color TFT',
                    'Memory Depth: 2.5k points'
                ],
                'loan_duration' => '1-7 hari',
                'requirements' => [
                    'Mahasiswa semester 3 ke atas',
                    'Surat permohonan dari dosen',
                    'Deposit Rp 500.000'
                ],
                'icon' => 'fas fa-wave-square',
                'color' => 'blue'
            ],
            [
                'id' => 2,
                'name' => 'Multimeter Digital',
                'model' => 'Fluke 87V',
                'image' => 'multimeter.jpeg',
                'category' => 'Pengukuran',
                'status' => 'available',
                'quantity_total' => 10,
                'quantity_available' => 7,
                'description' => 'Multimeter digital presisi tinggi untuk pengukuran tegangan, arus, resistansi, dan parameter listrik lainnya.',
                'specifications' => [
                    'DC Voltage: 0.1 mV - 1000 V',
                    'AC Voltage: 0.1 mV - 750 V',
                    'DC Current: 0.01 mA - 10 A',
                    'Resistance: 0.1 Ω - 50 MΩ',
                    'Frequency: 0.5 Hz - 200 kHz'
                ],
                'loan_duration' => '1-14 hari',
                'requirements' => [
                    'Mahasiswa semester 2 ke atas',
                    'Kartu mahasiswa aktif',
                    'Deposit Rp 200.000'
                ],
                'icon' => 'fas fa-tachometer-alt',
                'color' => 'green'
            ],
            [
                'id' => 3,
                'name' => 'Function Generator',
                'model' => 'Rigol DG1032Z',
                'image' => 'function-generator.jpeg',
                'category' => 'Generator',
                'status' => 'available',
                'quantity_total' => 3,
                'quantity_available' => 2,
                'description' => 'Function generator 30MHz untuk menghasilkan berbagai bentuk gelombang sinusoidal, kotak, dan segitiga.',
                'specifications' => [
                    'Frequency Range: 1 μHz - 30 MHz',
                    'Waveforms: Sine, Square, Triangle, Pulse',
                    'Amplitude: 1 mVpp - 10 Vpp',
                    'Channels: 2',
                    'Arbitrary Waveform: 14-bit, 125 MSa/s'
                ],
                'loan_duration' => '1-7 hari',
                'requirements' => [
                    'Mahasiswa semester 3 ke atas',
                    'Surat permohonan dari dosen',
                    'Deposit Rp 300.000'
                ],
                'icon' => 'fas fa-broadcast-tower',
                'color' => 'purple'
            ],
            [
                'id' => 4,
                'name' => 'Power Supply DC',
                'model' => 'Keysight E3631A',
                'image' => 'power-supply.jpeg',
                'category' => 'Power',
                'status' => 'maintenance',
                'quantity_total' => 4,
                'quantity_available' => 0,
                'description' => 'Power supply DC triple output dengan regulasi tinggi untuk berbagai kebutuhan eksperimen elektronika.',
                'specifications' => [
                    'Output 1: 0-6V, 0-5A',
                    'Output 2: 0-25V, 0-1A',
                    'Output 3: 0-25V, 0-1A',
                    'Regulation: ±0.01%',
                    'Ripple: <1 mVrms'
                ],
                'loan_duration' => '1-7 hari',
                'requirements' => [
                    'Mahasiswa semester 3 ke atas',
                    'Surat permohonan dari dosen',
                    'Deposit Rp 400.000'
                ],
                'icon' => 'fas fa-plug',
                'color' => 'red'
            ],
            [
                'id' => 5,
                'name' => 'Spektrum Analyzer',
                'model' => 'Rohde & Schwarz FSW-B',
                'image' => 'spectrum-analyzer.jpg',
                'category' => 'Analisis',
                'status' => 'available',
                'quantity_total' => 2,
                'quantity_available' => 1,
                'description' => 'Spektrum analyzer untuk analisis frekuensi dan karakteristik sinyal RF dengan akurasi tinggi.',
                'specifications' => [
                    'Frequency Range: 2 Hz - 26.5 GHz',
                    'Resolution Bandwidth: 0.1 Hz - 50 MHz',
                    'Dynamic Range: >70 dB',
                    'Phase Noise: -136 dBc/Hz',
                    'Display: 12.1" Touchscreen'
                ],
                'loan_duration' => '1-5 hari',
                'requirements' => [
                    'Mahasiswa semester 5 ke atas',
                    'Surat permohonan dari dosen',
                    'Training penggunaan alat',
                    'Deposit Rp 1.000.000'
                ],
                'icon' => 'fas fa-chart-line',
                'color' => 'indigo'
            ],
            [
                'id' => 6,
                'name' => 'Digital Caliper',
                'model' => 'Mitutoyo 500-196-30',
                'image' => 'digital-caliper.png',
                'category' => 'Pengukuran',
                'status' => 'available',
                'quantity_total' => 15,
                'quantity_available' => 12,
                'description' => 'Jangka sorong digital presisi tinggi untuk pengukuran dimensi dengan akurasi 0.01mm.',
                'specifications' => [
                    'Range: 0-150 mm',
                    'Resolution: 0.01 mm',
                    'Accuracy: ±0.02 mm',
                    'Battery Life: 3.8 years',
                    'IP67 Protection'
                ],
                'loan_duration' => '1-30 hari',
                'requirements' => [
                    'Mahasiswa aktif',
                    'Kartu mahasiswa',
                    'Deposit Rp 50.000'
                ],
                'icon' => 'fas fa-ruler-combined',
                'color' => 'yellow'
            ]
        ];
    }
}
