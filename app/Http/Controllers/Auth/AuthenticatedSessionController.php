<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('components.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect berdasarkan role
        return redirect()->intended($this->redirectByRole());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Get redirect path based on user role.
     */
    protected function redirectByRole(): string
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            if ($user->hasRole('super_admin')) {
                return route('admin.dashboard');
            }
            if ($user->hasRole('kajur')) {
                return route('kajur.dashboard');
            }
            if ($user->hasRole('sekjur')) {
                return route('sekjur.dashboard');
            }
            if ($user->hasRole('panitia_verifikasi')) {
                return route('panitia.verifikasi.dashboard');
            }
            if ($user->hasRole('panitia_penjadwalan')) {
                return route('panitia.penjadwalan.dashboard');
            }
            if ($user->hasRole('panitia_administrasi')) {
                return route('panitia.administrasi.dashboard');
            }
            if ($user->hasRole('dosen')) {
                return route('dosen.dashboard');
            }
            if ($user->hasRole('mahasiswa')) {
                return route('mahasiswa.dashboard');
            }
        }

        return route('dashboard.index');
    }
}
