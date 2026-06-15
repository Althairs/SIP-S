<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PublicController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\JurusanIndex;
use App\Livewire\Admin\JurusanCreate;
use App\Livewire\Admin\JurusanEdit;
use App\Livewire\Admin\ProdiIndex;
use App\Livewire\Admin\ProdiCreate;
use App\Livewire\Admin\ProdiEdit;
use App\Livewire\Admin\KajurSekjurIndex;
use App\Livewire\Admin\UserIndex;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\RoleIndex;
use App\Livewire\Admin\RoleCreate;
use App\Livewire\Admin\RoleEdit;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\Settings;
// Kajur Components
use App\Livewire\Kajur\Dashboard as KajurDashboard;
use App\Livewire\Kajur\DosenIndex;
use App\Livewire\Kajur\MahasiswaIndex;
use App\Livewire\Kajur\PanitiaIndex;
use App\Livewire\Kajur\KuotaDosen;
use App\Livewire\Kajur\BidangKeahlians;
use App\Livewire\Kajur\Kepakaran;
use App\Livewire\Kajur\VerifikasiSeminarProposal;
use App\Livewire\Kajur\VerifikasiSeminarHasil;
use App\Livewire\Kajur\VerifikasiSidangSkripsi;
use App\Livewire\Kajur\AturAtributDosen;
use App\Livewire\Kajur\PengaturanReminder;
// Sekjur Components
use App\Livewire\Sekjur\Dashboard as SekjurDashboard;
use App\Livewire\Sekjur\PengujiIndex;
use App\Livewire\Sekjur\GeneratePenguji as GeneratePengujiSekjur;

// Mahasiswa components
use App\Livewire\Mahasiswa\Dashboard as MahasiswaDashboard;
use App\Livewire\Mahasiswa\PendaftaranIndex;
use App\Livewire\Mahasiswa\PendaftaranCreate;
use App\Livewire\Mahasiswa\JadwalUjian;
use App\Livewire\Mahasiswa\Nilai;
use App\Livewire\Mahasiswa\Profile as MahasiswaProfile;

// Panitia
use App\Livewire\Panitia\Verifikasi\Dashboard as PanitiaVerifikasiDashboard;
use App\Livewire\Panitia\Verifikasi\VerifikasiBerkas;
use App\Livewire\Panitia\Penjadwalan\Dashboard as PanitiaPenjadwalanDashboard;
use App\Livewire\Panitia\Penjadwalan\JadwalUjians;
use App\Livewire\Panitia\Penjadwalan\SettingRuangan;
use App\Livewire\Panitia\Penjadwalan\SettingWaktu;
// use App\Livewire\Panitia\Penjadwalan\GeneratePenguji;
use App\Livewire\Panitia\Administrasi\Dashboard as PanitiaAdminDashboard;

// Dosen
use App\Livewire\Dosen\Dashboard as DosenDashboard;
use App\Livewire\Dosen\BerikanRevisi;
use App\Livewire\Dosen\DaftarRevisi;
use App\Livewire\Dosen\BerikanNilai;
use App\Livewire\Dosen\KuotaSaya;
use App\Livewire\Dosen\JadwalMenguji;
use App\Livewire\Dosen\Profile as DosenProfile;
use App\Livewire\Dosen\InputNilaiSistem;
use App\Livewire\Dosen\UploadNilaiBerkas;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/beranda', [PublicController::class, 'index'])->name('beranda');
Route::get('/jadwal', [PublicController::class, 'jadwal'])->name('jadwal');
Route::get('/wire', [PublicController::class, 'wire'])->name('wire');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboard::class)->name('dashboard');

        Route::prefix('jurusans')->name('jurusans.')->group(function () {
            Route::get('/', JurusanIndex::class)->name('index');
            Route::get('/create', JurusanCreate::class)->name('create');
            Route::get('/{jurusan}/edit', JurusanEdit::class)->name('edit');
        });

        Route::prefix('prodis')->name('prodis.')->group(function () {
            Route::get('/', ProdiIndex::class)->name('index');
            Route::get('/create', ProdiCreate::class)->name('create');
            Route::get('/{prodi}/edit', ProdiEdit::class)->name('edit');
        });

        Route::get('/kajur-sekjur', KajurSekjurIndex::class)->name('kajur-sekjur.index');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UserIndex::class)->name('index');
            Route::get('/create', UserCreate::class)->name('create');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', RoleIndex::class)->name('index');
            Route::get('/create', RoleCreate::class)->name('create');
            Route::get('/{role}/edit', RoleEdit::class)->name('edit');
        });

        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/settings', Settings::class)->name('settings');
    });

    /*
    |--------------------------------------------------------------------------
    | Kajur Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:kajur'])->prefix('kajur')->name('kajur.')->group(function () {
        // Dashboard
        Route::get('/', KajurDashboard::class)->name('dashboard');

        // Data Master
        Route::prefix('data-master')->name('data-master.')->group(function () {
            Route::get('/dosen', DosenIndex::class)->name('dosen');
            Route::get('/mahasiswa', MahasiswaIndex::class)->name('mahasiswa');
            Route::get('/panitia', PanitiaIndex::class)->name('panitia');
            Route::get('/kuota-dosen', KuotaDosen::class)->name('kuota-dosen');
            Route::get('/bidang-keahlian', BidangKeahlians::class)->name('bidang-keahlian');
            Route::get('/kepakaran', Kepakaran::class)->name('kepakaran');
            Route::get('/atur-atribut-dosen', AturAtributDosen::class)->name('atur-atribut-dosen');
            Route::get('/pengaturan-reminder', PengaturanReminder::class)->name('pengaturan-reminder');
        });

        // Verifikasi
        Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
            Route::get('/seminar-proposal', VerifikasiSeminarProposal::class)->name('seminar-proposal');
            Route::get('/seminar-hasil', VerifikasiSeminarHasil::class)->name('seminar-hasil');
            Route::get('/sidang-skripsi', VerifikasiSidangSkripsi::class)->name('sidang-skripsi');
        });

        // Profile & Settings
        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/settings', Settings::class)->name('settings');
    });

    /*
|--------------------------------------------------------------------------
| Sekjur Routes
|--------------------------------------------------------------------------
*/
    Route::middleware(['role:sekjur'])->prefix('sekjur')->name('sekjur.')->group(function () {
        Route::get('/', SekjurDashboard::class)->name('dashboard');

        // Data Master (View Only + Penguji)
        Route::prefix('data-master')->name('data-master.')->group(function () {
            Route::get('/dosen', DosenIndex::class)->name('dosen');
            Route::get('/mahasiswa', MahasiswaIndex::class)->name('mahasiswa');
            Route::get('/panitia', PanitiaIndex::class)->name('panitia');
            Route::get('/kuota-dosen', KuotaDosen::class)->name('kuota-dosen');
            Route::get('/bidang-keahlian', BidangKeahlians::class)->name('bidang-keahlian');
            Route::get('/kepakaran', Kepakaran::class)->name('kepakaran');

            // Penguji - SEKJUR yang mengelola
            Route::get('/penguji', PengujiIndex::class)->name('penguji');
            Route::get('/penguji/{pendaftaran}/generate', GeneratePengujiSekjur::class)->name('penguji.generate');
        });

        // Verifikasi (View Only)
        Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
            Route::get('/seminar-proposal', VerifikasiSeminarProposal::class)->name('seminar-proposal');
            Route::get('/seminar-hasil', VerifikasiSeminarHasil::class)->name('seminar-hasil');
            Route::get('/sidang-skripsi', VerifikasiSidangSkripsi::class)->name('sidang-skripsi');
        });

        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/settings', Settings::class)->name('settings');
    });
    /*
    |--------------------------------------------------------------------------
    | Mahasiswa Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // Dashboard
        Route::get('/', MahasiswaDashboard::class)->name('dashboard');

        // Pendaftaran
        Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
            Route::get('/', PendaftaranIndex::class)->name('index');
            Route::get('/create', PendaftaranCreate::class)->name('create');
            Route::get('/{pendaftaran}/edit', PendaftaranCreate::class)->name('edit');
        });

        // Jadwal Ujian
        Route::get('/jadwal', JadwalUjian::class)->name('jadwal');

        // Nilai
        Route::get('/nilai', Nilai::class)->name('nilai');

        // Profile
        Route::get('/profile', MahasiswaProfile::class)->name('profile');
    });

    /*
|--------------------------------------------------------------------------
| Panitia Verifikasi Routes
|--------------------------------------------------------------------------
*/
    Route::middleware(['role:panitia_verifikasi'])->prefix('panitia/verifikasi')->name('panitia.verifikasi.')->group(function () {
        Route::get('/', PanitiaVerifikasiDashboard::class)->name('dashboard');
        Route::get('/berkas', VerifikasiBerkas::class)->name('berkas');
        Route::get('/profile', Profile::class)->name('profile');
    });

    /*
    |--------------------------------------------------------------------------
    | Panitia Penjadwalan Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:panitia_penjadwalan'])->prefix('panitia/penjadwalan')->name('panitia.penjadwalan.')->group(function () {
        Route::get('/', PanitiaPenjadwalanDashboard::class)->name('dashboard');
        Route::get('/jadwal', JadwalUjians::class)->name('jadwal');
        Route::get('/setting-waktu', SettingWaktu::class)->name('setting-waktu');
        Route::get('/setting-ruangan', SettingRuangan::class)->name('setting-ruangan');
        Route::get('/profile', Profile::class)->name('profile');
    });

    /*
    |--------------------------------------------------------------------------
    | Panitia Administrasi Routes (Placeholder)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:panitia_administrasi'])->prefix('panitia/administrasi')->name('panitia.administrasi.')->group(function () {
        Route::get('/', PanitiaAdminDashboard::class)->name('dashboard');
        Route::get('/profile', Profile::class)->name('profile');
    });

    /*
|--------------------------------------------------------------------------
| Dosen Routes
|--------------------------------------------------------------------------
*/
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/', DosenDashboard::class)->name('dashboard');

        // Revisi
        Route::prefix('revisi')->name('revisi.')->group(function () {
            Route::get('/', DaftarRevisi::class)->name('index');
            Route::get('/{pendaftaran}/berikan', BerikanRevisi::class)->name('berikan');
        });

        // Nilai
        Route::prefix('nilai')->name('nilai.')->group(function () {
            Route::get('/', BerikanNilai::class)->name('index');
            Route::get('/{pendaftaran}/input', InputNilaiSistem::class)->name('input');
            Route::get('/{pendaftaran}/upload', UploadNilaiBerkas::class)->name('upload');
        });

        // Kuota
        Route::get('/kuota', KuotaSaya::class)->name('kuota');

        // Jadwal Menguji
        Route::get('/jadwal', JadwalMenguji::class)->name('jadwal');

        // Profile
        Route::get('/profile', DosenProfile::class)->name('profile');
    });

    /*
    |--------------------------------------------------------------------------
    | Redirect berdasarkan role
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

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
        } elseif ($user->hasRole('dosen')) {
            return redirect()->route('dosen.dashboard');
        }

        return view('dashboard.index');
    })->name('dashboard.index');
});
