# 🔬 Website Laboratorium Fisika Dasar

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)

Website modern dan responsif untuk Laboratorium Fisika Dasar Departemen Fisika FMIPA Universitas Syiah Kuala, dibangun dengan Laravel dan dilengkapi sistem manajemen laboratorium yang komprehensif.

## 📋 Deskripsi Proyek

Proyek ini bertujuan untuk mengembangkan lima website berbasis WordPress yang terdiri dari empat website untuk laboratorium di Departemen Fisika FMIPA Universitas Syiah Kuala serta satu website untuk profil departemen. Website ini menyediakan:

- **Landing Page Publik**: Informasi laboratorium, staff, fasilitas, dan layanan
- **Sistem Manajemen**: Dashboard admin untuk pengelolaan inventaris, peminjaman, kunjungan, dan pengujian
- **Formulir Online**: Peminjaman alat, pengajuan kunjungan, dan pengujian sampel
- **Interface Modern**: Design responsif dengan animasi smooth dan user experience yang optimal

## ✨ Fitur Utama

### 🌐 Landing Page (Akses Publik)
- [x] **Hero Section** dengan typewriter effect dan animasi dinamis
- [x] **About Section** dengan visi, misi, dan informasi laboratorium
- [x] **Staff Section** dengan filtering dan profil lengkap tim
- [x] **Equipment Section** dengan daftar peralatan modern
- [x] **Responsive Design** untuk semua device
- [x] **Smooth Animations** dengan Intersection Observer API
- [ ] **Gallery Section** (dalam pengembangan)
- [ ] **Services Section** (dalam pengembangan)
- [ ] **Contact Section** (dalam pengembangan)

### 🔧 Sistem Manajemen (Admin Dashboard)
- [ ] **Manajemen Inventaris Alat**
  - Database alat laboratorium lengkap
  - Tracking status dan kondisi alat
  - Riwayat maintenance dan kalibrasi
  
- [ ] **Sistem Peminjaman/Penyewaan**
  - Formulir pengajuan online
  - Approval workflow
  - Monitoring status peminjaman
  
- [ ] **Manajemen Kunjungan Lab**
  - Booking sistem kunjungan
  - Penjadwalan dan konfirmasi
  - Laporan kunjungan
  
- [ ] **Sistem Pengujian Sampel**
  - Pengajuan testing online
  - Tracking progress pengujian
  - Hasil dan sertifikat digital

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 10.x** - PHP Framework
- **MySQL 8.0+** - Database
- **PHP 8.1+** - Server-side language

### Frontend
- **Tailwind CSS 3.x** - Utility-first CSS framework
- **JavaScript ES6+** - Interaktivitas dan animasi
- **Font Awesome 6.x** - Icon library
- **Google Fonts** - Typography (Poppins & Plus Jakarta Sans)

### Tools & Deployment
- **Composer** - PHP dependency manager
- **NPM/Yarn** - Frontend package manager
- **Git** - Version control
- **CPanel/Hosting Universitas** - Deployment platform

## 📁 Struktur Proyek

```
laboratorium-fisika-dasar/
├── 📂 app/
│   ├── 📂 Http/Controllers/
│   │   ├── HomeController.php
│   │   ├── EquipmentController.php (future)
│   │   ├── RentalController.php (future)
│   │   └── StaffController.php (future)
│   ├── 📂 Models/
│   │   ├── Equipment.php (future)
│   │   ├── Rental.php (future)
│   │   └── Staff.php (future)
│   └── 📂 Services/ (future)
├── 📂 database/
│   ├── 📂 migrations/
│   ├── 📂 seeders/
│   └── 📂 factories/
├── 📂 public/
│   ├── 📂 css/
│   │   └── style.css
│   ├── 📂 js/
│   │   └── scripts.js
│   ├── 📂 images/
│   │   ├── 📂 staff/
│   │   ├── 📂 equipment/
│   │   └── 📂 gallery/
│   └── 📂 assets/
├── 📂 resources/
│   ├── 📂 views/
│   │   ├── 📂 layouts/
│   │   │   └── app.blade.php
│   │   ├── 📂 components/
│   │   │   ├── navbar.blade.php
│   │   │   ├── hero.blade.php
│   │   │   ├── about.blade.php
│   │   │   ├── staff.blade.php
│   │   │   ├── equipment.blade.php
│   │   │   └── footer.blade.php
│   │   ├── home.blade.php
│   │   └── staff.blade.php (optional)
│   └── 📂 css/ (if using Laravel Mix)
├── 📂 routes/
│   └── web.php
├── .env.example
├── composer.json
├── package.json
└── README.md
```

## 🚀 Instalasi dan Setup

### Prasyarat
- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 8.0+
- Node.js & NPM (untuk asset compilation)
- Git

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/your-username/laboratorium-fisika-dasar.git
   cd laboratorium-fisika-dasar
   ```

2. **Install Dependencies**
   ```bash
   # Install PHP dependencies
   composer install
   
   # Install NPM dependencies (jika menggunakan Laravel Mix)
   npm install
   ```

3. **Environment Setup**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Generate application key
   php artisan key:generate
   ```

4. **Database Configuration**
   ```bash
   # Edit .env file
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lab_fisika_dasar
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Migration** (ketika tersedia)
   ```bash
   # Run migrations
   php artisan migrate
   
   # Seed data (optional)
   php artisan db:seed
   ```

6. **Asset Compilation** (jika menggunakan Laravel Mix)
   ```bash
   # Development
   npm run dev
   
   # Production
   npm run production
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

   Website akan tersedia di `http://localhost:8000`

## ⚙️ Konfigurasi

### Upload Gambar
```bash
# Buat direktori untuk gambar staff
mkdir public/images/staff

# Buat direktori untuk gambar equipment
mkdir public/images/equipment

# Buat direktori untuk gallery
mkdir public/images/gallery
```

### Kustomisasi Data Staff
Edit file `app/Http/Controllers/HomeController.php`:

```php
$staff = [
    [
        'name' => 'Dr. Nama Lengkap',
        'position' => 'Jabatan',
        'category' => 'lecturer', // lecturer, technician, researcher
        'specialization' => 'Bidang Keahlian',
        'experience' => 'Pengalaman',
        'expertise' => ['Skill 1', 'Skill 2'],
        'email' => 'email@domain.com',
        'phone' => '+62-xxx-xxxxxx',
        'photo' => 'nama-file.jpg', // letakkan di public/images/staff/
        'color' => 'blue', // theme color
        'badge_color' => 'yellow',
        'badge_icon' => 'star',
        'social_link' => 'https://linkedin.com/in/username',
        'social_icon' => 'linkedin-in'
    ]
];
```

### Kustomisasi Warna Theme
Tersedia color scheme: `blue`, `purple`, `green`, `orange`, `indigo`, `teal`, `emerald`, `red`, `pink`, `cyan`

## 🎨 Kustomisasi Design

### Menambah Section Baru
1. Buat file blade component di `resources/views/components/`
2. Tambahkan styling di `public/css/style.css`
3. Tambahkan JavaScript di `public/js/scripts.js`
4. Include di `resources/views/home.blade.php`

### Mengubah Animasi
Edit intersection observer settings di `public/js/scripts.js`:

```javascript
const observer = new IntersectionObserver((entries) => {
    // Kustomisasi animasi
}, {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
});
```

## 📱 Responsive Design

Website ini dioptimalkan untuk:
- **Mobile**: 320px - 768px
- **Tablet**: 768px - 1024px  
- **Desktop**: 1024px+
- **Large Desktop**: 1280px+

## 🔍 Testing

```bash
# Run PHP tests (ketika tersedia)
php artisan test

# Check code style
./vendor/bin/phpcs

# Fix code style
./vendor/bin/phpcbf
```

## 🚢 Deployment

### Persiapan Production
1. **Environment Production**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Optimize untuk Production**
   ```bash
   # Cache configuration
   php artisan config:cache
   
   # Cache routes
   php artisan route:cache
   
   # Cache views
   php artisan view:cache
   
   # Optimize autoloader
   composer install --optimize-autoloader --no-dev
   ```

3. **Upload ke Server**
   - Upload semua file kecuali folder `node_modules`
   - Set permission untuk folder `storage` dan `bootstrap/cache`
   - Update `.env` dengan konfigurasi production

## 👥 Tim Pengembang

| Nama | Program Studi | NPM | Tanggung Jawab |
|------|---------------|-----|----------------|
| **Glenn Hakim** | S1 Informatika | 2208107010072 | Web Laboratorium Geofisika |
| **Ahmad Syah Ramadhan** | S1 Informatika | 2208107010033 | Web Laboratorium Fisika Dasar |
| **Rafli Afriza Nugraha** | S1 Informatika | 2208107010028 | Web Laboratorium Elektronika |
| **Muhammad Bintang Indra Hidayat** | S1 Informatika | 2208107010023 | Web Laboratorium Fisika Lanjut |
| **Willy Jonathan Arsyad** | S1 Informatika | 2208107010037 | Web Profil Jurusan Fisika |

## 🎯 Roadmap Pengembangan

### Phase 1: Landing Page ✅
- [x] Hero Section dengan animasi
- [x] About Section (Visi & Misi)
- [x] Staff Section dengan filtering
- [x] Equipment Section
- [ ] Gallery Section
- [ ] Services Section
- [ ] Contact Section

### Phase 2: Admin Dashboard 🚧
- [ ] Authentication sistem
- [ ] Dashboard overview
- [ ] Equipment management
- [ ] Staff management
- [ ] User management

### Phase 3: Booking System 📝
- [ ] Equipment rental system
- [ ] Lab visit booking
- [ ] Testing request system
- [ ] Notification system

### Phase 4: Advanced Features 🔮
- [ ] Payment integration
- [ ] Reporting system
- [ ] API development
- [ ] Mobile app companion

## 🐛 Issue Tracking

Gunakan GitHub Issues untuk melaporkan bug atau request fitur:
- **Bug Report**: Template untuk melaporkan masalah
- **Feature Request**: Template untuk request fitur baru
- **Enhancement**: Perbaikan fitur yang ada

## 📄 License

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 📞 Kontak

- **Email**: info@labfisika.unsyiah.ac.id
- **Phone**: +62-651-123456
- **Address**: Jl. Universitas No. 123, Banda Aceh, Indonesia
- **Website**: [https://labfisika.unsyiah.ac.id](https://labfisika.unsyiah.ac.id)

## 🙏 Acknowledgments

- **Departemen Fisika FMIPA Universitas Syiah Kuala**
- **Tim Pengembang Laravel Indonesia**
- **Tailwind CSS Community**
- **Font Awesome Team**

---

**⭐ Jangan lupa memberikan star jika project ini membantu!**

> Proyek ini dikembangkan sebagai bagian dari tugas akhir mahasiswa S1 Informatika untuk mendukung digitalisasi laboratorium akademik.
