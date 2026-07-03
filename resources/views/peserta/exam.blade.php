@extends('layouts.peserta')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 my-4 relative">

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[30px] border border-slate-200/80 shadow-md space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                        Section 2: Structure and Written Expression
                    </span>
                    <h2 class="text-lg font-bold text-slate-800 mt-1">Pertanyaan Nomor 1 dari 40</h2>
                </div>

                <div
                    class="text-slate-800 font-medium text-base leading-relaxed bg-slate-50 p-5 rounded-[20px] border border-slate-100">
                    "The North American treaty organization, ________ in 1949, is an international alliance of 30 countries
                    from North America and Europe."
                </div>

                <div class="space-y-3">
                    <label
                        class="flex items-center gap-4 p-4 border border-slate-200 rounded-[18px] cursor-pointer hover:bg-slate-50/60 transition-all duration-150">
                        <input type="radio" name="answer" class="w-4 h-4 text-blue-900 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-slate-700">A. which established</span>
                    </label>
                    <label
                        class="flex items-center gap-4 p-4 border border-slate-200 rounded-[18px] cursor-pointer hover:bg-slate-50/60 transition-all duration-150">
                        <input type="radio" name="answer" class="w-4 h-4 text-blue-900 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-slate-700">B. was established</span>
                    </label>
                    <label
                        class="flex items-center gap-4 p-4 border border-slate-200 rounded-[18px] cursor-pointer hover:bg-slate-50/60 transition-all duration-150">
                        <input type="radio" name="answer" class="w-4 h-4 text-blue-900 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-slate-700">C. established</span>
                    </label>
                    <label
                        class="flex items-center gap-4 p-4 border border-slate-200 rounded-[18px] cursor-pointer hover:bg-slate-50/60 transition-all duration-150">
                        <input type="radio" name="answer" class="w-4 h-4 text-blue-900 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-slate-700">D. establishing</span>
                    </label>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                    <div class="flex gap-2">
                        <button
                            class="px-6 py-2.5 border border-slate-200 text-slate-500 font-semibold rounded-xl text-sm hover:bg-slate-50 transition cursor-pointer"
                            disabled>
                            Sebelumnya
                        </button>
                        <button
                            class="px-6 py-2.5 bg-blue-900 hover:bg-blue-950 text-white font-semibold rounded-xl text-sm shadow transition cursor-pointer">
                            Selanjutnya
                        </button>
                    </div>

                    <button id="btn-selesai-ujian"
                        class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Selesaikan Ujian</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[24px] border border-slate-200/80 shadow-md text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sisa Waktu Pengerjaan</p>
                <h3 class="text-3xl font-mono font-bold text-blue-950 mt-2">00:44:59</h3>
            </div>

            <div class="bg-white p-6 rounded-[24px] border border-slate-200/80 shadow-md space-y-4">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-600 rounded-full animate-ping"></span> AI Proctoring Aktif
                </h4>

                <div class="w-full h-40 bg-slate-900 rounded-xl overflow-hidden relative border border-slate-200">
                    <video id="proctor-cam" autoplay muted playsinline
                        class="w-full h-full object-cover scale-x-[-1]"></video>
                    <div
                        class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-xs text-[10px] text-white px-2 py-0.5 rounded-md font-mono">
                        FPS: 30.00
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                    Sistem kecerdasan buatan mengawasi pergerakan titik koordinat wajah Anda secara lokal.
                </p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/face-api.min.js') }}"></script>
    <script src="{{ asset('js/proctoring.js') }}"></script>

    <script>
        const videoExam = document.getElementById('proctor-cam');
        let mediaStreamActive = null;

        // 1. Jalankan Hardware Webcam Secara Bersih
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    videoExam.srcObject = stream;
                    mediaStreamActive = stream;
                })
                .catch(function (err) {
                    console.error("Gagal memuat proctoring camera: ", err);
                });
        }

        // 2. Eksekusi Tombol Selesaikan Ujian
        document.getElementById('btn-selesai-ujian').addEventListener('click', function () {
            if (confirm('Apakah Anda yakin ingin mengakhiri ujian TOEFL ini? Kamera pengawas AI akan dimatikan otomatis.')) {

                // Matikan pengawasan looping AI di file proctoring.js
                if (typeof modelSudahSiap !== 'undefined') {
                    modelSudahSiap = false;
                }

                // Putus aliran hardware kamera agar lampu indikator laptop mati
                if (mediaStreamActive) {
                    mediaStreamActive.getTracks().forEach(track => track.stop());
                    console.log("Hardware webcam berhasil dinonaktifkan.");
                }

                alert('Ujian selesai! Mengalihkan halaman...');
                window.location.href = "{{ route('exam.calibration') }}";
            }
        });
    </script>
@endsection