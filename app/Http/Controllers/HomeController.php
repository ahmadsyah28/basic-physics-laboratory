<?php
namespace App\Http\Controllers;

use App\Models\ProfilLaboratorium;
use App\Models\Misi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $articleController;

    public function __construct()
    {
        $this->articleController = new ArticleController();
    }

    public function index()
    {
        // Ambil data artikel dari ArticleController
        $featuredArticles = $this->articleController->getFeaturedArticles();

        // Jika getFeaturedArticles() mengembalikan Collection, convert ke array
        if (is_object($featuredArticles) && method_exists($featuredArticles, 'toArray')) {
            $featuredArticles = $featuredArticles;
        }

        $profil = ProfilLaboratorium::with('misi')->first();
        $misis = Misi::all();

        // Dummy fallback jika belum ada data di database
        if (!$profil) {
            $profil = (object) [
                'namaLaboratorium' => 'Fisika Dasar',
                'tentangLaboratorium' => 'Laboratorium Fisika Dasar merupakan fasilitas unggulan yang berkomitmen untuk mengembangkan penelitian dan pendidikan di bidang fisika dengan teknologi terdepan.',
                'visi' => 'Menjadi laboratorium fisika terdepan di Indonesia yang berkontribusi dalam penelitian dan pengembangan ilmu fisika untuk kemajuan bangsa.'
            ];
        }

        if ($misis->isEmpty()) {
            $misis = collect([
                (object) ['pointMisi' => 'Menyediakan fasilitas penelitian fisika berkualitas tinggi'],
                (object) ['pointMisi' => 'Mengembangkan sumber daya manusia di bidang fisika'],
                (object) ['pointMisi' => 'Berkolaborasi dalam penelitian bertaraf internasional'],
            ]);
        }

        // Fallback articles jika database kosong
        if (empty($featuredArticles) || count($featuredArticles) == 0) {
            $featuredArticles = [
                [
                    'id' => 1,
                    'title' => 'Pengembangan Sistem Monitoring Seismik Real-time',
                    'excerpt' => 'Laboratorium berhasil mengembangkan sistem monitoring aktivitas seismik yang dapat memberikan peringatan dini dengan akurasi tinggi.',
                    'author' => 'Dr. Ahmad Rahman',
                    'date' => now()->subDays(7)->format('Y-m-d'),
                    'image' => 'images/article/article-1.jpeg',
                    'slug' => 'pengembangan-sistem-monitoring-seismik'
                ],
                [
                    'id' => 2,
                    'title' => 'Inovasi Metode Praktikum Fisika Modern',
                    'excerpt' => 'Penerapan teknologi AR dan VR dalam praktikum fisika modern memberikan pengalaman belajar yang lebih interaktif.',
                    'author' => 'Prof. Siti Nurhaliza',
                    'date' => now()->subDays(10)->format('Y-m-d'),
                    'image' => 'images/article/article-2.jpg',
                    'slug' => 'inovasi-metode-praktikum-fisika'
                ],
                [
                    'id' => 3,
                    'title' => 'Kerjasama Penelitian dengan Universitas Tokyo',
                    'excerpt' => 'Program pertukaran peneliti dan mahasiswa dalam bidang fisika material menghasilkan publikasi internasional berkualitas tinggi.',
                    'author' => 'Dr. Rizki Pratama',
                    'date' => now()->subDays(14)->format('Y-m-d'),
                    'image' => 'images/article/article-1.jpeg',
                    'slug' => 'kerjasama-penelitian-universitas-tokyo'
                ]
            ];
        }

        return view('home', compact('featuredArticles', 'profil', 'misis'));
    }

    public function about()
    {
        return view('about');
    }
}
