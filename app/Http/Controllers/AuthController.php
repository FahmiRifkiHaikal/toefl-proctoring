<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.registrasi');
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

    // Memproses Registrasi Akun & Simpan Vektor Wajah
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'face_vector' => 'required|string', // String JSON hasil JSON.stringify() dari JavaScript
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'peserta', // Default role untuk pendaftar ujian
                'face_vector' => $request->face_vector, // Menyimpan array 128 dimensi
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi akun dan perekaman wajah berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data pendaftaran: ' . $e->getMessage()
            ], 500);
        }
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
