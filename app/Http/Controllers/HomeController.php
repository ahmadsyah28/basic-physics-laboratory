<?php

namespace App\Http\Controllers;

use App\Models\ProfilLaboratorium;
use App\Models\Misi;
use App\Models\Gambar;
use App\Models\Alat;
use App\Models\Kunjungan;
use App\Models\BiodataPengurus;
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

        if (is_object($featuredArticles) && method_exists($featuredArticles, 'toArray')) {
            $featuredArticles = $featuredArticles;
        }

        // TAMBAHAN: Ambil gambar untuk gallery (EXCLUDE PENGURUS)
        $galleryImages = $this->getGalleryImages();

        $profil = ProfilLaboratorium::with('misi')->first();
        $misis = Misi::all();

        // Dummy fallback data...
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

         // Hitung statistik
        $totalAlat = Alat::count();
        $totalKunjunganPerTahun = Kunjungan::whereYear('tanggal_kunjungan', now()->year)->count();
        $totalStaf = BiodataPengurus::count();

        return view('home', compact(
            'featuredArticles',
            'profil',
            'misis',
            'galleryImages',
            'totalAlat',
            'totalKunjunganPerTahun',
            'totalStaf'
        ));

        return view('home', compact('featuredArticles', 'profil', 'misis', 'galleryImages'));
    }

    /**
     * DEBUG VERSION: Method untuk mendapatkan gambar gallery dengan debug info
     */
    private function getGalleryImages()
    {
        try {
            // DEBUG: Log semua gambar yang ada
            $allImages = Gambar::all();
            \Log::info('All images in database:', $allImages->toArray());

            // DEBUG: Log gambar ACARA
            $acaraImages = Gambar::where('kategori', 'ACARA')->get();
            \Log::info('ACARA images found:', $acaraImages->toArray());

            // DEBUG: Log gambar FASILITAS
            $fasilitasImages = Gambar::where('kategori', 'FASILITAS')->get();
            \Log::info('FASILITAS images found:', $fasilitasImages->toArray());

            $images = collect();

            // Ambil 4 gambar dari fasilitas (prioritas tertinggi)
            $fasilitasImages = Gambar::where('kategori', 'FASILITAS')
                                    ->latest()
                                    ->limit(4)
                                    ->get();
            \Log::info('Selected FASILITAS images:', $fasilitasImages->toArray());
            $images = $images->merge($fasilitasImages);

            // Sisa gambar dari acara
            $remainingCount = 6 - $images->count();
            if ($remainingCount > 0) {
                $acaraImages = Gambar::where('kategori', 'ACARA')
                                    ->latest()
                                    ->limit($remainingCount)
                                    ->get();
                \Log::info('Selected ACARA images:', $acaraImages->toArray());
                $images = $images->merge($acaraImages);
            }

            // Jika masih kurang dari 6, ambil sisa dari FASILITAS dan ACARA saja
            if ($images->count() < 6) {
                $stillNeeded = 6 - $images->count();
                $existingIds = $images->pluck('id')->toArray();

                $additionalImages = Gambar::whereIn('kategori', ['FASILITAS', 'ACARA'])
                                         ->whereNotIn('id', $existingIds)
                                         ->latest()
                                         ->limit($stillNeeded)
                                         ->get();
                \Log::info('Additional images:', $additionalImages->toArray());
                $images = $images->merge($additionalImages);
            }

            // Shuffle untuk variasi dan ambil maksimal 6
            $finalImages = $images->shuffle()->take(6)->map(function ($image) {
                $result = [
                    'url' => $image->url_lengkap,
                    'kategori' => $image->kategori,
                    'title' => $this->getImageTitle($image),
                    'debug_info' => $image->getDebugUrlInfo() // TAMBAHAN untuk debug
                ];
                \Log::info('Final image processed:', $result);
                return $result;
            });

            \Log::info('Final gallery images count:', ['count' => $finalImages->count()]);

            return $finalImages;

        } catch (\Exception $e) {
            \Log::error('Error in getGalleryImages:', ['error' => $e->getMessage()]);
            return collect([]);
        }
    }

    /**
     * Method untuk mendapatkan title gambar (TANPA PENGURUS)
     */
    private function getImageTitle($image)
    {
        switch ($image->kategori) {
            case 'FASILITAS':
                return 'Fasilitas Laboratorium';
            case 'ACARA':
                // Cek apakah ada relasi artikel
                if ($image->artikel && $image->artikel->nama_acara) {
                    return $image->artikel->nama_acara;
                }
                return 'Kegiatan Laboratorium';
            default:
                return 'Laboratorium Fisika Dasar';
        }
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
