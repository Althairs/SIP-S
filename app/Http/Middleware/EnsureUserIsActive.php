<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ! $user->is_active) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        }

        return $next($request);
    }
}
