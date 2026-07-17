<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun & Wajah - TOEFL Proctoring System ITN Malang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS & Fonts -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lato:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Memuat Library Face API & Script Proctoring Utama -->
    <script defer src="{{ asset('js/face-api.min.js') }}"></script>
    <script defer src="{{ asset('js/proctoring.js') }}"></script>

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        h1,
        h2,
        h3 {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-x-hidden py-12 px-4">

    <!-- Dekorasi Nordik / Background Abstract -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Main Container Card -->
    <div
        class="bg-white p-8 md:p-10 rounded-[32px] shadow-xl shadow-slate-200/50 w-full max-w-md border border-slate-100/80 z-10 transition-all duration-300 mx-auto">

        <!-- Header Title -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-14 h-14 bg-blue-50 text-blue-900 rounded-2xl text-xl mb-3 border border-blue-100">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Akun Baru</h2>
            <p class="text-[11px] font-bold text-amber-500 tracking-wider uppercase mt-1">Registrasi & Face Enrollment
            </p>
        </div>

        <!-- Form Pendaftaran -->
        <form id="form-registrasi" action="/register" method="POST" class="space-y-5">
            @csrf

            <!-- Input: Nama Lengkap -->
            <div class="space-y-1.5">
                <label for="reg-name" class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">Nama
                    Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-regular fa-user text-sm"></i>
                    </span>
                    <input type="text" id="reg-name" name="name" placeholder="Nama lengkap Anda" required
                        class="w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 transition-all focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10 focus:outline-none">
                </div>
            </div>

            <!-- Input: Email -->
            <div class="space-y-1.5">
                <label for="reg-email"
                    class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-regular fa-envelope text-sm"></i>
                    </span>
                    <input type="email" id="reg-email" name="email" placeholder="Contoh: nama@domain.com" required
                        class="w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 transition-all focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10 focus:outline-none">
                </div>
            </div>

            <!-- Input: Password -->
            <div class="space-y-1.5">
                <label for="reg-password"
                    class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" id="reg-password" name="password" placeholder="••••••••" required
                        class="w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 transition-all focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10 focus:outline-none">
                </div>
            </div>

            <!-- Preview Stream Kamera Utama & Deteksi Vektor -->
            <div class="pt-2 flex flex-col items-center justify-center">
                <div
                    class="relative w-full aspect-video max-w-[280px] bg-slate-950 rounded-2xl overflow-hidden shadow-md group border border-slate-200/80">
                    <video id="webcam-register" autoplay muted playsinline
                        class="w-full h-full object-cover transform -scale-x-100"></video>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 to-transparent pointer-events-none">
                    </div>
                </div>

                <!-- Status Perekaman / Sinkronisasi Model AI -->
                <p id="status-perekaman"
                    class="text-xs font-semibold mt-3 text-blue-600 bg-blue-50 px-4 py-1.5 rounded-full border border-blue-100/60 inline-flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-circle-notch animate-spin text-[10px]"></i> Menunggu model AI siap...
                </p>
            </div>

            <!-- Tombol Submit Utama Controlled via JS -->
            <div class="pt-2">
                <button type="button" id="btn-capture-face" disabled
                    class="w-full bg-slate-200 text-slate-400 text-sm font-bold py-3.5 rounded-xl transition-all cursor-not-allowed shadow-sm flex items-center justify-center gap-2 border border-transparent enabled:bg-blue-900 enabled:text-white enabled:hover:bg-blue-950 enabled:cursor-pointer enabled:shadow-blue-900/10 enabled:hover:shadow-md">
                    Daftar & Ambil Data Wajah <i class="fa-solid fa-camera text-xs"></i>
                </button>
            </div>
        </form>

        <!-- Footer Navigation Link -->
        <div class="mt-8 text-center space-y-2">
            <p class="text-slate-400 text-xs">Sudah memiliki akun terdaftar?</p>
            <a href="/login"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-900 hover:text-blue-950 transition-colors border-b border-dashed border-blue-900/30 pb-0.5">
                <i class="fa-solid fa-right-to-bracket text-[10px]"></i> Masuk Menggunakan Akun Anda
            </a>
        </div>

        <!-- Copyright Info -->
        <div class="text-center mt-8 pt-5 border-t border-slate-100">
            <p class="text-[10px] text-slate-400 font-medium tracking-wide">Sistem Pengawasan Ujian Berbasis AI &copy;
                2026</p>
        </div>
    </div>

</body>

</html>