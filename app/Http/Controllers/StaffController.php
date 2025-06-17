<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = [
            [
                'name' => 'Dr. Nasrullah, S.Si, M.Si.,M.Sc',
                'position' => 'Kepala Laboratorium',
                'category' => 'head-lecturer',
                'specialization' => 'Fisika Komputasi, Mekanika Kuantum',
                'experience' => '15+ tahun',
                'education' => [
                    'Ph.D Physics - Massachusetts Institute of Technology (MIT), 2008',
                    'M.Sc Physics - Universitas Indonesia, 2004',
                    'S.Si Physics - Universitas Syiah Kuala, 2002'
                ],
                'research_interests' => [
                    'Computational Physics',
                    'Quantum Mechanics',
                    'Mathematical Physics',
                    'Scientific Computing'
                ],
                'publications' => [
                    'Quantum Simulation Methods in Condensed Matter Physics (2023)',
                    'Advanced Computational Techniques in Modern Physics (2022)',
                    'Mathematical Foundations of Quantum Computing (2021)'
                ],
                'expertise' => ['Penelitian', 'Supervisi', 'Konsultasi'],
                'email' => 'ahmad.rahman@unsyiah.ac.id',
                'phone' => '+62-651-123456',
                'office' => 'Lab Fisika Dasar, Ruang 101',
                'office_hours' => 'Senin-Jumat, 09:00-15:00',
                'photo' => 'ketua-lab.jpg',
                'color' => 'blue',
                'badge_color' => 'yellow',
                'badge_icon' => 'star',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/ahmad-rahman',
                    'scholar' => 'https://scholar.google.com/citations?user=example',
                    'researchgate' => 'https://researchgate.net/profile/ahmad-rahman'
                ]
            ],
            [
                'name' => 'Intan Mulia Sari, S.Si., M.Si.',
                'position' => 'Tenaga Pengajar',
                'category' => 'lecturer',
                'specialization' => 'Fisika Material, Nanoteknologi',
                'experience' => '12+ tahun',
                'education' => [
                    'Ph.D Materials Science - University of Cambridge, 2010',
                    'M.Sc Physics - Institut Teknologi Bandung, 2006',
                    'S.Si Physics - Universitas Syiah Kuala, 2004'
                ],
                'research_interests' => [
                    'Nanomaterials Synthesis',
                    'Material Characterization',
                    'Semiconductor Physics',
                    'Energy Materials'
                ],
                'publications' => [
                    'Advances in Nanomaterial Applications (2023)',
                    'Semiconductor Quantum Dots for Solar Cells (2022)',
                    'Material Science in Renewable Energy (2021)'
                ],
                'expertise' => ['Material Science', 'Pengajaran', 'Analisis Sampel'],
                'email' => 'siti.nurhaliza@unsyiah.ac.id',
                'phone' => '+62-651-123457',
                'office' => 'Lab Fisika Dasar, Ruang 102',
                'office_hours' => 'Selasa-Kamis, 10:00-14:00',
                'photo' => 'tenaga-pengajar-1.png',
                'color' => 'purple',
                'badge_color' => 'green',
                'badge_icon' => 'graduation-cap',
                'social_links' => [
                    'scholar' => 'https://scholar.google.com/citations?user=example2',
                    'researchgate' => 'https://researchgate.net/profile/siti-nurhaliza'
                ]
            ],
            [
                'name' => 'Anla Fet Hardi, S.Si., M.Si.',
                'position' => 'Tenaga Pengajar',
                'category' => 'lecturer',
                'specialization' => 'Fisika Material, Nanoteknologi',
                'experience' => '12+ tahun',
                'education' => [
                    'Ph.D Materials Science - University of Cambridge, 2010',
                    'M.Sc Physics - Institut Teknologi Bandung, 2006',
                    'S.Si Physics - Universitas Syiah Kuala, 2004'
                ],
                'research_interests' => [
                    'Nanomaterials Synthesis',
                    'Material Characterization',
                    'Semiconductor Physics',
                    'Energy Materials'
                ],
                'publications' => [
                    'Advances in Nanomaterial Applications (2023)',
                    'Semiconductor Quantum Dots for Solar Cells (2022)',
                    'Material Science in Renewable Energy (2021)'
                ],
                'expertise' => ['Material Science', 'Pengajaran', 'Analisis Sampel'],
                'email' => 'siti.nurhaliza@unsyiah.ac.id',
                'phone' => '+62-651-123457',
                'office' => 'Lab Fisika Dasar, Ruang 102',
                'office_hours' => 'Selasa-Kamis, 10:00-14:00',
                'photo' => 'tenaga-pengajar-2.jpg',
                'color' => 'purple',
                'badge_color' => 'green',
                'badge_icon' => 'graduation-cap',
                'social_links' => [
                    'scholar' => 'https://scholar.google.com/citations?user=example2',
                    'researchgate' => 'https://researchgate.net/profile/siti-nurhaliza'
                ]
            ],
            [
                'name' => 'Vikah Suci Novianti, S.Si',
                'position' => 'Laboran',
                'category' => 'technician',
                'specialization' => 'Maintenance Alat, Kalibrasi',
                'experience' => '8+ tahun',
                'education' => [
                    'S.T Teknik Fisika - Institut Teknologi Bandung, 2015',
                    'Sertifikasi Kalibrasi Alat Ukur - BPFK, 2017',
                    'Training Advanced Lab Equipment - Jerman, 2019'
                ],
                'research_interests' => [
                    'Instrumentation Technology',
                    'Precision Measurement',
                    'Quality Control',
                    'Laboratory Standards'
                ],
                'publications' => [
                    'Panduan Kalibrasi Peralatan Lab Fisika (2023)',
                    'Maintenance Protokol untuk Instrumen Presisi (2022)'
                ],
                'expertise' => ['Maintenance', 'Kalibrasi', 'Training', 'Quality Assurance'],
                'email' => 'muhammad.iqbal@unsyiah.ac.id',
                'phone' => '+62-651-123458',
                'office' => 'Workshop Lab, Ruang A05',
                'office_hours' => 'Senin-Jumat, 08:00-16:00',
                'photo' => 'laboran-2.jpg',
                'color' => 'green',
                'badge_color' => 'blue',
                'badge_icon' => 'tools',
                'social_links' => []
            ],
            [
                'name' => 'Dini Rizqi Dwi Kunti Siregar, S.Si., M.Si',
                'position' => 'Laboran',
                'category' => 'technician',
                'specialization' => 'Praktikum, Administrasi Lab',
                'experience' => '4+ tahun',
                'education' => [
                    'S.Si Physics - Universitas Syiah Kuala, 2019',
                    'Sertifikasi Lab Safety Management - 2020',
                    'Training Student Assistant Methodology - 2021'
                ],
                'research_interests' => [
                    'Educational Technology',
                    'Laboratory Teaching Methods',
                    'Student Assessment',
                    'Lab Safety Protocols'
                ],
                'publications' => [
                    'Effective Teaching Methods in Physics Laboratory (2023)',
                    'Student Assessment in Practical Physics (2022)'
                ],
                'expertise' => ['Praktikum', 'Administrasi', 'Support', 'Teaching Assistant'],
                'email' => 'dewi.sartika@unsyiah.ac.id',
                'phone' => '+62-651-123461',
                'office' => 'Lab Fisika Dasar, Front Desk',
                'office_hours' => 'Senin-Jumat, 08:00-15:00',
                'photo' => 'laboran-1.jpg',
                'color' => 'teal',
                'badge_color' => 'cyan',
                'badge_icon' => 'hands-helping',
                'social_links' => []
            ]
        ];

        // Statistik staff - Tambahkan semua kategori yang dibutuhkan
        $stats = [
            'total_staff' => count($staff),
            'head-researchers' => count(array_filter($staff, fn($s) => $s['category'] === 'head-lecturer')), // Perbaiki ini
            'lecturers' => count(array_filter($staff, fn($s) => $s['category'] === 'lecturer')),
            'technicians' => count(array_filter($staff, fn($s) => $s['category'] === 'technician')),
            'researchers' => count(array_filter($staff, fn($s) => $s['category'] === 'researcher')),
            'total_experience' => array_sum(array_map(fn($s) => (int) explode('+', $s['experience'])[0], $staff)),
            'total_publications' => array_sum(array_map(fn($s) => count($s['publications']), $staff))
        ];

        return view('staff', compact('staff', 'stats'));
    }

    public function show($id)
    {
        // Detail staff individual (untuk future implementation)
        // return view('staff.show', compact('staff'));
    }
}
