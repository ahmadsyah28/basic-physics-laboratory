{{-- resources/views/components/articles.blade.php --}}
<section id="articles" class="py-24 bg-white relative">
  <!-- Top Divider/Separator -->
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-32 h-1 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-20 articles-animate" data-animation="fade-down">
            <div class="inline-flex items-center px-6 py-3 bg-blue-50 border border-blue-200 rounded-full text-blue-700 text-sm font-medium mb-8 shadow-lg">
                <i class="fas fa-newspaper mr-2 text-blue-600"></i>
                Artikel & Berita
            </div>
            <h2 class="font-poppins text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Artikel <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Terbaru</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Berita terkini, penelitian, dan perkembangan di Laboratorium Fisika Dasar
            </p>
            <div class="mt-8 w-24 h-1 bg-gradient-to-r from-blue-600 to-blue-800 mx-auto rounded-full"></div>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @if(isset($featuredArticles) && count($featuredArticles) > 0)
                @foreach($featuredArticles as $index => $article)
                <!-- Article {{ $index + 1 }} -->
                <article class="group bg-white rounded-3xl overflow-hidden border border-gray-100 hover:border-blue-200 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 articles-animate" data-animation="fade-up" data-delay="{{ ($index + 1) * 100 }}">
                    <div class="relative overflow-hidden">
                        <img src="{{ isset($article['image']) ? $article['image'] : asset('images/article/default.jpg') }}"
                             alt="{{ $article['title'] ?? 'Artikel Laboratorium' }}"
                             class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105"
                             onerror="this.src='{{ asset('images/article/default.jpg') }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute top-4 left-4">
                            <div class="w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <i class="fas fa-newspaper text-blue-600 text-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ isset($article['date']) ? \Carbon\Carbon::parse($article['date'])->format('d M Y') : 'Tanggal tidak tersedia' }}</span>
                            <i class="fas fa-user ml-4 mr-2"></i>
                            <span>{{ $article['author'] ?? 'Penulis tidak diketahui' }}</span>
                        </div>
                        <h3 class="font-poppins text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">
                            {{ $article['title'] ?? 'Judul artikel tidak tersedia' }}
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            {{ $article['excerpt'] ?? 'Deskripsi artikel tidak tersedia' }}
                        </p>
                        <a href="{{ route('articles.show', $article['id'] ?? '#') }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors duration-200">
                            Baca Selengkapnya
                            <i class="fas fa-arrow-right ml-2 text-sm group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            @else
                <!-- Fallback ketika tidak ada artikel -->
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Artikel</h3>
                    <p class="text-gray-600 mb-6">Artikel akan segera ditampilkan di sini.</p>
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Lihat Halaman Artikel
                    </a>
                </div>
            @endif
        </div>

        <!-- View All Articles Button -->
        <div class="text-center articles-animate" data-animation="fade-up" data-delay="400">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-full hover:from-blue-700 hover:to-blue-800 transform hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                <i class="fas fa-newspaper mr-2"></i>
                Lihat Semua Artikel
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<style>
/* Articles section styling */
#articles {
    background-color: #ffffff !important;
    position: relative;
}

/* Gradient text effect */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom blur effects */
.blur-3xl {
    filter: blur(64px);
}

/* Article card hover effects */
.group:hover {
    box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.15);
}

/* Button hover effects */
.hover\:shadow-blue-500\/25:hover {
    box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.25);
}

/* Typography */
.font-poppins {
    font-family: 'Poppins', sans-serif;
}

/* Modern card design */
.rounded-3xl {
    border-radius: 1.5rem;
}

/* === ANIMASI ARTICLES === */
.articles-animate {
    opacity: 0;
    transition: all 0.6s ease-out;
}

/* Animasi dari atas */
.articles-animate[data-animation="fade-down"] {
    transform: translateY(-60px);
}

.articles-animate[data-animation="fade-down"].animate {
    opacity: 1;
    transform: translateY(0);
}

/* Animasi dari bawah */
.articles-animate[data-animation="fade-up"] {
    transform: translateY(60px);
}

.articles-animate[data-animation="fade-up"].animate {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const articlesElements = document.querySelectorAll('.articles-animate');
    const articlesSection = document.getElementById('articles');
    let lastScrollY = window.scrollY;
    let articlesSectionTop = articlesSection.offsetTop - 200;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const currentScrollY = window.scrollY;
            const scrollDirection = currentScrollY > lastScrollY ? 'down' : 'up';

            if (entry.isIntersecting) {
                // Animasi dengan delay berdasarkan data-delay
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => {
                    entry.target.classList.add('animate');
                }, delay);
            } else {
                // Reset animasi hanya jika scroll ke atas dan berada di atas section
                if (scrollDirection === 'up' && currentScrollY < articlesSectionTop) {
                    entry.target.classList.remove('animate');
                }
            }

            lastScrollY = currentScrollY;
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    });

    articlesElements.forEach(element => {
        observer.observe(element);
    });

    // Update posisi section saat window resize
    window.addEventListener('resize', () => {
        articlesSectionTop = articlesSection.offsetTop - 200;
    });
});
</script>
