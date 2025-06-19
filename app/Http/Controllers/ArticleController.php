<?php
// app/Http/Controllers/ArticleController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        // Data artikel lengkap untuk halaman index
        $articles = [
            [
                'id' => 1,
                'title' => 'Pengembangan Sistem Monitoring Seismik Real-time',
                'excerpt' => 'Laboratorium berhasil mengembangkan sistem monitoring aktivitas seismik yang dapat memberikan peringatan dini dengan akurasi tinggi. Sistem ini menggunakan teknologi sensor terbaru dan algoritma machine learning untuk analisis data seismik.',
                'content' => '<p>Laboratorium Fisika Dasar telah berhasil mengembangkan sistem monitoring aktivitas seismik real-time yang revolusioner...</p>',
                'author' => 'Dr. Ahmad Rahman',
                'date' => '2025-06-15',
                'category' => 'Penelitian',
                'image' => 'images/article-1.jpg',
                'slug' => 'pengembangan-sistem-monitoring-seismik',
                'featured' => true
            ],
            [
                'id' => 2,
                'title' => 'Inovasi Metode Praktikum Fisika Modern',
                'excerpt' => 'Penerapan teknologi AR dan VR dalam praktikum fisika modern memberikan pengalaman belajar yang lebih interaktif dan mendalam bagi mahasiswa. Metode ini telah terbukti meningkatkan pemahaman konsep fisika secara signifikan.',
                'content' => '<p>Dalam era digital ini, pendidikan fisika mengalami transformasi besar...</p>',
                'author' => 'Prof. Siti Nurhaliza',
                'date' => '2025-06-12',
                'category' => 'Pendidikan',
                'image' => 'images/article-2.jpg',
                'slug' => 'inovasi-metode-praktikum-fisika',
                'featured' => false
            ],
            [
                'id' => 3,
                'title' => 'Kerjasama Penelitian dengan Universitas Tokyo',
                'excerpt' => 'Program pertukaran peneliti dan mahasiswa dalam bidang fisika material menghasilkan publikasi internasional berkualitas tinggi. Kolaborasi ini membuka peluang penelitian yang lebih luas di tingkat global.',
                'content' => '<p>Kerjasama internasional dalam bidang penelitian fisika material...</p>',
                'author' => 'Dr. Rizki Pratama',
                'date' => '2025-06-08',
                'category' => 'Kolaborasi',
                'image' => 'images/article-3.jpg',
                'slug' => 'kerjasama-penelitian-universitas-tokyo',
                'featured' => true
            ],
            [
                'id' => 4,
                'title' => 'Workshop Kalibrasi Alat Ukur Geofisika',
                'excerpt' => 'Pelatihan kalibrasi alat ukur geofisika untuk meningkatkan akurasi pengukuran dalam penelitian. Workshop ini dihadiri oleh peneliti dan teknisi dari berbagai institusi.',
                'content' => '<p>Akurasi pengukuran merupakan aspek fundamental dalam penelitian geofisika...</p>',
                'author' => 'Dr. Maya Sari',
                'date' => '2025-06-05',
                'category' => 'Pelatihan',
                'image' => 'images/article-4.jpg',
                'slug' => 'workshop-kalibrasi-alat-ukur',
                'featured' => false
            ],
            [
                'id' => 5,
                'title' => 'Publikasi Penelitian di Jurnal Internasional',
                'excerpt' => 'Tim peneliti laboratorium berhasil mempublikasikan hasil penelitian tentang dinamika struktur bumi di jurnal Nature Geoscience. Publikasi ini merupakan pencapaian bergengsi bagi institusi.',
                'content' => '<p>Publikasi di jurnal internasional bereputasi tinggi merupakan indikator...</p>',
                'author' => 'Prof. Dr. Budi Santoso',
                'date' => '2025-06-01',
                'category' => 'Publikasi',
                'image' => 'images/article-5.jpg',
                'slug' => 'publikasi-penelitian-jurnal-internasional',
                'featured' => false
            ],
            [
                'id' => 6,
                'title' => 'Penggunaan AI dalam Analisis Data Geofisika',
                'excerpt' => 'Implementasi artificial intelligence untuk analisis big data geofisika meningkatkan efisiensi dan akurasi interpretasi data. Teknologi ini memungkinkan deteksi pola yang kompleks dalam data.',
                'content' => '<p>Era big data dalam geofisika memerlukan pendekatan analisis yang canggih...</p>',
                'author' => 'Dr. Indra Wijaya',
                'date' => '2025-05-28',
                'category' => 'Penelitian',
                'image' => 'images/article-6.jpg',
                'slug' => 'penggunaan-ai-analisis-data-geofisika',
                'featured' => false
            ],
            [
                'id' => 7,
                'title' => 'Seminar Nasional Fisika Kebumian 2025',
                'excerpt' => 'Event tahunan yang mempertemukan para peneliti fisika kebumian dari seluruh Indonesia. Seminar ini menjadi platform berbagi pengetahuan dan hasil penelitian terbaru.',
                'content' => '<p>Seminar Nasional Fisika Kebumian merupakan forum ilmiah yang...</p>',
                'author' => 'Dr. Rina Kartika',
                'date' => '2025-05-25',
                'category' => 'Kolaborasi',
                'image' => 'images/article-7.jpg',
                'slug' => 'seminar-nasional-fisika-kebumian-2025',
                'featured' => false
            ],
            [
                'id' => 8,
                'title' => 'Modernisasi Laboratorium dengan Teknologi IoT',
                'excerpt' => 'Integrasi Internet of Things (IoT) dalam sistem laboratorium untuk monitoring dan kontrol peralatan secara real-time. Sistem ini meningkatkan efisiensi operasional laboratorium.',
                'content' => '<p>Teknologi Internet of Things (IoT) telah merevolusi cara kerja...</p>',
                'author' => 'Dr. Andi Kurniawan',
                'date' => '2025-05-22',
                'category' => 'Pendidikan',
                'image' => 'images/article-8.jpg',
                'slug' => 'modernisasi-laboratorium-teknologi-iot',
                'featured' => false
            ]
        ];

        return view('articles.index', compact('articles'));
    }

    public function show($slug)
    {
        // Data artikel detail berdasarkan slug
        $articles = [
            'pengembangan-sistem-monitoring-seismik' => [
                'id' => 1,
                'title' => 'Pengembangan Sistem Monitoring Seismik Real-time',
                'content' => '
                <p>Laboratorium Fisika Dasar telah berhasil mengembangkan sistem monitoring aktivitas seismik real-time yang revolusioner. Sistem ini menggunakan teknologi sensor terbaru dan algoritma machine learning untuk memberikan peringatan dini gempa bumi dengan akurasi tinggi.</p>

                <h2>Teknologi Terdepan dalam Monitoring Seismik</h2>
                <p>Sistem yang dikembangkan mengintegrasikan berbagai sensor seismik canggih yang dapat mendeteksi getaran tanah sekecil apapun. Data yang dikumpulkan kemudian diproses menggunakan algoritma artificial intelligence untuk memprediksi potensi gempa bumi dengan tingkat akurasi mencapai 95%.</p>

                <h3>Komponen Utama Sistem</h3>
                <p>Sistem monitoring ini terdiri dari beberapa komponen utama:</p>
                <ul>
                    <li><strong>Sensor Array Network:</strong> Jaringan sensor yang tersebar strategis untuk mendeteksi aktivitas seismik</li>
                    <li><strong>Data Processing Unit:</strong> Unit pemrosesan data real-time dengan teknologi cloud computing</li>
                    <li><strong>Early Warning System:</strong> Sistem peringatan dini yang terintegrasi dengan berbagai platform</li>
                    <li><strong>Mobile Application:</strong> Aplikasi mobile untuk masyarakat umum</li>
                </ul>

                <h2>Manfaat untuk Masyarakat</h2>
                <p>Dengan sistem peringatan dini yang akurat, masyarakat dapat memiliki waktu lebih untuk melakukan evakuasi dan persiapan menghadapi gempa bumi. Hal ini diharapkan dapat mengurangi korban jiwa dan kerusakan material secara signifikan.</p>

                <h3>Implementasi dan Pengujian</h3>
                <p>Sistem telah diuji coba di beberapa wilayah rawan gempa di Indonesia dan menunjukkan hasil yang sangat memuaskan. Tim peneliti juga melakukan kalibrasi berkala untuk memastikan akurasi sistem tetap optimal.</p>

                <p>Penelitian ini merupakan hasil kolaborasi dengan berbagai institusi internasional dan didukung oleh dana penelitian dari Kementerian Riset dan Teknologi. Diharapkan sistem ini dapat diimplementasikan secara nasional dalam waktu dekat.</p>',
                'author' => 'Dr. Ahmad Rahman',
                'date' => '2025-06-15',
                'category' => 'Penelitian',
                'image' => 'images/article-1.jpg',
                'slug' => 'pengembangan-sistem-monitoring-seismik'
            ],
            'inovasi-metode-praktikum-fisika' => [
                'id' => 2,
                'title' => 'Inovasi Metode Praktikum Fisika Modern',
                'content' => '
                <p>Dalam era digital ini, pendidikan fisika mengalami transformasi besar dengan penerapan teknologi Augmented Reality (AR) dan Virtual Reality (VR) dalam praktikum fisika modern. Inovasi ini memberikan pengalaman belajar yang lebih interaktif dan mendalam bagi mahasiswa.</p>

                <h2>Revolusi Digital dalam Pendidikan Fisika</h2>
                <p>Metode pembelajaran konvensional yang hanya mengandalkan papan tulis dan buku teks kini mulai ditinggalkan. Teknologi AR dan VR memungkinkan mahasiswa untuk "melihat" dan "berinteraksi" langsung dengan konsep-konsep fisika yang abstrak.</p>

                <h3>Implementasi Teknologi AR dalam Praktikum</h3>
                <p>Dengan menggunakan perangkat AR, mahasiswa dapat:</p>
                <ul>
                    <li>Visualisasi medan elektromagnetik dalam 3 dimensi</li>
                    <li>Simulasi gelombang suara dan cahaya secara real-time</li>
                    <li>Eksperimen virtual dengan bahan radioaktif yang aman</li>
                    <li>Observasi struktur kristal dalam skala atomik</li>
                </ul>

                <h2>Hasil dan Evaluasi</h2>
                <p>Studi yang dilakukan terhadap 200 mahasiswa menunjukkan peningkatan pemahaman konsep fisika sebesar 40% dibandingkan metode konvensional. Mahasiswa juga menunjukkan antusiasme yang lebih tinggi dalam mengikuti praktikum.</p>

                <h3>Tantangan dan Solusi</h3>
                <p>Meskipun memberikan banyak manfaat, implementasi teknologi ini juga menghadapi beberapa tantangan seperti keterbatasan perangkat dan biaya operasional. Tim pengembang telah merancang solusi berbasis cloud untuk mengatasi masalah ini.</p>',
                'author' => 'Prof. Siti Nurhaliza',
                'date' => '2025-06-12',
                'category' => 'Pendidikan',
                'image' => 'images/article-2.jpg',
                'slug' => 'inovasi-metode-praktikum-fisika'
            ]
            // Tambahkan artikel lainnya sesuai kebutuhan
        ];

        $article = $articles[$slug] ?? null;

        if (!$article) {
            abort(404);
        }

        return view('articles.show', compact('article'));
    }

    // Method tambahan untuk API
    public function latest()
    {
        // Return JSON untuk AJAX request
        $latestArticles = [
            // Data artikel terbaru
        ];

        return response()->json($latestArticles);
    }

    public function featured()
    {
        // Return artikel unggulan untuk homepage
        $featuredArticles = [
            // Data artikel unggulan
        ];

        return response()->json($featuredArticles);
    }
}
