<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;

        // Jika role sesuai, izinkan akses
        if ($userRole === $role) {
            return $next($request);
        }

        // Jika user adalah admin, beri akses juga ke halaman staff (opsional, tapi disarankan)
        if ($userRole === 'admin' && $role === 'staff') {
            return $next($request);
        }

        // Jika tidak berhak, kembalikan ke dashboard default dengan error
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}