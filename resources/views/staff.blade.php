{{-- resources/views/staff.blade.php --}}
@extends('layouts.app')

@section('title', 'Staff dan Tenaga Ahli - Laboratorium Fisika Dasar')

@section('content')
<!-- Hero Section untuk Staff Page dengan Background Image -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.jpg') }}"
             alt="Hero Background"
             class="w-full h-full object-cover transform scale-105 transition-transform duration-[20s] ease-in-out hover:scale-110">
        <!-- Overlay untuk memastikan text tetap terbaca -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#968c82]/80 via-[#635849]/70 to-[#443f35]/80"></div>
    </div>

    <!-- Content -->
    <div class="relative z-20 mx-6 px-4 sm:px-6 lg:px-8 text-center max-w-4xl">
        <!-- Breadcrumb -->
        <div class="staff-hero-animate mb-8 opacity-0" data-animation="fade-down">
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-amber-200 hover:text-white transition-colors duration-200">
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
                 data-search="{{ strtolower($member['name'] . ' ' . implode(' ', $member['expertise']) . ' ' . $member['specialization']) }}">

                <!-- Background Glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-{{ $member['color'] }}-500/5 via-transparent to-{{ $member['color'] }}-700/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="relative z-10">
                    <!-- Header dengan Photo dan Basic Info -->
                    <div class="flex items-start space-x-6 mb-6">
                        <!-- Staff Photo -->
                        <div class="relative flex-shrink-0">
                            <div class="w-24 h-24 bg-gradient-to-br from-{{ $member['color'] }}-200 to-{{ $member['color'] }}-300 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-500 overflow-hidden">
                                @if($member['photo'])
                                    <img src="{{ asset('images/staff/' . $member['photo']) }}"
                                         alt="{{ $member['name'] }}"
                                         class="w-full h-full object-cover rounded-2xl">
                                @else
                                    <i class="fas fa-user text-{{ $member['color'] }}-600 text-2xl"></i>
                                @endif
                            </div>
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

                        <!-- Expertise Tags -->
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Keahlian:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($member['expertise'] as $skill)
                                <span class="px-3 py-1 bg-{{ $member['color'] }}-100 text-{{ $member['color'] }}-700 text-xs rounded-full font-medium">{{ $skill }}</span>
                                @endforeach
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

                        <!-- Publications Preview -->
                        @if(count($member['publications']) > 0)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Publikasi Terbaru:</p>
                            <div class="text-sm text-gray-600">
                                <p class="italic">"{{ $member['publications'][0] }}"</p>
                                @if(count($member['publications']) > 1)
                                <p class="text-xs text-gray-500 mt-1">+{{ count($member['publications']) - 1 }} publikasi lainnya</p>
                                @endif
                            </div>
                        </div>
                        @endif

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
/* Staff page specific styles */
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

/* Enhanced staff card styles */
.staff-card {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.staff-card:hover {
    box-shadow: 0 25px 50px -12px rgba(150, 140, 130, 0.15);
}

/* Filter button styles */
.staff-filter-btn.active {
    background: linear-gradient(135deg, #968c82 0%, #635849 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(150, 140, 130, 0.3);
}

/* Search functionality */
#staff-search:focus {
    transform: scale(1.02);
}

/* Staff animations */
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

/* Card filtering */
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .staff-card {
        margin-bottom: 1.5rem;
    }

    #staff-search {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero animations
    const heroElements = document.querySelectorAll('.staff-hero-animate');

    // Trigger hero animations on load
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
    const staffSection = document.querySelector('section.py-24');

    if (staffSection) {
        let staffSectionTop = staffSection.offsetTop - 200;

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

        // Staff filtering and search
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

                // Update button styles
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

        // Update section position on resize
        window.addEventListener('resize', () => {
            staffSectionTop = staffSection.offsetTop - 200;
        });
    }
});
</script>
@endsection
