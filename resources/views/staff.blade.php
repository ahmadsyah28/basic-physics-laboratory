{{-- resources/views/staff.blade.php --}}
@extends('layouts.app')

@section('title', 'Staff dan Tenaga Ahli - Laboratorium Fisika Dasar')

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
        <div class="staff-molecules-container" id="staff-molecules-container"></div>
    </div>

    <!-- Content -->
    <div class="relative z-20 mx-6 px-4 sm:px-6 lg:px-8 text-center max-w-4xl">
        <!-- Breadcrumb -->
        <div class="staff-hero-animate mb-8 opacity-0" data-animation="fade-down">
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
                            <span class="text-white font-medium">Staff dan Tenaga Ahli</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Title -->
        <div class="staff-hero-animate mb-8 opacity-0" data-animation="fade-up" data-delay="200">
            <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6">
                <span class="text-white drop-shadow-lg">Tim</span>
                <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent drop-shadow-lg"> Profesional</span>
            </h1>
            <p class="text-xl md:text-2xl text-amber-100 max-w-3xl mx-auto leading-relaxed drop-shadow-md">
                Berkenalan dengan para ahli yang berdedikasi mengembangkan ilmu fisika dan mendampingi perjalanan akademik Anda
            </p>
        </div>
    </div>
</section>

<!-- Main Staff Section -->
<section class="py-24 bg-gray-50 relative">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Filter & Search Section -->
        <div class="mb-16 staff-animate" data-animation="fade-down">
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-lg">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <!-- Category Filter -->
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                        <button class="staff-filter-btn active px-6 py-3 bg-gradient-to-r from-[#968c82] to-[#635849] text-white rounded-full font-medium transition-all duration-300 hover:from-[#635849] hover:to-[#443f35] shadow-lg" data-filter="all">
                            <i class="fas fa-users mr-2"></i>Semua Tim ({{ $stats['total_staff'] }})
                        </button>
                        <button class="staff-filter-btn px-6 py-3 bg-white text-amber-700 border border-amber-200 rounded-full font-medium transition-all duration-300 hover:bg-amber-50" data-filter="head-lecturer">
                            <i class="fas fa-crown mr-2"></i>Kepala Laboratorium ({{ $stats['head-researchers'] }})
                        </button>
                        <button class="staff-filter-btn px-6 py-3 bg-white text-amber-700 border border-amber-200 rounded-full font-medium transition-all duration-300 hover:bg-amber-50" data-filter="lecturer">
                            <i class="fas fa-graduation-cap mr-2"></i>Dosen ({{ $stats['lecturers'] }})
                        </button>
                        <button class="staff-filter-btn px-6 py-3 bg-white text-amber-700 border border-amber-200 rounded-full font-medium transition-all duration-300 hover:bg-amber-50" data-filter="technician">
                            <i class="fas fa-tools mr-2"></i>Laboran ({{ $stats['technicians'] }})
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="relative">
                        <input type="text" id="staff-search" placeholder="Cari berdasarkan nama atau keahlian..."
                               class="pl-12 pr-4 py-3 w-80 border border-gray-300 rounded-full focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16" id="staff-grid">
            @foreach($staff as $index => $member)
            <div class="staff-card group relative bg-white rounded-3xl p-8 border border-gray-200 hover:border-amber-200 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 overflow-hidden staff-animate"
                 data-animation="fade-up"
                 data-delay="{{ ($index + 1) * 100 }}"
                 data-category="{{ $member['category'] }}"
                 data-search="{{ strtolower($member['name'] . ' ' . $member['specialization'] . ' ' . implode(' ', $member['research_interests'])) }}">

                <!-- Background Glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-{{ $member['color'] }}-500/5 via-transparent to-{{ $member['color'] }}-700/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="relative z-10">
                    <!-- Header dengan Photo dan Basic Info -->
                    <div class="flex items-start space-x-6 mb-6">
                        <!-- Staff Photo - Changed to Rectangle -->
                        <div class="relative flex-shrink-0">
                            <div class="w-28 h-36 bg-gradient-to-br from-{{ $member['color'] }}-200 to-{{ $member['color'] }}-300 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-500 overflow-hidden">
                                @if($member['photo'])
                                    <img src="{{ asset('images/staff/' . $member['photo']) }}"
                                         alt="{{ $member['name'] }}"
                                         class="w-full h-full object-cover rounded-xl">
                                @else
                                    <i class="fas fa-user text-{{ $member['color'] }}-600 text-3xl"></i>
                                @endif
                            </div>
                            <!-- Badge -->
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-br from-{{ $member['badge_color'] }}-400 to-{{ $member['badge_color'] }}-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-{{ $member['badge_icon'] }} text-white text-xs"></i>
                            </div>
                        </div>

                        <!-- Basic Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-poppins text-xl font-bold text-gray-900 mb-1">
                                {{ $member['name'] }}
                            </h3>
                            <p class="text-{{ $member['color'] }}-600 font-medium mb-2">{{ $member['position'] }}</p>
                            <p class="text-gray-600 text-sm mb-3">{{ $member['specialization'] }}</p>

                            <!-- Quick Contact -->
                            <div class="flex items-center space-x-3">
                                @if($member['email'])
                                <a href="mailto:{{ $member['email'] }}" class="w-8 h-8 bg-{{ $member['color'] }}-600 text-white rounded-lg flex items-center justify-center hover:bg-{{ $member['color'] }}-700 transition-colors duration-300">
                                    <i class="fas fa-envelope text-xs"></i>
                                </a>
                                @endif
                                @if($member['phone'])
                                <a href="tel:{{ $member['phone'] }}" class="w-8 h-8 bg-{{ $member['color'] }}-600 text-white rounded-lg flex items-center justify-center hover:bg-{{ $member['color'] }}-700 transition-colors duration-300">
                                    <i class="fas fa-phone text-xs"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Information -->
                    <div class="space-y-4">
                        <!-- Office Info -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">📍 Kantor:</span>
                                    <span class="text-gray-600 block">{{ $member['office'] }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">🕒 Jam Kerja:</span>
                                    <span class="text-gray-600 block">{{ $member['office_hours'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Research Interests -->
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Bidang Penelitian:</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach(array_slice($member['research_interests'], 0, 3) as $interest)
                                <li class="flex items-center">
                                    <i class="fas fa-circle text-{{ $member['color'] }}-400 text-xs mr-2"></i>
                                    {{ $interest }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Social Links -->
                        @if(count($member['social_links']) > 0)
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Profil Online:</span>
                                <div class="flex space-x-2">
                                    @foreach($member['social_links'] as $platform => $url)
                                    <a href="{{ $url }}" target="_blank" class="w-8 h-8 bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center hover:bg-{{ $member['color'] }}-100 hover:text-{{ $member['color'] }}-600 transition-colors duration-300">
                                        @if($platform === 'linkedin')
                                            <i class="fab fa-linkedin-in text-xs"></i>
                                        @elseif($platform === 'scholar')
                                            <i class="fas fa-graduation-cap text-xs"></i>
                                        @elseif($platform === 'researchgate')
                                            <i class="fab fa-researchgate text-xs"></i>
                                        @elseif($platform === 'github')
                                            <i class="fab fa-github text-xs"></i>
                                        @endif
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
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
.staff-molecules-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.staff-molecule {
    position: absolute;
    width: 60px;
    height: 60px;
    opacity: 0.8;
    pointer-events: none;
}

.staff-atom {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.9), rgba(255,215,0,0.4));
    box-shadow: 0 0 20px rgba(255,255,255,0.6);
}

.staff-atom.core {
    width: 12px;
    height: 12px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: staffPulse 2s infinite ease-in-out;
}

.staff-atom.electron {
    width: 6px;
    height: 6px;
}

.staff-orbit {
    position: absolute;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.staff-orbit-1 {
    width: 40px;
    height: 40px;
    animation: staffRotate 4s linear infinite;
}

.staff-orbit-2 {
    width: 55px;
    height: 55px;
    animation: staffRotate 6s linear infinite reverse;
}

.staff-orbit-1 .staff-atom.electron {
    top: -3px;
    left: 50%;
    transform: translateX(-50%);
}

.staff-orbit-2 .staff-atom.electron {
    top: -3px;
    left: 50%;
    transform: translateX(-50%);
}

/* Keyframe Animations */
@keyframes staffRotate {
    from { transform: translate(-50%, -50%) rotate(0deg); }
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

@keyframes staffPulse {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
    }
    50% {
        transform: translate(-50%, -50%) scale(1.3);
    }
}

.staff-molecule.float {
    animation: staffMoleculeFloat 18s infinite linear;
}

@keyframes staffMoleculeFloat {
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
.staff-molecule.amber .staff-atom.core {
    background: radial-gradient(circle, #fbbf24, #d97706);
    box-shadow: 0 0 25px rgba(251, 191, 36, 0.9);
}

.staff-molecule.amber .staff-atom.electron {
    background: radial-gradient(circle, #fbbf24, #d97706);
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.7);
}

.staff-molecule.orange .staff-atom.core {
    background: radial-gradient(circle, #fb923c, #ea580c);
    box-shadow: 0 0 25px rgba(251, 146, 60, 0.8);
}

.staff-molecule.orange .staff-atom.electron {
    background: radial-gradient(circle, #fb923c, #ea580c);
    box-shadow: 0 0 15px rgba(251, 146, 60, 0.7);
}

.staff-molecule.brown .staff-atom.core {
    background: radial-gradient(circle, #a78bfa, #7c3aed);
    box-shadow: 0 0 25px rgba(167, 139, 250, 0.8);
}

.staff-molecule.brown .staff-atom.electron {
    background: radial-gradient(circle, #a78bfa, #7c3aed);
    box-shadow: 0 0 15px rgba(167, 139, 250, 0.7);
}

/* ===== STAFF PAGE SPECIFIC STYLES ===== */
.staff-hero-animate {
    opacity: 0;
    transition: all 0.8s ease-out;
}

.staff-hero-animate[data-animation="fade-down"] {
    transform: translateY(-40px);
}

.staff-hero-animate[data-animation="fade-up"] {
    transform: translateY(40px);
}

.staff-hero-animate.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Staff Card Styles */
.staff-card {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.staff-card:hover {
    box-shadow: 0 25px 50px -12px rgba(150, 140, 130, 0.15);
}

/* Filter Button Styles */
.staff-filter-btn.active {
    background: linear-gradient(135deg, #968c82 0%, #635849 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(150, 140, 130, 0.3);
}

/* Search Input */
#staff-search:focus {
    transform: scale(1.02);
}

/* Animation Classes */
.staff-animate {
    opacity: 0;
    transition: all 0.6s ease-out;
}

.staff-animate[data-animation="fade-down"] {
    transform: translateY(-60px);
}

.staff-animate[data-animation="fade-up"] {
    transform: translateY(60px);
}

.staff-animate.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Card Filtering */
.staff-card.hidden {
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.5s ease;
}

.staff-card.visible {
    opacity: 1;
    transform: scale(1);
    transition: all 0.5s ease;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .staff-card {
        margin-bottom: 1.5rem;
    }

    #staff-search {
        width: 100%;
    }

    .staff-molecule {
        width: 40px;
        height: 40px;
    }

    .staff-orbit-1 {
        width: 30px;
        height: 30px;
    }

    .staff-orbit-2 {
        width: 40px;
        height: 40px;
    }

    .staff-card .w-28 {
        width: 6rem;
    }

    .staff-card .h-36 {
        height: 8rem;
    }
}

@media (max-width: 640px) {
    .staff-card .flex-col {
        flex-direction: column;
    }

    .staff-card .space-x-6 {
        space-x: 0;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== MOLECULAR ANIMATION =====
    function createStaffMolecularAnimation() {
        const container = document.getElementById('staff-molecules-container');
        if (!container) return;

        const moleculeTypes = ['amber', 'orange', 'brown'];
        const moleculeSizes = ['small', 'normal', 'large'];

        function createMolecule() {
            const molecule = document.createElement('div');
            const type = moleculeTypes[Math.floor(Math.random() * moleculeTypes.length)];
            const size = moleculeSizes[Math.floor(Math.random() * moleculeSizes.length)];

            molecule.className = `staff-molecule float ${type} ${size}`;
            molecule.style.left = Math.random() * 100 + '%';
            molecule.style.animationDelay = Math.random() * 5 + 's';
            molecule.style.animationDuration = (15 + Math.random() * 10) + 's';

            // Core atom
            const core = document.createElement('div');
            core.className = 'staff-atom core';
            molecule.appendChild(core);

            // Create orbits
            const numOrbits = Math.random() > 0.5 ? 2 : 1;
            for (let i = 1; i <= numOrbits; i++) {
                const orbit = document.createElement('div');
                orbit.className = `staff-orbit staff-orbit-${i}`;

                const electron = document.createElement('div');
                electron.className = 'staff-atom electron';
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
    createStaffMolecularAnimation();

    // Hero animations
    const heroElements = document.querySelectorAll('.staff-hero-animate');
    setTimeout(() => {
        heroElements.forEach(element => {
            const delay = element.dataset.delay || 0;
            setTimeout(() => {
                element.classList.add('animate');
            }, delay);
        });
    }, 300);

    // Staff section animations
    const staffElements = document.querySelectorAll('.staff-animate');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => {
                    entry.target.classList.add('animate');
                }, delay);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    });

    staffElements.forEach(element => {
        observer.observe(element);
    });

    // ===== FILTERING & SEARCH =====
    const filterButtons = document.querySelectorAll('.staff-filter-btn');
    const staffCards = document.querySelectorAll('.staff-card');
    const searchInput = document.getElementById('staff-search');

    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;

            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            updateFilterButtonStyles();

            // Filter cards
            filterCards(filter, searchInput.value);
        });
    });

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const activeFilter = document.querySelector('.staff-filter-btn.active').dataset.filter;
            filterCards(activeFilter, searchTerm);
        });
    }

    // Filter cards function
    function filterCards(categoryFilter, searchTerm) {
        staffCards.forEach(card => {
            const category = card.dataset.category;
            const searchData = card.dataset.search || '';

            const categoryMatch = categoryFilter === 'all' || category === categoryFilter;
            const searchMatch = searchTerm === '' || searchData.includes(searchTerm);

            if (categoryMatch && searchMatch) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.classList.remove('hidden');
                    card.classList.add('visible');
                }, 50);
            } else {
                card.classList.remove('visible');
                card.classList.add('hidden');
                setTimeout(() => {
                    card.style.display = 'none';
                }, 500);
            }
        });
    }

    // Update filter button styles
    function updateFilterButtonStyles() {
        filterButtons.forEach(btn => {
            if (btn.classList.contains('active')) {
                btn.className = 'staff-filter-btn active px-6 py-3 bg-gradient-to-r from-[#968c82] to-[#635849] text-white rounded-full font-medium transition-all duration-300 hover:from-[#635849] hover:to-[#443f35] shadow-lg';
            } else {
                btn.className = 'staff-filter-btn px-6 py-3 bg-white text-amber-700 border border-amber-200 rounded-full font-medium transition-all duration-300 hover:bg-amber-50';
            }
        });
    }

    // Initialize all cards as visible
    staffCards.forEach(card => {
        card.classList.add('visible');
    });

    // ===== PARALLAX EFFECT =====
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.staff-molecules-container');
        if (parallax) {
            const speed = scrolled * 0.3;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    });

    // ===== PERFORMANCE OPTIMIZATION =====
    const heroSection = document.querySelector('section');
    const heroObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const molecules = entry.target.querySelectorAll('.staff-molecule');
            if (entry.isIntersecting) {
                molecules.forEach(molecule => {
                    molecule.style.animationPlayState = 'running';
                });
            } else {
                molecules.forEach(molecule => {
                    molecule.style.animationPlayState = 'paused';
                });
            }
        });
    });

    if (heroSection) {
        heroObserver.observe(heroSection);
    }
});
</script>
@endsection
