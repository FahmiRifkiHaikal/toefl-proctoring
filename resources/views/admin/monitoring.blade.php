@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Live Monitoring Logs</h1>
                <p class="text-sm text-slate-500">Data log riwayat indikasi kecurangan real-time yang tersimpan beserta
                    bukti foto.</p>
            </div>

            <div class="flex items-center gap-4">
                <span
                    class="flex items-center gap-2 text-xs px-3 py-1.5 bg-emerald-50 text-emerald-600 font-bold border border-emerald-200 rounded-full animate-pulse">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Terhubung ke Server
                </span>

                <form action="{{ route('admin.monitoring.clear') }}" method="POST"
                    onsubmit="return confirm('PERINGATAN SISWANDI/DOSEN: Apakah Anda yakin ingin menghapus SELURUH data log kecurangan dari database? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 text-xs px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold border border-rose-200 rounded-[12px] transition duration-150 cursor-pointer">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Bersihkan Semua Log</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-[24px] border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-5">Nama Peserta</th>
                            <th class="p-5">Jenis Pelanggaran</th>
                            <th class="p-5">Skor Jarak (Euclidean)</th>
                            <th class="p-5">Bukti Foto</th> {{-- Kolom Baru --}}
                            <th class="p-5">Waktu Kejadian</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/80 transition duration-150">
                                <td class="p-5 font-bold text-slate-900">{{ $log->user->name }}</td>
                                <td class="p-5">
                                    @if($log->violation_type === 'Menoleh')
                                        <span
                                            class="px-2.5 py-1 bg-amber-50 text-amber-700 font-bold text-xs rounded-lg border border-amber-200/60"><i
                                                class="fa-solid fa-user-clock mr-1.5"></i>Menoleh</span>
                                    @elseif($log->violation_type === 'Melirik')
                                        <span
                                            class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-lg border border-indigo-200/60"><i
                                                class="fa-solid fa-eye mr-1.5"></i>Melirik</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-rose-50 text-rose-700 font-bold text-xs rounded-lg border border-rose-200/60"><i
                                                class="fa-solid fa-user-slash mr-1.5"></i>Wajah Hilang</span>
                                    @endif
                                </td>
                                <td class="p-5 font-mono text-xs font-semibold text-slate-600">
                                    {{ $log->euclidean_score ? number_format($log->euclidean_score, 2) : '-' }}
                                </td>

                                {{-- LOGIKA BARU: MENAMPILKAN THUMBNAIL FOTO CAPTURE --}}
                                <td class="p-5">
                                    @if($log->violation_image && Storage::disk('public')->exists('violations/' . $log->violation_image))
                                        <div class="relative w-12 h-9 overflow-hidden rounded-lg border border-slate-200 bg-slate-100 group shadow-sm cursor-pointer"
                                            onclick="bukaModalBukti('{{ asset('storage/violations/' . $log->violation_image) }}')">
                                            <img src="{{ asset('storage/violations/' . $log->violation_image) }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition duration-150"
                                                alt="Bukti Mini">
                                            <div
                                                class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition duration-150 text-white text-[10px]">
                                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Tidak ada foto</span>
                                    @endif
                                </td>

                                <td class="p-5 text-slate-500 text-xs">{{ $log->created_at->format('d M Y - H:i:s') }} WIB</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-slate-400"> {{-- Ubah colspan menjadi 5 --}}
                                    <i class="fa-solid fa-inbox text-3xl mb-3 block text-slate-300"></i>
                                    Belum ada rekaman log pelanggaran kecurangan yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL POPUP PREVIEW FOTO (Ringan & Tanpa Tambahan Library/Bootstrap) --}}
    <div id="modalBuktiFoto"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        onclick="tutupModalBukti()">
        <div class="bg-white rounded-[24px] overflow-hidden max-w-xl w-full shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150"
            onclick="event.stopPropagation()">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-camera text-slate-500"></i> Bukti Capture Kamera AI
                </h3>
                <button onclick="tutupModalBukti()"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-2 bg-slate-950 flex items-center justify-center aspect-video">
                <img id="imgModalTarget" src="" class="max-w-full max-h-[70vh] object-contain rounded-lg"
                    alt="Bukti Pelanggaran Full">
            </div>
        </div>
    </div>

    <script>
        function bukaModalBukti(urlGambar) {
            document.getElementById('imgModalTarget').src = urlGambar;
            document.getElementById('modalBuktiFoto').classList.remove('hidden');
        }

        function tutupModalBukti() {
            document.getElementById('modalBuktiFoto').classList.add('hidden');
            setTimeout(() => {
                document.getElementById('imgModalTarget').src = '';
            }, 150);
        }
    </script>
@endsection