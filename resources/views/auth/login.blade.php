<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TOEFL Proctoring System ITN Malang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }

        h1,
        h2 {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen relative overflow-hidden">

    <div class="absolute -top-40 -right-40 w-96 height-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none">
    </div>
    <div class="absolute -bottom-20 -left-20 w-80 height-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none">
    </div>

    <div
        class="bg-white p-10 rounded-[40px] shadow-xl w-full max-w-md border border-slate-100 z-10 transition-all duration-300 hover:shadow-2xl mx-4">

        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-blue-900/10 rounded-[22px] text-blue-900 text-2xl mb-4">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h2 class="text-2xl font-bold text-blue-950 tracking-tight">TOEFL Proctoring</h2>
            <p class="text-xs font-semibold text-amber-500 tracking-widest uppercase mt-1">Institut Teknologi Nasional
                Malang</p>
        </div>

        @if ($errors->any())
            <div
                class="bg-red-50 text-red-600 p-4 rounded-[20px] mb-6 text-sm flex items-start gap-3 border border-red-100 animate-pulse">
                <i class="fa-solid fa-circle-exclamation mt-1 flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 pl-2">Alamat
                    Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Contoh: user@peserta.com"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 rounded-[20px] text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-900 focus:bg-white transition-all duration-200">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 pl-2">Kata
                    Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 rounded-[20px] text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-900 focus:bg-white transition-all duration-200">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-900 hover:bg-blue-950 text-white font-semibold py-3.5 rounded-[20px] shadow-lg shadow-blue-900/20 hover:shadow-xl hover:shadow-blue-900/30 transition-all duration-200 flex items-center justify-center gap-2 mt-4 cursor-pointer text-sm">
                <span>Masuk Ke Dashboard</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <!-- ========================================================================= -->
        <!-- BUTTON NAVIGASI KE HALAMAN REGISTRASI (TAMBAHAN) -->
        <!-- ========================================================================= -->
        <div class="mt-6 text-center text-sm">
            <p class="text-slate-500 text-xs">Belum memiliki akun peserta / wajah terdaftar?</p>
            <a href="/register"
                class="inline-flex items-center gap-1.5 mt-2 text-xs font-bold text-blue-900 hover:text-blue-950 transition-colors border-b border-blue-900/35 hover:border-blue-950 pb-0.5">
                <i class="fa-solid fa-user-plus text-[10px]"></i> Daftar Akun Baru Disini
            </a>
        </div>
        <!-- ========================================================================= -->

        <div class="text-center mt-6 pt-5 border-t border-slate-100">
            <p class="text-[11px] text-slate-400 font-medium">Sistem Pengawasan Ujian Berbasis AI &copy; 2026</p>
        </div>
    </div>

</body>

</html>