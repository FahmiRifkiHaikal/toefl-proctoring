@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Pengaturan Sesi Ujian</h1>
                <p class="text-sm text-slate-500">Kelola dan aktifkan sesi ujian TOEFL aktif untuk perekaman log proctoring
                    AI.</p>
            </div>

            <button onclick="bukaModalSesi()"
                class="flex items-center gap-2 text-xs px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-[12px] transition duration-150 shadow-sm shadow-blue-600/10 cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Sesi Baru</span>
            </button>
        </div>

        <!-- Sesi Aktif Standout Info -->
        @php
            $activeSession = $sessions->where('is_active', true)->first();
        @endphp
        <div
            class="p-5 {{ $activeSession ? 'bg-emerald-50/60 border-emerald-200 text-emerald-900' : 'bg-amber-50/60 border-amber-200 text-amber-900' }} border rounded-[24px] flex items-center gap-4 shadow-sm animate-in fade-in duration-200">
            <div
                class="w-12 h-12 rounded-2xl {{ $activeSession ? 'bg-emerald-500' : 'bg-amber-500' }} flex items-center justify-center text-white text-xl shrink-0">
                <i class="fa-solid {{ $activeSession ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
            </div>
            <div>
                <span class="text-xs uppercase font-bold tracking-wider opacity-75">Sesi Monitor Saat Ini</span>
                <h3 class="font-bold text-base mt-0.5">
                    {{ $activeSession ? $activeSession->session_name : 'Tidak Ada Sesi yang Sedang Aktif!' }}
                </h3>
            </div>
        </div>

        <!-- Tabel Data Sesi -->
        <div class="bg-white rounded-[24px] border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-5 w-16 text-center">No</th>
                            <th class="p-5">Nama Sesi Ujian</th>
                            <th class="p-5 w-44">Status</th>
                            <th class="p-5 w-52">Dibuat Pada</th>
                            <th class="p-5 w-40 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($sessions as $index => $session)
                            <tr class="hover:bg-slate-50/80 transition duration-150">
                                <td class="p-5 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                                <td class="p-5 font-bold text-slate-900">{{ $session->session_name }}</td>
                                <td class="p-5">
                                    @if ($session->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-lg border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Sedang Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-600 font-semibold text-xs rounded-lg border border-slate-200">
                                            Diarsipkan
                                        </span>
                                    @endif
                                </td>
                                <td class="p-5 text-slate-500 text-xs">
                                    {{ $session->created_at->format('d M Y - H:i:s') }} WIB
                                </td>
                                <td class="p-5 text-center">
                                    @if (!$session->is_active)
                                        <form action="{{ route('admin.sessions.activate', $session->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-xs px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold border border-blue-200 rounded-lg transition cursor-pointer">
                                                <i class="fa-solid fa-power-off mr-1"></i> Aktifkan
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="text-xs font-bold text-emerald-600 flex items-center justify-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i> Monitor Ready
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-3xl mb-3 block text-slate-300"></i>
                                    Belum ada sesi ujian yang dibuat. Silakan tambahkan sesi baru terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP: TAMBAH SESI BARU -->
    <div id="modalTambahSesi"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        onclick="tutupModalSesi()">
        <div class="bg-white rounded-[24px] overflow-hidden max-w-md w-full shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150"
            onclick="event.stopPropagation()">

            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-folder-plus text-slate-500"></i> Buat Sesi Ujian Baru
                </h3>
                <button onclick="tutupModalSesi()"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.admin.sessions.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label for="session_name"
                        class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nama Sesi Ujian</label>
                    <input type="text" name="session_name" id="session_name" required autocomplete="off"
                        placeholder="Contoh: TOEFL Batch 1 - Ruang 04"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition duration-150">
                    <p class="text-[11px] text-amber-600 mt-2 font-medium">
                        <i class="fa-solid fa-circle-info"></i> Membuat sesi baru otomatis menonaktifkan sesi yang berjalan
                        sekarang.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="tutupModalSesi()"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm shadow-blue-600/10 cursor-pointer">
                        Simpan & Aktifkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT MODAL CONTROL -->
    <script>
        function bukaModalSesi() {
            document.getElementById('modalTambahSesi').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('session_name').focus();
            }, 50);
        }

        function tutupModalSesi() {
            document.getElementById('modalTambahSesi').classList.add('hidden');
            document.getElementById('session_name').value = '';
        }
    </script>
@endsection
