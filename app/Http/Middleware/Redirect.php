<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Redirect
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /** @var User $user */
                $user = Auth::guard($guard)->user();

                if ($user->hasRole('super_admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('kajur')) {
                    return redirect()->route('kajur.dashboard');
                } elseif ($user->hasRole('sekjur')) {
                    return redirect()->route('sekjur.dashboard');
                } elseif ($user->hasRole('mahasiswa')) {
                    return redirect()->route('mahasiswa.dashboard');
                } elseif ($user->hasRole('panitia_verifikasi')) {
                    return redirect()->route('panitia.verifikasi.dashboard');
                } elseif ($user->hasRole('panitia_penjadwalan')) {
                    return redirect()->route('panitia.penjadwalan.dashboard');
                } elseif ($user->hasRole('panitia_administrasi')) {
                    return redirect()->route('panitia.administrasi.dashboard');
                }

                return redirect()->route('dashboard.index');
            }
        }

        return $next($request);
    }
}
