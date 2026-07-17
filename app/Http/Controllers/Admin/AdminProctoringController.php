<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class AdminProctoringController extends Controller
{
    // 1. TAMPILAN PENGATURAN SESI (ADMIN)
    public function indexSessions()
    {
        $sessions = ExamSession::latest()->get();
        return view('admin.sessions', compact('sessions'));
    }

    // 2. SIMPAN SESI BARU & AKTIFKAN
    public function storeSession(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
        ]);

        // Nonaktifkan semua sesi lain terlebih dahulu agar hanya ada 1 sesi aktif saat ini
        ExamSession::query()->update(['is_active' => false]);

        // Buat sesi baru dan langsung aktifkan
        ExamSession::create([
            'session_name' => $request->session_name,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Sesi baru berhasil dibuat dan diaktifkan. Sesi lama otomatis diarsipkan!');
    }

    // 3. AKTIFKAN SESI TERTENTU SECARA MANUAL
    public function activateSession($id)
    {
        ExamSession::query()->update(['is_active' => false]);

        $session = ExamSession::findOrFail($id);
        $session->update(['is_active' => true]);

        return redirect()->back()->with('success', "Sesi '{$session->session_name}' sekarang aktif!");
    }

    // 4. TAMPILAN HISTORY KECURANGAN (ADMIN)
    public function history(Request $request)
    {
        $sessions = ExamSession::all();
        $selectedSessionId = $request->get('session_id');

        // Mengambil log kecurangan berdasarkan sesi yang dipilih di dropdown filter
        // Gunakan model log pelanggaranmu yang sebenarnya (misal: App\Models\ViolationLog)
        $query = \App\Models\ViolationLog::with(['user', 'examSession']);

        if ($selectedSessionId) {
            $query->where('exam_session_id', $selectedSessionId);
        }

        $violations = $query->latest()->paginate(15);

        return view('admin.history', compact('violations', 'sessions', 'selectedSessionId'));
    }
}
