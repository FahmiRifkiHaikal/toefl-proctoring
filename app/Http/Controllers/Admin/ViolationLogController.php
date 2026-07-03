<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ViolationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ViolationLogController extends Controller
{
    // Menampilkan halaman tabel monitoring real-time
    public function index()
    {
        // Ambil log terbaru beserta data user (peserta) terkait
        $logs = ViolationLog::with('user')->latest()->paginate(10);
        return view('admin.monitoring', compact('logs'));
    }

    // Mengamankan data log kecurangan kiriman AJAX dari proctoring.js peserta
    public function store(Request $request)
    {
        $request->validate([
            'violation_type' => 'required|string',
            'euclidean_score' => 'required|numeric',
            'violation_image' => 'nullable|string' // Menerima data string Base64
        ]);

        $imageName = null;

        // Cek jika ada lampiran string Base64 gambar bukti dari client
        if ($request->filled('violation_image')) {
            $base64Image = $request->violation_image;

            // Memisahkan metadata Base64 dengan data string citra murni
            // Contoh: "data:image/jpeg;base64,/9j/4AAQSkZ..." dipecah
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
            'violation_type' => $request->violation_type,
            'euclidean_score' => $request->euclidean_score,
            'violation_image' => $imageName, // Menyimpan nama file gambar ke database
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Log kecurangan berhasil dicatat beserta bukti foto.'
        ], 200);
    }

    public function clearAll()
    {
        // Menghapus semua isi tabel tanpa menghapus struktur tabelnya
        DB::table('violation_logs')->truncate();

        return redirect()->back()->with('success', 'Semua data log kecurangan berhasil dibersihkan.');
    }
}
