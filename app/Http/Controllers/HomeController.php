<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $equipment = [
            [
                'name' => 'Osiloskop Digital',
                'description' => 'Alat untuk menganalisis sinyal listrik dan gelombang dengan presisi tinggi',
                'image' => 'images/oscilloscope.jpg'
            ],
            [
                'name' => 'Spektrometer',
                'description' => 'Instrumen untuk menganalisis spektrum cahaya dan radiasi elektromagnetik',
                'image' => 'images/spectrometer.jpg'
            ],
            [
                'name' => 'Generator Fungsi',
                'description' => 'Perangkat untuk menghasilkan berbagai bentuk gelombang listrik',
                'image' => 'images/function-generator.jpg'
            ],
            [
                'name' => 'Multimeter Digital',
                'description' => 'Alat ukur listrik serbaguna untuk tegangan, arus, dan resistansi',
                'image' => 'images/multimeter.jpg'
            ],
            [
                'name' => 'Power Supply',
                'description' => 'Sumber daya listrik DC yang dapat diatur dengan presisi tinggi',
                'image' => 'images/power-supply.jpg'
            ],
            [
                'name' => 'Interferometer',
                'description' => 'Instrumen untuk mengukur panjang gelombang dan indeks bias dengan akurasi tinggi',
                'image' => 'images/interferometer.jpg'
            ]
        ];

        return view('home', compact('equipment'));
    }

    public function about()
    {
        return view('about');
    }

    public function equipment()
    {
        return view('equipment');
    }

    public function services()
    {
        return view('services');
    }

    public function contact()
    {
        return view('contact');
    }
}
