<section id="about" class="py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mt-10 mb-20 scroll-animate" data-animation="fade-down">
            <div class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-full text-blue-700 text-sm font-medium mb-6">
                <i class="fas fa-university mr-2"></i>
                Tentang Laboratorium
            </div>
            <h2 class="font-poppins text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Laboratorium <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Fisika Dasar</span>
            </h2>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
                Laboratorium Fisika Dasar merupakan fasilitas unggulan yang berkomitmen untuk mengembangkan penelitian dan pendidikan di bidang fisika dengan teknologi terdepan.
            </p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <!-- Left Side - Image -->
            <div class="relative scroll-animate" data-animation="slide-left">
                <div class="relative overflow-hidden rounded-3xl shadow-2xl">
                    <img src="{{ asset('images/hero-1.jpg') }}" alt="Laboratorium Fisika Dasar"
                         class="w-full h-[500px] object-cover transform hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="space-y-8 scroll-animate" data-animation="slide-right">
                <!-- Vision -->
                <div>
                    <h3 class="text-3xl font-bold text-blue-600 mb-4">Visi Kami</h3>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Menjadi laboratorium fisika terdepan di Indonesia yang berkontribusi dalam penelitian dan pengembangan ilmu fisika untuk kemajuan bangsa.
                    </p>
                </div>

                <!-- Mission -->
                <div>
                    <h3 class="text-3xl font-bold text-blue-600 mb-6">Misi Kami</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-gray-700 text-lg">Menyediakan fasilitas penelitian fisika berkualitas tinggi</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-gray-700 text-lg">Mengembangkan sumber daya manusia di bidang fisika</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-gray-700 text-lg">Berkolaborasi dalam penelitian bertaraf internasional</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Existing styles tetap sama */
#about {
    background-color: #ffffff !important;
    background-image: none !important;
    position: relative;
    z-index: 10;
}

#about::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    z-index: -1;
}

/* Custom gradient text */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

/* === ANIMASI SEDERHANA === */
.scroll-animate {
    opacity: 0;
    transition: all 0.6s ease-out;
}

/* Animasi dari atas */
.scroll-animate[data-animation="fade-down"] {
    transform: translateY(-50px);
}

.scroll-animate[data-animation="fade-down"].animate {
    opacity: 1;
    transform: translateY(0);
}

/* Animasi dari kiri */
.scroll-animate[data-animation="slide-left"] {
    transform: translateX(-100px);
}

.scroll-animate[data-animation="slide-left"].animate {
    opacity: 1;
    transform: translateX(0);
}

/* Animasi dari kanan */
.scroll-animate[data-animation="slide-right"] {
    transform: translateX(100px);
}

.scroll-animate[data-animation="slide-right"].animate {
    opacity: 1;
    transform: translateX(0);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.scroll-animate');
    const aboutSection = document.getElementById('about');
    let lastScrollY = window.scrollY;
    let aboutSectionTop = aboutSection.offsetTop;
    let aboutSectionBottom = aboutSection.offsetTop + aboutSection.offsetHeight;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const currentScrollY = window.scrollY;
            const scrollDirection = currentScrollY > lastScrollY ? 'down' : 'up';

            if (entry.isIntersecting) {
                // Selalu tampilkan animasi ketika element terlihat
                entry.target.classList.add('animate');
            } else {
                // Hanya reset animasi jika scroll ke atas DAN berada di atas section about
                if (scrollDirection === 'up' && currentScrollY < aboutSectionTop) {
                    entry.target.classList.remove('animate');
                }
                // Jika scroll dari bawah ke atas tapi masih di bawah section about, tetap tampilkan
            }

            lastScrollY = currentScrollY;
        });
    }, {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(element => {
        observer.observe(element);
    });

    // Update posisi section saat window resize
    window.addEventListener('resize', () => {
        aboutSectionTop = aboutSection.offsetTop;
        aboutSectionBottom = aboutSection.offsetTop + aboutSection.offsetHeight;
    });
});
</script>
