<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi login telah berakhir. Silakan login kembali.'
                ], 401);
            }
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke halaman ini.'
                ], 403);
            }
            return $this->redirectToRoleDashboard(auth()->user()->role);
        }

        return $next($request);
    }

    private function redirectToRoleDashboard(string $role)
    {
        return match ($role) {
            'guru' => redirect()->route('guru.dashboard'),
            'staff' => redirect()->route('staff.scan.index'),
            'bendahara' => redirect()->route('bendahara.dashboard'),
            default => redirect()->route('login'),
        };
    }
}