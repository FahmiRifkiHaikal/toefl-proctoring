@extends('layouts.admin') {{-- Sesuaikan dengan layout admin bawaan proyekmu --}}

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">History Kecurangan Peserta</h1>

            <!-- Filter Dropdown Berdasarkan Sesi -->
            <form action="{{ route('admin.admin.history') }}" method="GET" class="flex gap-2">
                <select name="session_id" onchange="this.form.submit()"
                    class="bg-white border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Sesi Ujian --</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}" {{ $selectedSessionId == $session->id ? 'selected' : '' }}>
                            {{ $session->session_name }} {{ $session->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-left text-xs uppercase font-semibold">
                        <th class="px-5 py-3 border-b">Nama Peserta</th>
                        <th class="px-5 py-3 border-b">Sesi Ujian</th>
                        <th class="px-5 py-3 border-b">Jenis Pelanggaran</th>
                        <th class="px-5 py-3 border-b">Skor Euclidean</th>
                        <th class="px-5 py-3 border-b">Waktu Kejadian</th>
                        <th class="px-5 py-3 border-b">Bukti Snapshot</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($violations as $log)
                        <tr>
                            <td class="px-5 py-5 border-b">{{ $log->user->name }}</td>
                            <td class="px-5 py-5 border-b">
                                <span
                                    class="font-medium text-blue-600">{{ $log->examSession->session_name ?? 'Tanpa Sesi' }}</span>
                            </td>
                            <td class="px-5 py-5 border-b">
                                <span
                                    class="px-2 py-1 rounded text-xs font-semibold {{ $log->violation_type == 'Wajah Berbeda (Calo)' ? 'bg-purple-100 text-purple-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $log->violation_type }}
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b">{{ number_format($log->euclidean_score, 2) }} px</td>
                            <td class="px-5 py-5 border-b">{{ $log->created_at->format('d M Y, H:i:s') }} WIB</td>
                            <td class="px-5 py-5 border-b">
                                @if($log->violation_image)
                                    <button onclick="bukaModalBukti('{{ $log->violation_image }}')"
                                        class="text-blue-500 hover:text-blue-700 font-semibold underline">
                                        Lihat Bukti Foto
                                    </button>
                                @else
                                    <span class="text-gray-400">Tidak ada foto</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada riwayat rekaman pelanggaran di sesi
                                ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $violations->links() }}
        </div>
    </div>

    <!-- Modal Pop-up Bukti Foto -->
    <div id="modal-bukti" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white p-4 rounded-lg max-w-lg w-full text-center">
            <h3 class="text-lg font-bold mb-3 text-gray-800">Snapshot Bukti Kecurangan</h3>
            <img id="img-bukti" src="" class="w-full h-auto border rounded max-h-96 object-contain mb-4">
            <button onclick="tutupModalBukti()"
                class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded-lg">Tutup</button>
        </div>
    </div>

    <script>
        function bukaModalBukti(base64Src) {
            document.getElementById('img-bukti').src = base64Src;
            document.getElementById('modal-bukti').classList.remove('hidden');
        }
        function tutupModalBukti() {
            document.getElementById('modal-bukti').classList.add('hidden');
        }
    </script>
@endsection