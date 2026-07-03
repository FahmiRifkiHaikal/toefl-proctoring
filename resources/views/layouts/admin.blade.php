<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TOEFL Proctoring ITN Malang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @vite(['resources/js/app.js'])
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
    </style>
</head>

<body class="flex min-h-screen">

    <aside class="w-64 bg-blue-950 text-white p-6 m-4 mr-0 rounded-[30px] flex flex-col justify-between shadow-xl">
        <div>
            <div class="mb-10 text-center pt-4 border-b border-blue-900 pb-6">
                <h2 class="text-xl font-bold tracking-tight">Proctoring AI</h2>
                <p class="text-[10px] font-semibold text-amber-400 tracking-wider uppercase mt-1">ITN Malang</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-[15px] text-sm font-medium transition duration-200 {{ Request::routeIs('admin.dashboard') ? 'bg-blue-800 text-white' : 'text-slate-400 hover:bg-blue-900/50 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    <span>Dashboard Grafik</span>
                </a>
                <a href="{{ route('admin.monitoring') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-[15px] text-sm font-medium transition duration-200 {{ Request::routeIs('admin.monitoring') ? 'bg-blue-800 text-white' : 'text-slate-400 hover:bg-blue-900/50 hover:text-white' }}">
                    <i class="fa-solid fa-desktop w-5"></i>
                    <span>Live Monitoring Log</span>
                </a>
            </nav>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="pt-6 border-t border-blue-900">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-4 py-3 text-red-400 hover:bg-red-950/40 rounded-[15px] text-sm font-medium transition duration-200 cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i>
                <span>Keluar Sistem</span>
            </button>
        </form>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        @yield('content')
    </main>

</body>

</html>