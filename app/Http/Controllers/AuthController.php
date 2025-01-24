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
        $request->validate([
            'nim' => 'required|nim',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('nim', 'password'))) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            session(['role' => $role]);

            if ($role === 'petugas') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
            } else {
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }
        }

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

        session()->forget('role');

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
