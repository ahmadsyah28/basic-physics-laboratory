<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    public function index()
    {
        $facilities = [
            [
                'id' => 1,
                'name' => 'Ruang Laboratorium',
                'title' => 'Laboratorium Fisika Modern',
                'image' => 'ruang-ajar.jpg',
                'capacity' => '30 Mahasiswa',
                'description' => 'Ruang laboratorium dengan fasilitas lengkap untuk praktikum fisika dasar dan lanjutan. Dilengkapi dengan peralatan modern, sistem keamanan tinggi, dan teknologi terdepan untuk mendukung pembelajaran eksperimental yang optimal.',
                'icon' => 'fas fa-flask',
                'features' => [
                    'Peralatan Eksperimen Modern',
                    'Sistem Keamanan Advanced',
                    'AC Central & Ventilasi',
                    'Alat Ukur Digital Presisi',
                    'Storage Equipment Aman',
                    'Emergency Safety System'
                ],
                'color_intensity' => '500'
            ],
            [
                'id' => 2,
                'name' => 'Ruang Diskusi',
                'title' => 'Ruang Diskusi Kolaboratif',
                'image' => 'ruang-diskusi.png',
                'capacity' => '15 Mahasiswa',
                'area' => '64 m²',
                'description' => 'Ruang diskusi yang dirancang untuk mendukung pembelajaran kolaboratif dan presentasi. Equipped dengan teknologi audio-visual modern dan furniture fleksibel yang dapat disesuaikan dengan berbagai format diskusi dan pembelajaran.',
                'icon' => 'fas fa-comments',
                'features' => [
                    'Smart Board Interactive',
                    'Sistem Audio Premium',
                    'Projector 4K Display',
                    'Furniture Modular',
                    'High-Speed Wi-Fi',
                    'Video Conference Ready'
                ],
                'color_intensity' => '600'
            ],
            [
                'id' => 3,
                'name' => 'Ruang Shower',
                'title' => 'Fasilitas Kebersihan & Keamanan',
                'image' => 'ruang-shower.jpg',
                'capacity' => '1 Unit',
                'area' => '45 m²',
                'description' => 'Fasilitas shower darurat dan kebersihan yang memenuhi standar keamanan laboratorium. Dilengkapi dengan sistem emergency shower, eyewash station, dan fasilitas dekontaminasi untuk menjaga keselamatan selama praktikum.',
                'icon' => 'fas fa-shower',
                'features' => [
                    'Emergency Shower System',
                    'Eyewash Station',
                    'Dekontaminasi Area',
                    'Water Heater System',
                    'Drainage Khusus',
                    'Safety Signage Lengkap'
                ],
                'color_intensity' => '700'
            ]
        ];

        return view('facilities', compact('facilities'));
    }

    public function show($id)
    {
        // Method untuk detail fasilitas individual
        $facilities = $this->getFacilitiesData();
        $facility = collect($facilities)->where('id', $id)->first();

        if (!$facility) {
            abort(404);
        }

        return view('facilities.detail', compact('facility'));
    }

    private function getFacilitiesData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Ruang Laboratorium',
                'title' => 'Laboratorium Fisika Modern',
                'image' => 'ruang-ajar.jpg',
                'capacity' => '30 Mahasiswa',
                'description' => 'Ruang laboratorium dengan fasilitas lengkap untuk praktikum fisika dasar dan lanjutan. Dilengkapi dengan peralatan modern, sistem keamanan tinggi, dan teknologi terdepan untuk mendukung pembelajaran eksperimental yang optimal.',
                'icon' => 'fas fa-flask',
                'features' => [
                    'Peralatan Eksperimen Modern',
                    'Sistem Keamanan Advanced',
                    'AC Central & Ventilasi',
                    'Alat Ukur Digital Presisi',
                    'Storage Equipment Aman',
                    'Emergency Safety System'
                ],
                'color_intensity' => '500'
            ],
            [
                'id' => 2,
                'name' => 'Ruang Diskusi',
                'title' => 'Ruang Diskusi Kolaboratif',
                'image' => 'ruang-diskusi.jpg',
                'capacity' => '15 Mahasiswa',
                'description' => 'Ruang diskusi yang dirancang untuk mendukung pembelajaran kolaboratif dan presentasi. Equipped dengan teknologi audio-visual modern dan furniture fleksibel yang dapat disesuaikan dengan berbagai format diskusi dan pembelajaran.',
                'icon' => 'fas fa-comments',
                'features' => [
                    'Smart Board Interactive',
                    'Sistem Audio Premium',
                    'Projector 4K Display',
                    'Furniture Modular',
                    'High-Speed Wi-Fi',
                    'Video Conference Ready'
                ],
                'color_intensity' => '600'
            ],
            [
                'id' => 3,
                'name' => 'Ruang Shower',
                'title' => 'Fasilitas Kebersihan & Keamanan',
                'image' => 'ruang-shower.jpg',
                'capacity' => '1 Unit',
                'description' => 'Fasilitas shower darurat dan kebersihan yang memenuhi standar keamanan laboratorium. Dilengkapi dengan sistem emergency shower, eyewash station, dan fasilitas dekontaminasi untuk menjaga keselamatan selama praktikum.',
                'icon' => 'fas fa-shower',
                'features' => [
                    'Emergency Shower System',
                    'Eyewash Station',
                    'Dekontaminasi Area',
                    'Water Heater System',
                    'Drainage Khusus',
                    'Safety Signage Lengkap'
                ],
                'color_intensity' => '700'
            ]
        ];
    }
}
