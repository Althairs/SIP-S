<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Ambil akun testing untuk auto-fill
        $testAccounts = $this->getTestAccounts();

        return view('components.auth.login', compact('testAccounts'));
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

    /**
     * Get test accounts for auto-fill.
     */
    protected function getTestAccounts(): array
    {
        // Ambil user dengan role tertentu untuk ditampilkan
        $users = User::where('is_active', true)
            ->where(function ($query) {
                $query->whereNotNull('email')
                    ->orWhereNotNull('nim')
                    ->orWhereNotNull('nip');
            })
            ->get()
            // Filter hanya user yang memiliki role tertentu
            ->filter(function ($user) {
                $roles = $user->getRoleNames()->toArray();
                $allowedRoles = [
                    'super_admin',
                    'kajur',
                    'sekjur',
                    'dosen',
                    'mahasiswa',
                    'panitia_verifikasi',
                    'panitia_penjadwalan',
                    'panitia_administrasi'
                ];
                return !empty(array_intersect($roles, $allowedRoles));
            })
            ->sortBy(function ($user) {
                // Urutkan berdasarkan role
                $order = [
                    'super_admin' => 1,
                    'kajur' => 2,
                    'sekjur' => 3,
                    'dosen' => 7,
                    'mahasiswa' => 8,
                    'panitia_verifikasi' => 4,
                    'panitia_penjadwalan' => 5,
                    'panitia_administrasi' => 6,
                ];
                $role = $user->getRoleNames()->first() ?? '';
                return $order[$role] ?? 999;
            })
            ->take(100);

        $colors = [
            '#7C3AED', // Super Admin - Purple
            '#059669', // Kajur - Emerald
            '#7C3AED', // Sekjur - Violet
            '#2563EB', // Dosen - Blue
            '#7C3AED', // Mahasiswa - Purple
            '#D97706', // Panitia Verifikasi - Amber
            '#0891B2', // Panitia Penjadwalan - Cyan
            '#DC2626', // Panitia Administrasi - Red
        ];

        $colorMap = [
            'super_admin' => '#7C3AED',
            'kajur' => '#059669',
            'sekjur' => '#7C3AED',
            'dosen' => '#2563EB',
            'mahasiswa' => '#7C3AED',
            'panitia_verifikasi' => '#D97706',
            'panitia_penjadwalan' => '#0891B2',
            'panitia_administrasi' => '#DC2626',
        ];

        return $users->map(function ($user) use ($colorMap) {
            $role = $user->getRoleNames()->first() ?? 'User';
            $roleLabel = $this->getRoleLabel($role);

            // Tentukan login identifier (prioritas: email, nim, nip)
            $login = $user->email;
            if (empty($login) && $user->nim) {
                $login = $user->nim;
            } elseif (empty($login) && $user->nip) {
                $login = $user->nip;
            }

            $color = $colorMap[$role] ?? '#6B7280';

            // Ambil inisial
            $nameParts = explode(' ', $user->name);
            $initials = '';
            if (count($nameParts) >= 2) {
                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
            } else {
                $initials = strtoupper(substr($user->name, 0, 2));
            }

            // Ambil 2 karakter pertama untuk avatar
            $avatarText = strtoupper(substr($user->name, 0, 2));

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nim' => $user->nim,
                'nip' => $user->nip,
                'login' => $login, // Untuk auto-fill
                'role' => $roleLabel,
                'role_raw' => $role,
                'color' => $color,
                'initials' => $initials,
                'avatar_text' => $avatarText,
            ];
        })->values()->toArray();
    }

    protected function getRoleLabel($role): string
    {
        $labels = [
            'super_admin' => 'Super Admin',
            'kajur' => 'Kajur',
            'sekjur' => 'Sekjur',
            'dosen' => 'Dosen',
            'mahasiswa' => 'Mahasiswa',
            'panitia_verifikasi' => 'Panitia Verifikasi',
            'panitia_penjadwalan' => 'Panitia Penjadwalan',
            'panitia_administrasi' => 'Panitia Administrasi',
        ];
        return $labels[$role] ?? ucfirst($role);
    }
}
