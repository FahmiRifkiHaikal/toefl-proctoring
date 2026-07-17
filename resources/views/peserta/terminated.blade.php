<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Dibatalkan - SmartProctor ITN Malang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center font-sans antialiased p-4">

    <div class="bg-white p-10 rounded-[40px] shadow-xl w-full max-w-lg border border-slate-100 text-center space-y-6">

        <!-- Icon Peringatan Kritis -->
        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-red-50 rounded-[28px] text-red-600 text-4xl shadow-inner border border-red-100">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div class="space-y-2">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Sesi Ujian TOEFL Dihentikan</h2>
            <p class="text-xs font-semibold text-red-600 tracking-widest uppercase">System Violation Blocked</p>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-left text-sm text-slate-600 space-y-3">
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="font-medium">Total Batas Pelanggaran:</span>
                <span class="font-bold text-slate-800">5 / 5 Kali</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="font-medium">Status Akhir:</span>
                <span class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded-md">TERMINATED / GAGAL</span>
            </div>
            <p class="text-xs text-slate-400 pt-1 leading-relaxed">
                *Catatan: Sistem AI mendeteksi gerakan di luar ketentuan pengerjaan (seperti wajah tidak menghadap layar
                atau multi-user) secara berturut-turut.*
            </p>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-800 text-left flex gap-3">
            <i class="fa-solid fa-circle-info mt-0.5 text-sm flex-shrink-0"></i>
            <p class="leading-relaxed">
                <strong>Sesi Anda Telah Dikunci.</strong> Anda otomatis dikeluarkan dari sistem. Anda dapat mengikuti
                ujian kembali hanya jika Admin telah menjadwalkan dan mengaktifkan **Sesi Ujian Baru** untuk Anda.
            </p>
        </div>

        <!-- Form Logout Otomatis untuk Keluar Sistem -->
        <form action="{{ route('logout') }}" method="POST" class="pt-2">
            @csrf
            <button type="submit"
                class="w-full bg-blue-900 hover:bg-blue-950 text-white font-semibold py-4 rounded-[20px] shadow-lg shadow-blue-900/10 hover:shadow-xl hover:shadow-blue-900/20 transition text-sm flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i> Keluar dari Ujian
            </button>
        </form>
    </div>

</body>

</html>
