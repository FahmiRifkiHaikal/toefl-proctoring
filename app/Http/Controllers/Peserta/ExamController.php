<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // Mengarahkan ke halaman kalibrasi kamera awal sebelum ujian
    public function calibration()
    {
        return view('peserta.calibration');
    }

    // Mengarahkan ke halaman lembar soal ujian TOEFL
    public function index()
    {
        return view('peserta.exam');
    }
}
