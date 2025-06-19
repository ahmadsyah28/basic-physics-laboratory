{{-- resources/views/articles/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Artikel - Laboratorium Fisika Dasar')
@section('content')

<!-- Hero Section -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.jpg') }}"
             alt="Hero Background"
             class="w-full h-full object-cover transform scale-105 transition-transform duration-[20s] ease-in-out hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-br from-[#968c82]/80 via-[#635849]/70 to-[#443f35]/80"></div>
    </div>

    <!-- Animated Particles -->
    <div class="absolute inset-0 z-10">
        <div class="articles-molecules-container" id="articles-molecules-container"></div>
    </div>

    <!-- Content -->
    <div class="relative z-20 mx-6 px-4 sm:px-6 lg:px-8 text-center max-w-4xl">
        <!-- Breadcrumb -->
        <div class="articles-hero-animate mb-8 opacity-0" data-animation="fade-down">
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-amber-200 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-amber-300 mx-3"></i>
                            <span class="text-white font-medium">Artikel & Berita</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Title -->
        <div class="articles-hero-animate mb-8 opacity-0" data-animation="fade-up" data-delay="200">
            <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6">
                <span class="text-white drop-shadow-lg">Artikel</span>
                <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent drop-shadow-lg"> Laboratorium</span>
            </h1>
            <p class="text-xl md:text-2xl text-amber-100 max-w-3xl mx-auto leading-relaxed drop-shadow-md mb-8">
                Temukan berita terkini, penelitian terdepan, dan perkembangan inovasi dari Laboratorium Fisika Dasar
            </p>

            <!-- Stats -->
            <div class="flex flex-wrap justify-center gap-8 articles-hero-animate opacity-0" data-animation="fade-up" data-delay="400">
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-4">
                    <div class="text-3xl font-bold text-yellow-400">{{ count($articles) }}+</div>
                    <div class="text-amber-100">Artikel</div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-4">
                    <div class="text-3xl font-bold text-orange-400">5+</div>
                    <div class="text-amber-100">Kategori</div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-4">
                    <div class="text-3xl font-bold text-white">{{ date('Y') }}</div>
                    <div class="text-amber-100">Terbaru</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="py-8 bg-white border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
            <button class="filter-btn active px-6 py-3 bg-blue-600 text-white rounded-full font-medium transition-all duration-300 hover:bg-blue-700" data-filter="all">
                Semua Artikel
            </button>
            <button class="filter-btn px-6 py-3 bg-gray-100 text-gray-700 rounded-full font-medium transition-all duration-300 hover:bg-blue-600 hover:text-white" data-filter="penelitian">
                Penelitian
            </button>
            <button class="filter-btn px-6 py-3 bg-gray-100 text-gray-700 rounded-full font-medium transition-all duration-300 hover:bg-blue-600 hover:text-white" data-filter="pendidikan">
                Pendidikan
            </button>
            <button class="filter-btn px-6 py-3 bg-gray-100 text-gray-700 rounded-full font-medium transition-all duration-300 hover:bg-blue-600 hover:text-white" data-filter="kolaborasi">
                Kolaborasi
            </button>
            <button class="filter-btn px-6 py-3 bg-gray-100 text-gray-700 rounded-full font-medium transition-all duration-300 hover:bg-blue-600 hover:text-white" data-filter="publikasi">
                Publikasi
            </button>
        </div>
    </div>
</section>

<!-- Articles Section -->
<section class="py-20 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Featured Article (First Article) -->
        @if(count($articles) > 0)
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Artikel Unggulan</h2>
            <article class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                <div class="lg:flex">
                    <div class="lg:w-1/2">
                        <img src="{{ asset($articles[0]['image']) }}"
                             alt="{{ $articles[0]['title'] }}"
                             class="w-full h-64 lg:h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="lg:w-1/2 p-8 lg:p-12">
                        <div class="flex items-center mb-4">
                            @if($articles[0]['category'] == 'Penelitian')
                                <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $articles[0]['category'] }}</span>
                            @elseif($articles[0]['category'] == 'Pendidikan')
                                <span class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $articles[0]['category'] }}</span>
                            @elseif($articles[0]['category'] == 'Kolaborasi')
                                <span class="bg-green-600 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $articles[0]['category'] }}</span>
                            @else
                                <span class="bg-gray-600 text-white px-4 py-2 rounded-full text-sm font-medium">{{ $articles[0]['category'] }}</span>
                            @endif
                            <span class="text-gray-500 text-sm ml-4">{{ date('d M Y', strtotime($articles[0]['date'])) }}</span>
                        </div>

                        <h3 class="font-poppins text-2xl lg:text-3xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors duration-300">
                            {{ $articles[0]['title'] }}
                        </h3>

                        <p class="text-gray-600 leading-relaxed mb-6 text-lg">
                            {{ $articles[0]['excerpt'] }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $articles[0]['author'] }}</span>
                            </div>

                            <a href="{{ route('articles.show', $articles[0]['slug']) }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors duration-200 group">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right ml-2 text-sm group-hover:translate-x-1 transition-transform duration-200"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        @endif

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="articles-grid">
            @foreach(array_slice($articles, 1) as $article)
            <article class="article-card group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" data-category="{{ strtolower($article['category']) }}">
                <div class="relative overflow-hidden">
                    <img src="{{ asset($article['image']) }}"
                         alt="{{ $article['title'] }}"
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute top-4 left-4">
                        @if($article['category'] == 'Penelitian')
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium">{{ $article['category'] }}</span>
                        @elseif($article['category'] == 'Pendidikan')
                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium">{{ $article['category'] }}</span>
                        @elseif($article['category'] == 'Kolaborasi')
                            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-medium">{{ $article['category'] }}</span>
                        @elseif($article['category'] == 'Publikasi')
                            <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-medium">{{ $article['category'] }}</span>
                        @else
                            <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-medium">{{ $article['category'] }}</span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>{{ date('d M Y', strtotime($article['date'])) }}</span>
                        <i class="fas fa-user ml-4 mr-2"></i>
                        <span>{{ $article['author'] }}</span>
                    </div>

                    <h3 class="font-poppins text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2">
                        {{ $article['title'] }}
                    </h3>

                    <p class="text-gray-600 leading-relaxed mb-4 line-clamp-3">
                        {{ $article['excerpt'] }}
                    </p>

                    <a href="{{ route('articles.show', $article['slug']) }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors duration-200 group">
                        Baca Selengkapnya
                        <i class="fas fa-arrow-right ml-2 text-sm group-hover:translate-x-1 transition-transform duration-200"></i>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-16">
            <button class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-full hover:from-blue-700 hover:to-blue-800 transform hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                <i class="fas fa-plus mr-2"></i>
                Muat Artikel Lainnya
            </button>
        </div>
    </div>
</section>

<style>
/* Enhanced Gradient Text */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ===== MOLECULAR ANIMATION STYLES ===== */
.articles-molecules-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.articles-molecule {
    position: absolute;
    width: 60px;
    height: 60px;
    opacity: 0.8;
    pointer-events: none;
}

.articles-atom {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.9), rgba(255,215,0,0.4));
    box-shadow: 0 0 20px rgba(255,255,255,0.6);
}

.articles-atom.core {
    width: 12px;
    height: 12px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: articlesPulse 2s infinite ease-in-out;
}

.articles-atom.electron {
    width: 6px;
    height: 6px;
}

.articles-orbit {
    position: absolute;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.articles-orbit-1 {
    width: 40px;
    height: 40px;
    animation: articlesRotate 4s linear infinite;
}

.articles-orbit-2 {
    width: 55px;
    height: 55px;
    animation: articlesRotate 6s linear infinite reverse;
}

.articles-orbit-1 .articles-atom.electron {
    top: -3px;
    left: 50%;
    transform: translateX(-50%);
}

.articles-orbit-2 .articles-atom.electron {
    top: -3px;
    left: 50%;
    transform: translateX(-50%);
}

/* Keyframe Animations */
@keyframes articlesRotate {
    from { transform: translate(-50%, -50%) rotate(0deg); }
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

@keyframes articlesPulse {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
    }
    50% {
        transform: translate(-50%, -50%) scale(1.3);
    }
}

.articles-molecule.float {
    animation: articlesMoleculeFloat 18s infinite linear;
}

@keyframes articlesMoleculeFloat {
    0% {
        transform: translateY(100vh) translateX(-50px) rotate(0deg) scale(0.5);
        opacity: 0;
    }
    5% {
        opacity: 0.8;
        transform: translateY(95vh) translateX(-40px) rotate(18deg) scale(0.7);
    }
    25% {
        transform: translateY(75vh) translateX(-10px) rotate(90deg) scale(1);
    }
    50% {
        transform: translateY(50vh) translateX(20px) rotate(180deg) scale(1.1);
    }
    75% {
        transform: translateY(25vh) translateX(40px) rotate(270deg) scale(1);
    }
    95% {
        opacity: 0.8;
        transform: translateY(5vh) translateX(60px) rotate(342deg) scale(0.7);
    }
    100% {
        transform: translateY(-10vh) translateX(80px) rotate(360deg) scale(0.5);
        opacity: 0;
    }
}

/* Molecule Color Variants */
.articles-molecule.amber .articles-atom.core {
    background: radial-gradient(circle, #fbbf24, #d97706);
    box-shadow: 0 0 25px rgba(251, 191, 36, 0.9);
}

.articles-molecule.amber .articles-atom.electron {
    background: radial-gradient(circle, #fbbf24, #d97706);
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.7);
}

.articles-molecule.orange .articles-atom.core {
    background: radial-gradient(circle, #fb923c, #ea580c);
    box-shadow: 0 0 25px rgba(251, 146, 60, 0.8);
}

.articles-molecule.orange .articles-atom.electron {
    background: radial-gradient(circle, #fb923c, #ea580c);
    box-shadow: 0 0 15px rgba(251, 146, 60, 0.7);
}

.articles-molecule.brown .articles-atom.core {
    background: radial-gradient(circle, #a78bfa, #7c3aed);
    box-shadow: 0 0 25px rgba(167, 139, 250, 0.8);
}

.articles-molecule.brown .articles-atom.electron {
    background: radial-gradient(circle, #a78bfa, #7c3aed);
    box-shadow: 0 0 15px rgba(167, 139, 250, 0.7);
}

/* ===== ARTICLES PAGE SPECIFIC STYLES ===== */
.articles-hero-animate {
    opacity: 0;
    transition: all 0.8s ease-out;
}

.articles-hero-animate[data-animation="fade-down"] {
    transform: translateY(-40px);
}

.articles-hero-animate[data-animation="fade-up"] {
    transform: translateY(40px);
}

.articles-hero-animate.animate {
    opacity: 1;
    transform: translateY(0);
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Gradient text effect */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

/* Filter transition */
.article-card {
    transition: all 0.3s ease;
}

.article-card.hidden {
    display: none;
}

/* Typography */
.font-poppins {
    font-family: 'Poppins', sans-serif;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== MOLECULAR ANIMATION =====
    function createArticlesMolecularAnimation() {
        const container = document.getElementById('articles-molecules-container');
        if (!container) return;

        const moleculeTypes = ['amber', 'orange', 'brown'];
        const moleculeSizes = ['small', 'normal', 'large'];

        function createMolecule() {
            const molecule = document.createElement('div');
            const type = moleculeTypes[Math.floor(Math.random() * moleculeTypes.length)];
            const size = moleculeSizes[Math.floor(Math.random() * moleculeSizes.length)];

            molecule.className = `articles-molecule float ${type} ${size}`;
            molecule.style.left = Math.random() * 100 + '%';
            molecule.style.animationDelay = Math.random() * 5 + 's';
            molecule.style.animationDuration = (15 + Math.random() * 10) + 's';

            // Core atom
            const core = document.createElement('div');
            core.className = 'articles-atom core';
            molecule.appendChild(core);

            // Create orbits
            const numOrbits = Math.random() > 0.5 ? 2 : 1;
            for (let i = 1; i <= numOrbits; i++) {
                const orbit = document.createElement('div');
                orbit.className = `articles-orbit articles-orbit-${i}`;

                const electron = document.createElement('div');
                electron.className = 'articles-atom electron';
                orbit.appendChild(electron);

                molecule.appendChild(orbit);
            }

            container.appendChild(molecule);

            // Cleanup
            setTimeout(() => {
                if (container.contains(molecule)) {
                    container.removeChild(molecule);
                }
            }, 20000);
        }

        // Initialize molecules
        for (let i = 0; i < 3; i++) {
            setTimeout(() => createMolecule(), i * 2000);
        }

        const moleculeInterval = setInterval(createMolecule, 3000);

        window.addEventListener('beforeunload', () => {
            clearInterval(moleculeInterval);
        });
    }

    // ===== ANIMATIONS =====
    createArticlesMolecularAnimation();

    // Hero animations
    const heroElements = document.querySelectorAll('.articles-hero-animate');
    setTimeout(() => {
        heroElements.forEach(element => {
            const delay = element.dataset.delay || 0;
            setTimeout(() => {
                element.classList.add('animate');
            }, delay);
        });
    }, 300);

    // Filter functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const articleCards = document.querySelectorAll('.article-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-blue-600', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-700');
            });

            this.classList.add('active', 'bg-blue-600', 'text-white');
            this.classList.remove('bg-gray-100', 'text-gray-700');

            // Filter articles
            articleCards.forEach(card => {
                const category = card.dataset.category;

                if (filter === 'all' || category === filter) {
                    card.classList.remove('hidden');
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.classList.add('hidden');
                    }, 300);
                }
            });
        });
    });

    // ===== PARALLAX EFFECT =====
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.articles-molecules-container');
        if (parallax) {
            const speed = scrolled * 0.3;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    });
});
</script>

@endsection
