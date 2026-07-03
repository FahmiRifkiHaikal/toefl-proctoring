@extends('layouts.peserta')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6 my-4">
        <div class="bg-white p-8 rounded-[30px] border border-slate-200/80 shadow-md text-center space-y-6">
            <div>
                <span
                    class="text-[10px] px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 font-bold uppercase tracking-wider rounded-full">Tahap
                    Persiapan</span>
                <h2 class="text-xl font-bold text-blue-950 mt-3">Kalibrasi Deteksi Kamera AI</h2>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-1">Posisikan wajah tegak lurus menghadap kamera depan
                    untuk menetapkan posisi ideal awal Anda selama tes.</p>
            </div>

            <form action="{{ route('logout') }}" method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 text-xs px-4 py-2.5 bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-bold border border-slate-200 hover:border-rose-200 rounded-[14px] transition duration-150 cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>

            <div
                class="w-full h-80 bg-slate-900 rounded-[20px] shadow-inner overflow-hidden relative border-4 border-white flex items-center justify-center group">
                <div
                    class="absolute inset-0 border-2 border-dashed border-blue-500/40 rounded-[16px] m-4 pointer-events-none group-hover:border-blue-500 transition duration-300">
                </div>
                <video id="webcam" autoplay muted playsinline class="w-full h-full object-cover scale-x-[-1]"></video>

                <div id="cam-placeholder"
                    class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 bg-slate-950">
                    <i class="fa-solid fa-video-slash text-3xl mb-2"></i>
                    <p class="text-xs">Menginisialisasi akses webcam...</p>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('exam.index') }}"
                    class="inline-flex items-center gap-3 bg-blue-900 hover:bg-blue-950 text-white font-semibold px-8 py-3.5 rounded-[18px] text-sm shadow-md transition-all duration-200 cursor-pointer">
                    <span>Kunci Posisi Ideal & Mulai Ujian</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Script Sederhana untuk menyalakan Webcam Pengguna
        const video = document.getElementById('webcam');
        const placeholder = document.getElementById('cam-placeholder');

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                    placeholder.classList.add('hidden');
                })
                .catch(function (error) {
                    console.error("Akses kamera ditolak atau tidak ditemukan:", error);
                    placeholder.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-rose-500 text-3xl mb-2"></i><p class="text-xs text-rose-400 font-semibold">Gagal mengakses kamera depan!</p>`;
                });
        }
    </script>
@endsection