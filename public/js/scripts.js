document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('navbar');
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const logoWhite = document.getElementById('logo-white');
    const logoDark = document.getElementById('logo-dark');

    // Navbar scroll effect dengan pergantian logo dan warna
    function updateNavbar() {
        if (window.scrollY > 50) {
            // Ketika di-scroll
            navbar.classList.add('navbar-scrolled');

            // Ganti logo
            if (logoWhite && logoDark) {
                logoWhite.style.opacity = '0';
                logoDark.style.opacity = '1';
            }
        } else {
            // Ketika di top
            navbar.classList.remove('navbar-scrolled');

            // Kembalikan logo putih
            if (logoWhite && logoDark) {
                logoWhite.style.opacity = '1';
                logoDark.style.opacity = '0';
            }
        }
    }

    // Event listener untuk scroll
    window.addEventListener('scroll', updateNavbar);

    // Panggil sekali saat load
    updateNavbar();

    // Mobile menu toggle
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const navbarHeight = navbar.offsetHeight;
                const targetPosition = target.offsetTop - navbarHeight;

                // Tutup mobile menu jika terbuka
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                }

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Tutup mobile menu ketika klik di luar
    document.addEventListener('click', function(e) {
        if (mobileMenu && !mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.classList.add('hidden');
        }
    });

    // Tutup mobile menu ketika resize ke desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && mobileMenu) { // md breakpoint
            mobileMenu.classList.add('hidden');
        }
    });
});
