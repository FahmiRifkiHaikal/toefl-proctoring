<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TOEFL Exam - Online Proctoring</title>
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
            background-color: #f1f5f9;
        }

        h1,
        h2,
        h3 {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100">

    <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-900/10 text-blue-900 rounded-[12px] flex items-center justify-center text-lg">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <h1 class="text-sm font-bold text-blue-950">TOEFL Prediction Test</h1>
                <p class="text-[10px] text-amber-500 font-bold uppercase tracking-wider">ITN Malang Language Center</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                <span class="text-[10px] px-2 py-0.5 bg-emerald-100 text-emerald-700 font-bold rounded-full">Status:
                    Aktif</span>
            </div>
            <div
                class="w-9 h-9 bg-slate-200 rounded-full flex items-center justify-center text-slate-600 font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        @yield('content')
    </main>

</body>

</html>