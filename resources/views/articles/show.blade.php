{{-- resources/views/articles/show.blade.php --}}
@extends('layouts.app')
@section('title', $article['title'] . ' - Laboratorium Fisika Dasar')
@section('content')

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 1px 1px, #2563eb 1px, transparent 0); background-size: 20px 20px;"></div>
    </div>

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('articles.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Artikel</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-700 font-medium">{{ $article['title'] }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Article Header -->
        <div class="text-center mb-8">
            <!-- Category Badge -->
            <div class="mb-6">
                @if($article['category'] == 'Penelitian')
                    <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $article['category'] }}</span>
                @elseif($article['category'] == 'Pendidikan')
                    <span class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $article['category'] }}</span>
                @elseif($article['category'] == 'Kolaborasi')
                    <span class="bg-green-600 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $article['category'] }}</span>
                @else
                    <span class="bg-gray-600 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $article['category'] }}</span>
                @endif
            </div>

            <!-- Title -->
            <h1 class="font-poppins text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $article['title'] }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap justify-center items-center gap-6 text-gray-600 mb-8">
                <div class="flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    <span>{{ $article['author'] }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-2 text-blue-600"></i>
                    <span>{{ date('d M Y', strtotime($article['date'])) }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2 text-blue-600"></i>
                    <span>5 menit baca</span>
                </div>
            </div>

            <!-- Share Buttons -->
            <div class="flex justify-center items-center gap-4 mb-8">
                <span class="text-gray-500 text-sm">Bagikan:</span>
                <a href="#" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition-colors duration-200">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="w-10 h-10 bg-blue-400 hover:bg-blue-500 text-white rounded-full flex items-center justify-center transition-colors duration-200">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="w-10 h-10 bg-blue-700 hover:bg-blue-800 text-white rounded-full flex items-center justify-center transition-colors duration-200">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="#" class="w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-full flex items-center justify-center transition-colors duration-200">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        <!-- Featured Image -->
        <div class="mb-12">
            <img src="{{ asset($article['image']) }}"
                 alt="{{ $article['title'] }}"
                 class="w-full h-80 object-cover rounded-3xl shadow-lg">
        </div>

        <!-- Article Content -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-700 leading-relaxed text-lg">
                {!! $article['content'] !!}
            </div>
        </div>

        <!-- Tags Section -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags:</h3>
            <div class="flex flex-wrap gap-2">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $article['category'] }}</span>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Fisika</span>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Laboratorium</span>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Penelitian</span>
            </div>
        </div>

        <!-- Author Bio -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-lg mb-2">{{ $article['author'] }}</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Peneliti dan dosen di Laboratorium Fisika Dasar dengan fokus pada bidang geofisika dan instrumentasi.
                            Memiliki pengalaman lebih dari 10 tahun dalam penelitian dan pengembangan teknologi fisika.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('articles.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Artikel
                </a>

                <div class="flex items-center gap-4">
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                        <i class="fas fa-print mr-2"></i>
                        Cetak
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-bookmark mr-2"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Articles -->
<section class="py-16 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-poppins text-3xl font-bold text-gray-900 mb-4">Artikel Terkait</h2>
            <p class="text-gray-600">Artikel lainnya yang mungkin menarik untuk Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Related Article 1 -->
            <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <img src="{{ asset('images/article-2.jpg') }}"
                     alt="Related Article"
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-6">
                    <div class="text-sm text-blue-600 font-medium mb-2">Pendidikan</div>
                    <h3 class="font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-200">
                        Inovasi Metode Praktikum Fisika Modern
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Penerapan teknologi AR dan VR dalam praktikum fisika...
                    </p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Baca Selengkapnya →
                    </a>
                </div>
            </article>

            <!-- Related Article 2 -->
            <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <img src="{{ asset('images/article-3.jpg') }}"
                     alt="Related Article"
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-6">
                    <div class="text-sm text-green-600 font-medium mb-2">Kolaborasi</div>
                    <h3 class="font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-200">
                        Kerjasama Penelitian dengan Universitas Tokyo
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Program pertukaran peneliti dan mahasiswa dalam bidang...
                    </p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Baca Selengkapnya →
                    </a>
                </div>
            </article>

            <!-- Related Article 3 -->
            <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <img src="{{ asset('images/article-4.jpg') }}"
                     alt="Related Article"
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-6">
                    <div class="text-sm text-purple-600 font-medium mb-2">Publikasi</div>
                    <h3 class="font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-200">
                        Publikasi Penelitian di Jurnal Internasional
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Tim peneliti berhasil mempublikasikan hasil penelitian...
                    </p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Baca Selengkapnya →
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>

<style>
/* Typography */
.font-poppins {
    font-family: 'Poppins', sans-serif;
}

/* Prose styling for article content */
.prose {
    color: #374151;
    max-width: none;
}

.prose h2 {
    color: #1f2937;
    font-weight: 700;
    font-size: 1.875rem;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.prose h3 {
    color: #1f2937;
    font-weight: 600;
    font-size: 1.5rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.prose p {
    margin-bottom: 1.25rem;
    line-height: 1.7;
}

.prose strong {
    color: #1f2937;
    font-weight: 600;
}

.prose ul, .prose ol {
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }

    body {
        font-size: 12pt;
        line-height: 1.4;
    }

    h1, h2, h3 {
        page-break-after: avoid;
    }
}
</style>

@endsection
