<section id="laboratorium" class="py-24 relative">
    <!-- Top Divider/Separator -->
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-32 h-1 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full"></div>

    <!-- Decorative Background Elements -->
    <div class="absolute top-20 left-10 w-32 h-32 bg-gray-100 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute bottom-20 right-10 w-40 h-40 bg-gray-200 rounded-full opacity-30 blur-3xl"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-20 scroll-animate" data-animation="fade-down">
            <div class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-blue-700 text-sm font-medium mb-6">
                <i class="fas fa-flask mr-2 text-blue-600"></i>
                Galeri Laboratorium
            </div>
            <h2 class="font-poppins text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Jelajahi <span class="bg-clip-text text-transparent" style="background-image: linear-gradient(to right, #1e3a8a, #2563eb);">Laboratorium Kami</span>
            </h2>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
                Lihat fasilitas canggih dan lingkungan penelitian kami yang mendukung inovasi di bidang fisika
            </p>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            @if($galleryImages && count($galleryImages) > 0)
                @foreach($galleryImages as $index => $image)
                <!-- Gallery Item {{ $index + 1 }} -->
                <div class="group relative rounded-3xl overflow-hidden shadow-lg bg-white border border-blue-200 hover:shadow-blue-200/50 transition-all duration-300 scroll-animate"
                     data-animation="fade-down"
                     data-delay="{{ ($index + 1) * 100 }}"
                     onclick="openImageModal('{{ $image['url'] }}', '{{ $image['title'] }}', '{{ $image['kategori'] }}')">

                    <img src="{{ $image['url'] }}"
                         alt="{{ $image['title'] }}"
                         class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700 cursor-pointer">

                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <!-- Title Overlay -->
                    <div class="absolute bottom-4 left-4 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div class="bg-black/60 backdrop-blur-sm rounded-lg px-3 py-2">
                            <p class="text-lg font-poppins font-semibold">{{ $image['title'] }}</p>
                            <span class="text-xs bg-blue-500 px-2 py-1 rounded-full uppercase">{{ $image['kategori'] }}</span>
                        </div>
                    </div>

                    <!-- Zoom Icon -->
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-search-plus text-blue-600"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback/Default Images jika tidak ada data -->
                <div class="group relative rounded-3xl overflow-hidden shadow-lg bg-white border border-blue-200 hover:shadow-blue-200/50 transition-all duration-300 scroll-animate" data-animation="fade-down" data-delay="100">
                    <img src="https://images.unsplash.com/photo-1581092160607-0c6354e4dffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Laboratorium Instrumentasi" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-blue-900 text-lg font-poppins font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        Laboratorium Instrumentasi
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-lg bg-white border border-blue-200 hover:shadow-blue-200/50 transition-all duration-300 scroll-animate" data-animation="fade-down" data-delay="200">
                    <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Stasiun Penelitian" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-blue-900 text-lg font-poppins font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        Stasiun Penelitian
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-lg bg-white border border-blue-200 hover:shadow-blue-200/50 transition-all duration-300 scroll-animate" data-animation="fade-down" data-delay="300">
                    <img src="https://images.unsplash.com/photo-1581093458791-8b0e48d81e64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Ruang Analisis Data" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-blue-900 text-lg font-poppins font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        Ruang Analisis Data
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-lg bg-white border border-blue-200 hover:shadow-blue-200/50 transition-all duration-300 scroll-animate" data-animation="fade-down" data-delay="400">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Area Pengujian Lapangan" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-blue-900 text-lg font-poppins font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        Area Pengujian Lapangan
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-lg bg-white border border-blue-200 hover:shadow-blue-200/50 transition-all duration-300 scroll-animate" data-animation="fade-down" data-delay="500">
                    <img src="https://images.unsplash.com/photo-1581091877018-4b6dada07c21?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Laboratorium Seismik" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-blue-900 text-lg font-poppins font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        Laboratorium Seismik
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-lg bg-white border border-blue-200 hover:shadow-blue-200/50 transition-all duration-300 scroll-animate" data-animation="fade-down" data-delay="600">
                    <img src="https://images.unsplash.com/photo-1581092334651-d23647b6d81d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Bengkel Geofisika" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-800/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-4 left-4 text-blue-900 text-lg font-poppins font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        Bengkel Geofisika
                    </div>
                </div>
            @endif
        </div>

        <!-- View More Button -->
        <div class="text-center scroll-animate" data-animation="fade-up" data-delay="700">
            <a href="{{ route('facilities') }}"
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-blue-600/25 transform hover:-translate-y-1">
                <i class="fas fa-images mr-3"></i>
                Lihat Semua Fasilitas
                <i class="fas fa-arrow-right ml-3"></i>
            </a>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="galleryImageModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-5xl max-h-[90vh] mx-auto">
        <button onclick="closeGalleryModal()"
                class="absolute -top-12 right-0 bg-white/20 hover:bg-white/30 text-white border border-white/30 w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-200 z-10">
            <i class="fas fa-times"></i>
        </button>
        <img id="galleryModalImage" src="" alt="" class="w-full h-auto rounded-lg shadow-2xl">
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent text-white p-6 rounded-b-lg">
            <h3 id="galleryModalTitle" class="text-xl font-semibold mb-2"></h3>
            <span id="galleryModalCategory" class="inline-block bg-blue-500 px-3 py-1 rounded-full text-sm uppercase"></span>
        </div>
    </div>
</div>

<style>
/* Laboratorium section styles */
#laboratorium {
    background: #ffffff !important;
    position: relative;
    z-index: 10;
}

#laboratorium::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff;
    z-index: -1;
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

/* Card hover glow effect */
.group:hover {
    box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.2);
}

/* Button hover effects */
.hover\:shadow-blue-600\/25:hover {
    box-shadow: 0 25px 50px -12px rgba(29, 78, 216, 0.25);
}

/* Typography */
.font-poppins {
    font-family: 'Poppins', sans-serif;
}

/* Consistent spacing */
.space-y-4 > * + * {
    margin-top: 1rem;
}

/* Modern card design */
.rounded-3xl {
    border-radius: 1.5rem;
}

/* === ANIMASI LABORATORIUM === */
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

/* Animasi dari bawah */
.scroll-animate[data-animation="fade-up"] {
    transform: translateY(50px);
}

.scroll-animate[data-animation="fade-up"].animate {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.scroll-animate');
    const labSection = document.getElementById('laboratorium');
    let lastScrollY = window.scrollY;
    let labSectionTop = labSection.offsetTop - 200;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const currentScrollY = window.scrollY;
            const scrollDirection = currentScrollY > lastScrollY ? 'down' : 'up';

            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => {
                    entry.target.classList.add('animate');
                }, delay);
            } else {
                if (scrollDirection === 'up' && currentScrollY < labSectionTop) {
                    entry.target.classList.remove('animate');
                }
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

    window.addEventListener('resize', () => {
        labSectionTop = labSection.offsetTop - 200;
    });
});

// Gallery Modal Functions
function openImageModal(imageSrc, title, category) {
    const modal = document.getElementById('galleryImageModal');
    const modalImage = document.getElementById('galleryModalImage');
    const modalTitle = document.getElementById('galleryModalTitle');
    const modalCategory = document.getElementById('galleryModalCategory');

    modalImage.src = imageSrc;
    modalTitle.textContent = title;
    modalCategory.textContent = category;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Prevent body scrolling
    document.body.style.overflow = 'hidden';
}

function closeGalleryModal() {
    const modal = document.getElementById('galleryImageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('galleryImageModal');
    if (e.target === modal) {
        closeGalleryModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGalleryModal();
    }
});
</script>
