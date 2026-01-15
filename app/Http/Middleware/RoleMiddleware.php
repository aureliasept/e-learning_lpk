<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role tidak sesuai, lempar ke dashboard masing-masing
        if ($user->role !== $role) {
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'instructor' => redirect()->route('instructor.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                default => redirect('/login'),
            };
        }

        return $next($request);
    }
}