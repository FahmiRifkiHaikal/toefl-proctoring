@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Analitik</h1>
            <p class="text-sm text-slate-500">Rangkuman data akumulasi pelanggaran proctoring AI.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[24px] border border-slate-200/60 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggaran Menoleh</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $stats['menoleh'] }} <span
                            class="text-xs text-slate-400 font-normal">Kali</span></h3>
                </div>
                <div
                    class="w-12 h-12 bg-amber-50 text-amber-600 rounded-[18px] flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-face-frown-open"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[24px] border border-slate-200/60 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggaran Melirik</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $stats['melirik'] }} <span
                            class="text-xs text-slate-400 font-normal">Kali</span></h3>
                </div>
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-[18px] flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[24px] border border-slate-200/60 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Wajah Tidak Terdeteksi</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $stats['wajah_hilang'] }} <span
                            class="text-xs text-slate-400 font-normal">Kali</span></h3>
                </div>
                <div
                    class="w-12 h-12 bg-rose-50 text-rose-600 rounded-[18px] flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[24px] border border-slate-200/60 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-6">Rasio Perbandingan Kecurangan</h3>

            @php
                $total = array_sum($stats);
                $p_menoleh = $total > 0 ? ($stats['menoleh'] / $total) * 100 : 0;
                $p_melirik = $total > 0 ? ($stats['melirik'] / $total) * 100 : 0;
                $p_hilang = $total > 0 ? ($stats['wajah_hilang'] / $total) * 100 : 0;
            @endphp

            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span class="text-slate-600">Menoleh Ke Samping</span>
                        <span class="font-bold text-slate-800">{{ number_format($p_menoleh, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-3 rounded-full">
                        <div class="bg-amber-500 h-3 rounded-full" style="width: {{ $p_menoleh }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span class="text-slate-600">Melirik/Mata Bergeser</span>
                        <span class="font-bold text-slate-800">{{ number_format($p_melirik, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-3 rounded-full">
                        <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $p_melirik }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-medium mb-1">
                        <span class="text-slate-600">Meninggalkan Kamera</span>
                        <span class="font-bold text-slate-800">{{ number_format($p_hilang, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-3 rounded-full">
                        <div class="bg-rose-500 h-3 rounded-full" style="width: {{ $p_hilang }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection