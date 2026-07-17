<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationLog;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ViolationLogController extends Controller
{
    // =========================================================================
    // 1. TAMPILAN HISTORY KECURANGAN PER SESI (ADMIN)
    // =========================================================================
    public function index(Request $request)
    {
        $sessions = ExamSession::latest()->get();
        $selectedSessionId = $request->get('session_id');

        // Mengambil log kecurangan terfilter berdasarkan dropdown sesi yang dipilih oleh admin
        $query = ViolationLog::with(['user', 'examSession']);

        if ($selectedSessionId) {
            $query->where('exam_session_id', $selectedSessionId);
        } else {
            // Default: Tampilkan log dari sesi yang saat ini sedang aktif jika admin belum memilih filter
            $activeSession = ExamSession::where('is_active', true)->first();
            if ($activeSession) {
                $query->where('exam_session_id', $activeSession->id);
                $selectedSessionId = $activeSession->id;
            }
        }

        $logs = $query->latest()->paginate(10);

        return view('admin.monitoring', compact('logs', 'sessions', 'selectedSessionId'));
    }

    // =========================================================================
    // 2. MENYIMPAN DATA LOG KECURANGAN PESERTA UJIAN (AJAX DARI PROCTORING.JS)
    // =========================================================================
    public function store(Request $request)
    {
        $request->validate([
            'violation_type' => 'required|string',
            'euclidean_score' => 'required|numeric',
            'violation_image' => 'nullable|string' // Menerima data string Base64
        ]);

        // [LOGIKA REVISI]: Cari sesi ujian yang sedang berstatus aktif saat ini
        $activeSession = ExamSession::where('is_active', true)->first();

        // Jika tidak ada sesi yang diaktifkan oleh admin, kunci jalannya perekaman log
        if (!$activeSession) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencatat log. Tidak ada sesi ujian yang sedang aktif saat ini!'
            ], 403);
        }

        $imageName = null;

        // Cek jika ada lampiran string Base64 gambar bukti dari client
        if ($request->filled('violation_image')) {
            $base64Image = $request->violation_image;

            // Memisahkan metadata Base64 dengan data string citra murni
            @list($type, $fileData) = explode(';', $base64Image);
            @list(, $fileData) = explode(',', $fileData);

            if ($fileData) {
                // Generate nama file unik menggunakan UUID atau String Acak + Timestamp
                $imageName = 'bukti_' . Str::random(10) . '_' . time() . '.jpg';

                // Decode string base64 menjadi file biner gambar asli dan simpan ke folder /storage/app/public/violations/
                Storage::disk('public')->put('violations/' . $imageName, base64_decode($fileData));
            }
        }

        // Insert data record pelanggaran baru ke dalam tabel MySQL
        ViolationLog::create([
            'user_id' => Auth::id(), // Mengikat ID peserta yang login
            'exam_session_id' => $activeSession->id, // [LOGIKA REVISI]: Mengikat log ke ID sesi aktif
            'violation_type' => $request->violation_type,
            'euclidean_score' => $request->euclidean_score,
            'violation_image' => $imageName, // Menyimpan nama file gambar ke database
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Log kecurangan sesi "' . $activeSession->session_name . '" berhasil dicatat beserta bukti foto.'
        ], 200);
    }

    // =========================================================================
    // 3. LOGIKA UNTUK FITUR PENGATURAN SESI BARU (ADMIN)
    // =========================================================================
    public function storeSession(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
        ]);

        // Amankan konsistensi data dengan Transaction DB
        DB::transaction(function () use ($request) {
            // Nonaktifkan semua sesi lama terlebih dahulu (is_active = false)
            ExamSession::query()->update(['is_active' => false]);

            // Buat sesi baru dan langsung set menjadi aktif (is_active = true)
            ExamSession::create([
                'session_name' => $request->session_name,
                'is_active' => true
            ]);
        });

        return redirect()->back()->with('success', 'Sesi baru berhasil dibuat dan diaktifkan. Sesi selanjutnya dijamin "clear" dari log sesi lama!');
    }

    // =========================================================================
    // 4. RESET TOTAL BILA MEMANG DIPERLUKAN (OPSIONAL)
    // =========================================================================
    public function clearAll()
    {
        // Tetap mempertahankan method truncate bawaanmu jika admin ingin membersihkan seluruh isi tabel total
        DB::table('violation_logs')->truncate();
        return redirect()->back()->with('success', 'Seluruh data riwayat kecurangan dari semua sesi berhasil dibersihkan.');
    }
}
