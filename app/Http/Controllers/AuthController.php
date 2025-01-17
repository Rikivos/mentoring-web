<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'nim' => 'required|nim',
            'password' => 'required',
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt($request->only('nim', 'password'))) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Ambil role pengguna setelah login
            $role = Auth::user()->role;

            // Simpan role dalam session
            session(['role' => $role]);

            if ($role === 'mente') {
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }
            if ($role === 'mentor') {
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            } else {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
            }
        }

        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'nim' => 'NIM atau password salah.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Menghapus role dari session setelah logout
        session()->forget('role');

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
