<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total pelanggaran berdasarkan jenisnya untuk kebutuhan diagram
        $stats = [
            'menoleh' => ViolationLog::where('violation_type', 'Menoleh')->count(),
            'melirik' => ViolationLog::where('violation_type', 'Melirik')->count(),
            'wajah_hilang' => ViolationLog::where('violation_type', 'Wajah Hilang')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
