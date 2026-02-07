<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan import ini
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Ganti auth()->check() dengan Auth::check()
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Ganti auth()->user() dengan Auth::user()
        // Pastikan role user sesuai
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}