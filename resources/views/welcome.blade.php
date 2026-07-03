<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOEFL Prediction Test with AI Proctoring</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <div
                        class="bg-indigo-600 text-white p-2 rounded-lg flex items-center justify-center shadow-md shadow-indigo-200">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <span
                        class="font-bold text-xl tracking-tight bg-gradient-to-r bg-clip-text text-transparent from-indigo-600 to-violet-600">
                        SmartProctor
                    </span>
                </div>

                <div>
                    <a href="/login"
                        class="inline-flex items-center justify-center px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-100 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="relative overflow-hidden pt-16 pb-20 lg:pt-24 lg:pb-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 mb-6 border border-indigo-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                        Sistem Berbasis Artificial Intelligence
                    </span>
                    <h1
                        class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl lg:text-5xl xl:text-6xl leading-tight">
                        Uji Kemampuan TOEFL Anda dengan <span
                            class="block text-indigo-600 bg-gradient-to-r bg-clip-text text-transparent from-indigo-600 to-violet-600">Pengawasan
                            AI Terpercaya</span>
                    </h1>
                    <p class="mt-4 text-base text-slate-500 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl leading-relaxed">
                        Platform TOEFL Prediction Test online yang aman, akurat, dan terintegrasi dengan teknologi
                        proctoring pintar berbasis *Convolutional Neural Network* untuk menjaga integritas ujian Anda.
                    </p>
                    <div class="mt-8 sm:max-w-lg sm:mx-auto sm:text-center lg:text-left lg:mx-0">
                        <a href="/login"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-2xl shadow-xl shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-1">
                            Mulai Tes Sekarang <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>

                <div
                    class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 flex justify-center">
                    <div class="relative w-full max-w-md lg:max-w-xl">
                        <div
                            class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob">
                        </div>
                        <div
                            class="absolute -bottom-10 -right-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-200">
                        </div>

                        <div
                            class="relative bg-white border border-slate-200 rounded-3xl shadow-2xl p-4 overflow-hidden transform hover:scale-[1.02] transition-transform duration-300">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 text-red-400"><i class="fa-solid fa-circle text-[10px]"></i></span>
                                    <span class="w-3 text-yellow-400"><i
                                            class="fa-solid fa-circle text-[10px]"></i></span>
                                    <span class="w-3 text-green-400"><i
                                            class="fa-solid fa-circle text-[10px]"></i></span>
                                </div>
                                <span class="text-xs font-medium text-slate-400"><i
                                        class="fa-solid fa-video mr-1 text-indigo-500"></i> AI Proctoring Active</span>
                            </div>
                            <div
                                class="bg-slate-900 rounded-2xl h-64 flex flex-col items-center justify-center text-white relative">
                                <i class="fa-solid fa-user-shield text-5xl text-indigo-400 mb-3 opacity-80"></i>
                                <p class="text-sm font-medium tracking-wide">Mendeteksi Posisi Wajah...</p>
                                <div
                                    class="absolute inset-x-6 bottom-6 flex justify-between text-[11px] font-mono text-indigo-300 bg-slate-800/80 backdrop-blur px-3 py-2 rounded-xl border border-slate-700/50">
                                    <span>SSD MobileNet: OK</span>
                                    <span>Landmark 68: Connected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-16 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Fitur Sistem Pengawasan Cerdas</h2>
                <p class="mt-4 text-lg text-slate-500">Bagaimana teknologi kecerdasan buatan kami bekerja menjaga
                    transparansi dan validitas skor tes Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-slate-50 p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-6 text-xl">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Deteksi Pergerakan Wajah</h3>
                    <p class="text-slate-500 leading-relaxed">Sistem memantau dan menghitung pergeseran posisi kepala
                        secara *real-time* untuk mendeteksi tindakan menoleh atau melirik mencurigakan.</p>
                </div>

                <div
                    class="bg-slate-50 p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="w-12 h-12 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center mb-6 text-xl">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Pengecekan Kehadiran</h3>
                    <p class="text-slate-500 leading-relaxed">Algoritma otomatis mendeteksi jika wajah peserta hilang
                        dari jangkauan kamera ataupun jika terdapat lebih dari satu orang di layar.</p>
                </div>

                <div
                    class="bg-slate-50 p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="w-12 h-12 rounded-xl bg-fuchsia-100 text-fuchsia-600 flex items-center justify-center mb-6 text-xl">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Log Pelanggaran Real-time</h3>
                    <p class="text-slate-500 leading-relaxed">Setiap anomali kecurangan yang terdeteksi akan dikonversi
                        menjadi bukti foto dasar string Base64 dan langsung dikirim aman ke database.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>&copy; 2026 SmartProctor System. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </footer>

</body>

</html>