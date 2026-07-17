<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ExamSession;
use App\Models\ViolationLog;

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
        // Contoh data yang dibutuhkan oleh proctoring.js (di-passing dari database)
        // $user = Auth::user();
        // $userFaceVectorFromDatabase = $user->face_vector;
        // $loggedUsername = $user->name;

        return view('peserta.exam', [
            // 'userFaceVectorFromDatabase' => $userFaceVectorFromDatabase,
            // 'loggedUsername' => $loggedUsername
        ]);
    }

    /**
     * Menangani incoming log pelanggaran dari proctoring.js via AJAX
     */
    public function logViolation(Request $request)
    {
        $request->validate([
            'violation_type' => 'required|string',
            'euclidean_score' => 'required|numeric',
            'violation_image' => 'nullable|string', // Base64 Image
            'current_violation_count' => 'required|integer'
        ]);

        try {
            // 1. Simpan log pelanggaran ke database (contoh logikanya)
            // ViolationLog::create([
            //     'user_id' => Auth::id(),
            //     'type' => $request->violation_type,
            //     'score' => $request->euclidean_score,
            //     'image' => $request->violation_image, // Simpan path atau base64
            // ]);

            // 2. Jika hitungan pelanggaran di request mencapai batas maksimal, 
            //    kamu bisa mengubah status ujian user di DB menjadi 'FAILED' / 'TERMINATED'
            if ($request->current_violation_count >= 5) {
                // $userExamStatus = UserExam::where('user_id', Auth::id())->first();
                // $userExamStatus->update(['status' => 'terminated']);

                Log::warning("User " . Auth::id() . " telah menyentuh batas maksimal pelanggaran AI.");
            }

            return response()->json([
                'success' => true,
                'message' => 'Log pelanggaran ' . $request->violation_type . ' berhasil dicatat di server.'
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal mencatat log proctoring: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan log'], 500);
        }
    }

    /**
     * Menampilkan halaman ringkasan kegagalan ujian akibat proctoring
     */
    public function terminatedSummary()
    {
        // Pastikan view ini diletakkan di resources/views/peserta/terminated.blade.php
        return view('peserta.terminated');
    }

    public function checkExamAccess()
    {
        $user = Auth::user();

        // 1. Dapatkan sesi ujian yang saat ini sedang aktif diaktifkan oleh admin
        $activeSession = ExamSession::where('is_active', true)->first();

        if (!$activeSession) {
            return redirect()->route('login')->with('error', 'Tidak ada sesi ujian aktif saat ini. Hubungi admin!');
        }

        // 2. Cek apakah user ini sudah mengumpulkan 5 pelanggaran DI SESI AKTIF INI
        $violationCount = ViolationLog::where('user_id', $user->id)
            ->where('exam_session_id', $activeSession->id)
            ->count();

        if ($violationCount >= 5) {
            // Jika sudah mencapai batas di sesi ini, tolak masuk dan paksa logout dengan pesan peringatan
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Anda telah dinyatakan GAGAL pada sesi ujian ini. Silakan tunggu sesi baru dari Admin!');
        }

        // Jika aman, izinkan masuk ke halaman kalibrasi/ujian
        return view('peserta.calibration');
    }

    /**
     * Memproses reset status ujian agar peserta dapat mengulang kembali
     */
    public function retakeExam()
    {
        try {
            // Logika membersihkan sesi/token pengerjaan lama di database
            // Contoh: Hapus log pelanggaran sebelumnya atau ubah status kembali ke 'ready'
            // ViolationLog::where('user_id', Auth::id())->delete();
            // UserExam::where('user_id', Auth::id())->update(['status' => 'ready', 'score' => null]);

            // Setelah di-reset, alihkan peserta kembali ke halaman kalibrasi/ujian
            return redirect()->route('peserta.calibration')->with('success', 'Silakan lakukan kalibrasi ulang untuk memulai ujian baru.');
        } catch (\Exception $e) {
            Log::error("Gagal mereset sesi ujian: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mereset sesi pengerjaan.');
        }
    }
}
