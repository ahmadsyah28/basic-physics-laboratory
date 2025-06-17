<footer class="bg-physics-dark text-white">
    <div class=" mx-6 px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="lg:col-span-1">
                <div class="flex items-center space-x-2 mb-6">
                    <img src="{{ asset('images/logo-fisika-putih.png') }}"
                             alt="Logo Fisika"
                             class="w-24 object-contain transition-opacity duration-300"
                             id="logo-white" />
                    <h5 class="font-poppins text-xl font-bold">Lab Fisika Dasar</h5>
                </div>
                <p class="font-jakarta text-gray-300 mb-6 leading-relaxed">
                    Laboratorium Fisika Dasar merupakan fasilitas unggulan untuk pengembangan penelitian dan pendidikan fisika dengan teknologi terdepan.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center text-white hover:bg-physics-blue transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center text-white hover:bg-physics-blue transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center text-white hover:bg-physics-blue transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center text-white hover:bg-physics-blue transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Menu Links -->
            <div>
                <h6 class="font-poppins text-lg font-semibold mb-6">Menu</h6>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Beranda</a></li>
                    <li><a href="{{ route('about') }}" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Tentang</a></li>
                    <li><a href="{{ route('equipment') }}" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Alat Lab</a></li>
                    <li><a href="{{ route('services') }}" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Layanan</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h6 class="font-poppins text-lg font-semibold mb-6">Layanan</h6>
                <ul class="space-y-3">
                    <li><a href="#" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Praktikum Fisika</a></li>
                    <li><a href="#" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Penelitian</a></li>
                    <li><a href="#" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Kalibrasi Alat</a></li>
                    <li><a href="#" class="font-jakarta text-gray-300 hover:text-white transition-colors duration-200">Konsultasi</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h6 class="font-poppins text-lg font-semibold mb-6">Kontak</h6>
                <ul class="space-y-4">
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-physics-blue mt-1"></i>
                        <span class="font-jakarta text-gray-300">Jl. Universitas No. 123, Indonesia</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-phone text-physics-blue"></i>
                        <span class="font-jakarta text-gray-300">(021) 123-4567</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-physics-blue"></i>
                        <span class="font-jakarta text-gray-300">info@labfisika.ac.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="font-jakarta text-gray-400 mb-4 md:mb-0">
                &copy; {{ date('Y') }} Laboratorium Fisika Dasar. All rights reserved.
            </p>
            <div class="flex space-x-6">
                <a href="#" class="font-jakarta text-gray-400 hover:text-white transition-colors duration-200">Privacy Policy</a>
                <a href="#" class="font-jakarta text-gray-400 hover:text-white transition-colors duration-200">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
