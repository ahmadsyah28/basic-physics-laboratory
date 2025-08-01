<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Panel Laboratorium Fisika Dasar</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            min-height: calc(100vh - 72px);
        }

        .nav-link {
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.3), rgba(168, 85, 247, 0.2));
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.4), rgba(168, 85, 247, 0.3));
            border-left: 4px solid #f59e0b;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-atom text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-xl font-semibold">Admin Panel</h1>
                    <p class="text-blue-100 text-sm">Laboratorium Fisika Dasar</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-blue-100">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition shadow-lg font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white w-64 sidebar sticky top-0 h-screen">
            <nav class="p-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.staff.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                            <i class="fas fa-users mr-3 w-5"></i>
                            <span>Kelola Staff</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.equipment.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                            <i class="fas fa-tools mr-3 w-5"></i>
                            <span>Kelola Alat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.peminjaman.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt mr-3 w-5"></i>
                            <span>Peminjaman Alat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.visits.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.visits.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check mr-3 w-5"></i>
                            <span>Kelola Kunjungan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.schedule.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-times mr-3 w-5"></i>
                            <span>Kelola Jadwal Available</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.articles.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                            <i class="fas fa-newspaper mr-3 w-5"></i>
                            <span>Kelola Artikel</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.visimisi.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.visimisi.*') ? 'active' : '' }}">
                            <i class="fas fa-bullseye mr-3 w-5"></i>
                            <span>Kelola Visi Misi</span>
                        </a>
                    </li>
                    @if(Auth::user()->role === 'super_admin')
                    <li class="pt-4 mt-4 border-t border-white/20">
                        <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield mr-3 w-5"></i>
                            <span>Kelola Admin</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500 text-emerald-800 p-4 mb-6 rounded-r-lg shadow-lg" role="alert">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-r-lg shadow-lg" role="alert">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                        </div>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
