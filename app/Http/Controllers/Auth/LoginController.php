<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * 1. TAMPILKAN FORM LOGIN
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 2. PROSES LOGIN
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // CEK ROLE & ARAHKAN KE DASHBOARD YANG BENAR
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'instructor') { // Pastikan di database tulisannya 'instructor' (bukan instruktur)
                return redirect()->route('instructor.dashboard');
            } elseif ($role === 'student') {
                return redirect()->route('student.dashboard');
            }

            // Jika role tidak dikenali
            return redirect('/');
        }

        // Jika Login Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * 3. PROSES LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}