{{-- resources/views/components/navbar.blade.php --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-transparent transition-all duration-300" id="navbar">
    <div class="mx-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo Section -->
            <div class="flex items-center">
                <div class="flex items-center space-x-3">
                    <!-- Logo dengan 2 versi -->
                    <div class="w-20 h-10 relative">
                        <!-- Logo putih (default) -->
                        <img src="{{ asset('images/logo-fisika-putih.png') }}"
                             alt="Logo Fisika"
                             class="w-full h-full object-contain transition-opacity duration-300"
                             id="logo-white" />
                        <!-- Logo hitam (ketika scroll) -->
                        <img src="{{ asset('images/logo-fisika.png') }}"
                             alt="Logo Fisika"
                             class="w-full h-full object-contain absolute top-0 left-0 opacity-0 transition-opacity duration-300"
                             id="logo-dark" />
                    </div>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}#visi-misi" class="nav-link text-white hover:text-yellow-400 font-medium transition-colors duration-200">Beranda</a>
                    <a href="{{ route('staff') }}" class="nav-link text-white hover:text-yellow-400 font-medium transition-colors duration-200">Staf dan Tenaga Ahli</a>
                    <a href="{{ route('home') }}#fasilitas" class="nav-link text-white hover:text-yellow-400 font-medium transition-colors duration-200">Fasilitas</a>
                    <a href="{{ route('home') }}#layanan" class="nav-link text-white hover:text-yellow-400 font-medium transition-colors duration-200">Layanan</a>
                    <a href="{{ route('home') }}#kontak" class="nav-link text-white hover:text-yellow-400 font-medium transition-colors duration-200">Kontak</a>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button"
                        class="mobile-menu-button text-white hover:text-yellow-400 focus:outline-none transition-colors duration-200"
                        aria-controls="mobile-menu"
                        aria-expanded="false">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 mobile-menu-bg bg-black/90 backdrop-blur-md">
            <a href="{{ route('home') }}#visi-misi" class="mobile-nav-link block px-3 py-2 text-white hover:text-yellow-400 font-medium">Beranda</a>
            <a href="{{ route('staff') }}" class="mobile-nav-link block px-3 py-2 text-white hover:text-yellow-400 font-medium">Staf dan Tenaga Ahli</a>
            <a href="{{ route('home') }}#fasilitas" class="mobile-nav-link block px-3 py-2 text-white hover:text-yellow-400 font-medium">Fasilitas</a>
            <a href="{{ route('home') }}#layanan" class="mobile-nav-link block px-3 py-2 text-white hover:text-yellow-400 font-medium">Layanan</a>
            <a href="{{ route('home') }}#kontak" class="mobile-nav-link block px-3 py-2 text-white hover:text-yellow-400 font-medium">Kontak</a>
        </div>
    </div>
</nav>

<style>
/* Navbar styling on scroll */
.navbar-scrolled {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

/* Navbar text colors when scrolled */
.navbar-scrolled .nav-link {
    color: #1f2937 !important; /* text-gray-800 */
}

.navbar-scrolled .nav-link:hover {
    color: #3b82f6 !important; /* text-blue-500 */
}

.navbar-scrolled .mobile-menu-button {
    color: #1f2937 !important;
}

.navbar-scrolled .mobile-menu-button:hover {
    color: #3b82f6 !important;
}

/* Mobile menu background when scrolled */
.navbar-scrolled .mobile-menu-bg {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px);
}

.navbar-scrolled .mobile-nav-link {
    color: #1f2937 !important;
}

.navbar-scrolled .mobile-nav-link:hover {
    color: #3b82f6 !important;
}

/* Active page indicator */
.nav-link.active {
    color: #fbbf24 !important; /* text-yellow-400 */
    font-weight: 600;
}

.navbar-scrolled .nav-link.active {
    color: #3b82f6 !important; /* text-blue-500 when scrolled */
}
</style>
