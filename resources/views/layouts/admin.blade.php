<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TOEFL Proctoring ITN Malang</title>

    <!-- Tailwind CSS (V4) & Vite Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @vite(['resources/js/app.js'])

    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f8fafc;
        }

        h1,
        h2,
        h3 {
            font-family: 'Poppins', sans-serif;
        }

        /* Mencegah scrollbar horizontal merusak sudut rounded pada wrapper utama */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>

<body class="flex min-h-screen overflow-hidden">

    <!-- Sidebar Menu Navigasi -->
    <aside
        class="w-64 bg-blue-950 text-white p-6 m-4 mr-0 rounded-[30px] flex flex-col justify-between shadow-xl shrink-0 hidden md:flex z-20">
        <div>
            <!-- Logo / Brand Title -->
            <div class="mb-10 text-center pt-4 border-b border-blue-900/60 pb-6">
                <h2 class="text-xl font-bold tracking-tight">Proctoring AI</h2>
                <p class="text-[10px] font-semibold text-amber-400 tracking-wider uppercase mt-1">ITN Malang</p>
            </div>

            <!-- List Menu Utama -->
            <!-- List Menu Utama -->
            <nav class="space-y-2">
                <!-- Menu 1: Dashboard Grafik -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-[15px] text-sm font-medium transition duration-200 {{ Request::routeIs('admin.dashboard') ? 'bg-blue-800 text-white shadow-md shadow-blue-950/20' : 'text-slate-400 hover:bg-blue-900/50 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    <span>Dashboard Grafik</span>
                </a>

                <!-- Menu 2: Live Monitoring Log (Sesi Aktif) -->
                <a href="{{ route('admin.monitoring') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-[15px] text-sm font-medium transition duration-200 {{ Request::routeIs('admin.monitoring') ? 'bg-blue-800 text-white shadow-md shadow-blue-950/20' : 'text-slate-400 hover:bg-blue-900/50 hover:text-white' }}">
                    <i class="fa-solid fa-desktop w-5"></i>
                    <span>Live Monitoring Log</span>
                </a>

                <!-- Menu 3: History Ujian (Halaman Baru) -->
                <a href="{{ route('admin.admin.history') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-[15px] text-sm font-medium transition duration-200 {{ Request::routeIs('admin.history') ? 'bg-blue-800 text-white shadow-md shadow-blue-950/20' : 'text-slate-400 hover:bg-blue-900/50 hover:text-white' }}">
                    <i class="fa-solid fa-history w-5"></i>
                    <span>History Kecurangan</span>
                </a>

                <!-- Menu 4: Pengaturan Sesi --- Halaman Baru -->
                <a href="{{ route('admin.admin.sessions') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-[15px] text-sm font-medium transition duration-200 {{ Request::routeIs('admin.sessions') ? 'bg-blue-800 text-white shadow-md shadow-blue-950/20' : 'text-slate-400 hover:bg-blue-900/50 hover:text-white' }}">
                    <i class="fa-solid fa-layer-group w-5"></i>
                    <span>Pengaturan Sesi</span>
                </a>
            </nav>
        </div>

        <!-- Tombol Aksi Keluar Sistem -->
        <form action="{{ route('logout') }}" method="POST" class="pt-6 border-t border-blue-900/60">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-4 py-3 text-red-400 hover:bg-red-950/40 rounded-[15px] text-sm font-medium transition duration-200 cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i>
                <span>Keluar Sistem</span>
            </button>
        </form>
    </aside>

    <!-- Area Konten Utama Aplikasi -->
    <main class="flex-1 p-4 md:p-8 overflow-y-auto custom-scrollbar flex flex-col min-w-0">

        <!-- Notifikasi Dinamis (Flash Alert Session) -->
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium animate-in fade-in slide-in-from-top-2 duration-200">
                <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-sm font-medium animate-in fade-in slide-in-from-top-2 duration-200">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-base"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Inject Render Blade Target Konten -->
        <div class="flex-1">
            @yield('content')
        </div>

    </main>

</body>

</html>
