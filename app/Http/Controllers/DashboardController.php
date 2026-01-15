<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user && ($user->role === 'instruktur' || $user->role === 'instructor')) {
            return redirect()->route('instruktur.dashboard');
        }

        if ($user && ($user->role === 'student' || $user->role === 'peserta')) {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('login');
    }
}
