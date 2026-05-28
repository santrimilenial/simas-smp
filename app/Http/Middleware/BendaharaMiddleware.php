<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BendaharaMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'bendahara') {
            return $this->redirectToRoleDashboard(auth()->user()->role);
        }

        return $next($request);
    }

    private function redirectToRoleDashboard(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'staff' => redirect()->route('staff.scan.index'),
            default => redirect()->route('login'),
        };
    }
}
