<?php

/**
 * ==============================================================================
 * Tujuan: Middleware otorisasi akses berbasis role (admin atau guru).
 * Dipakai Oleh: Route Group di routes/web.php
 * Dependensi: Illuminate\Support\Facades\Auth, Closure, Request, Response
 * Daftar Fungsi: handle($request, Closure $next, ...$roles)
 * Side Effect: Redirect atau abort HTTP 403 jika role tidak sesuai
 * ==============================================================================
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
