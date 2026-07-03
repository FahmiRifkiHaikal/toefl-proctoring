<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Memproses data form login (Logika Utama)
    public function login(Request $request)
    {
        // 1. Validasi input dari form
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Percobaan autentikasi (Mencocokkan email & password di database)
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk menghindari serangan Session Fixation
            $request->session()->regenerate();

            // 3. Logika Pengecekan Role Pengguna untuk Pengalihan Halaman
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'peserta') {
                return redirect()->route('exam.calibration');
            }
        }

        // 4. Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses proses keluar sistem (Logout)
    public function logout(Request $request)
    {
        Auth::logout();

        // Hancurkan session yang digunakan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
