<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pembayaran Listrik - @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <style>
        .sidebar {
            width: 250px;
            transition: width 0.3s ease-in-out;
            overflow-x: hidden;
            
        }

        .sidebar.collapsed {
            width: 64px;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.expanded {
            margin-left: 64px;
        }

        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .sidebar .sidebar-text {
            opacity: 1;
            transition: opacity 0.2s;
        }
    </style>
</head>

<body class="bg-gray-100 flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside id="sidebar"
        {{-- class="sidebar bg-gray-800 text-white p-4 flex flex-col justify-between fixed h-full shadow-lg z-20 overflow-hidden">  --}}
        class="sidebar bg-gray-100 text-green-900 p-4 flex flex-col justify-between fixed h-full shadow-lg z-20 overflow-hidden">

        <div>
            <h1 class="text-2xl font-bold mb-6 text-center sidebar-text"> Listrik Pascabayar
            </h1>
            <nav>
                <ul>
                    {{-- Menu untuk Admin --}}
                    @if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 1)
                        <ul class="space-y-2 bg-gray-100 p-4 rounded-md shadow-sm">
                            {{-- Dashboard Admin --}}
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9.75L12 4l9 5.75M4.5 10.75V19a2 2 0 002 2h11a2 2 0 002-2v-8.25" />
                                    </svg>
                                    <span class="sidebar-text">Dashboard Admin</span>
                                </a>
                            </li>

                            {{-- Manajemen User --}}
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20v-2a4 4 0 00-3-3.87M9 20v-2a4 4 0 013-3.87M7 10a4 4 0 100-8 4 4 0 000 8zm10 0a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen User</span>
                                </a>
                            </li>

                            {{-- Manajemen Pelanggan --}}
                            <li>
                                <a href="{{ route('admin.pelanggans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A8.001 8.001 0 0112 16a8.001 8.001 0 016.879 1.804M12 12a5 5 0 100-10 5 5 0 000 10zm0 0v1" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Pelanggan</span>
                                </a>
                            </li>

                            {{-- Manajemen Tarif --}}
                            <li>
                                <a href="{{ route('admin.tarifs.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.104 0-2 .896-2 2s.896 2 2 2m0 0c1.104 0 2-.896 2-2s-.896-2-2-2zm0 0V4m0 12v4" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Tarif</span>
                                </a>
                            </li>

                            {{-- Manajemen Penggunaan --}}
                            <li>
                                <a href="{{ route('admin.penggunaans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4-10v10m4-6v6" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Penggunaan</span>
                                </a>
                            </li>

                            {{-- Manajemen Tagihan --}}
                            <li>
                                <a href="{{ route('admin.tagihans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-4a2 2 0 012-2h2a2 2 0 012 2v4M16 17V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Tagihan</span>
                                </a>
                            </li>

                            {{-- Manajemen Pembayaran --}}
                            <li>
                                <a href="{{ route('admin.pembayarans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="2" y="7" width="20" height="10" rx="2" stroke-width="2"
                                            stroke="currentColor" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 11h20" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Pembayaran</span>
                                </a>
                            </li>
                        </ul>
                    @endif


                    {{-- Menu untuk Petugas --}}
                    @if (Auth::guard('web')->check() && Auth::guard('web')->user()->level_id == 2)
                        <ul class="space-y-2 bg-gray-100 p-4 rounded-md shadow-sm">
                            {{-- Dashboard Petugas --}}
                            <li>
                                <a href="{{ route('petugas.dashboard') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9.75L12 4l9 5.75M4.5 10.75V19a2 2 0 002 2h11a2 2 0 002-2v-8.25" />
                                    </svg>
                                    <span class="sidebar-text">Dashboard Petugas</span>
                                </a>
                            </li>

                            {{-- Lihat Pelanggan --}}
                            <li>
                                <a href="{{ route('petugas.pelanggans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A8.001 8.001 0 0112 16a8.001 8.001 0 016.879 1.804M12 12a5 5 0 100-10 5 5 0 000 10zm0 0v1" />
                                    </svg>
                                    <span class="sidebar-text">Lihat Pelanggan</span>
                                </a>
                            </li>

                            {{-- Manajemen Tarif --}}
                            <li>
                                <a href="{{ route('petugas.tarifs.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.104 0-2 .896-2 2s.896 2 2 2m0 0c1.104 0 2-.896 2-2s-.896-2-2-2zm0 0V4m0 12v4" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Tarif</span>
                                </a>
                            </li>

                            {{-- Manajemen Penggunaan --}}
                            <li>
                                <a href="{{ route('petugas.penggunaans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4-10v10m4-6v6" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Penggunaan</span>
                                </a>
                            </li>

                            {{-- Manajemen Tagihan --}}
                            <li>
                                <a href="{{ route('petugas.tagihans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-4a2 2 0 012-2h2a2 2 0 012 2v4M16 17V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Tagihan</span>
                                </a>
                            </li>

                            {{-- Manajemen Pembayaran --}}
                            <li>
                                <a href="{{ route('petugas.pembayarans.index') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="2" y="7" width="20" height="10" rx="2" stroke-width="2"
                                            stroke="currentColor" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 11h20" />
                                    </svg>
                                    <span class="sidebar-text">Manajemen Pembayaran</span>
                                </a>
                            </li>
                        </ul>
                    @endif


                    {{-- Menu untuk Pelanggan --}}
                    @if (Auth::guard('pelanggan')->check())
                        <ul class="space-y-2 bg-gray-100 p-4 rounded-md shadow-sm">
                            <li>
                                <a href="{{ route('pelanggan.dashboard') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9.75L12 4l9 5.75M4.5 10.75V19a2 2 0 002 2h11a2 2 0 002-2v-8.25" />
                                    </svg>
                                    <span class="sidebar-text">Dashboard Pelanggan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pelanggan.riwayat_penggunaan') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4-10v10m4-6v6" />
                                    </svg>
                                    <span class="sidebar-text">Riwayat Penggunaan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pelanggan.tagihan_saya') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-4a2 2 0 012-2h2a2 2 0 012 2v4M16 17V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10" />
                                    </svg>
                                    <span class="sidebar-text">Tagihan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pelanggan.profil_saya') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="4" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 20a6 6 0 0112 0" />
                                    </svg>
                                    <span class="sidebar-text">Profil</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pelanggan.riwayat_pembayaran') }}"
                                    class="flex items-center px-4 py-2 rounded-md bg-white hover:bg-green-100 text-green-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="sidebar-text">Riwayat Pembayaran</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                </ul>
            </nav>
        </div>

        {{-- Logout button at the bottom of sidebar --}}
        <div class="mb-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    {{-- class="w-full flex items-center justify-center px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-semibold"> --}}
                    class="w-full flex items-center justify-center px-4 py-2 rounded-md bg-red-500 hover:bg-red-600 text-white font-semibold transition">   
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="sidebar-text whitespace-nowrap overflow-hidden">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div id="main-content" class="main-content flex-1 flex flex-col min-h-screen relative z-10">
        <header class="bg-white shadow p-4 flex justify-between items-center z-10">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="text-green-700 hover:text-green-900 focus:outline-none mr-4 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-xl font-semibold text-green-800">@yield('title', 'Dashboard')</h2>
            </div>
            <div class="text-green-800 font-medium">
                @if (Auth::guard('web')->check())
                    Halo, {{ Auth::guard('web')->user()->nama_user }}
                @elseif(Auth::guard('pelanggan')->check())
                    Halo, {{ Auth::guard('pelanggan')->user()->nama_pelanggan }}
                @endif
            </div>
        </header>

        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>

        <footer class="bg-green-700 text-white p-4 text-center">
            &copy; Website Pembayaran Listrik - Evan Yudi Pratama 
        </footer>
    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    </script>
</body>

</html>
