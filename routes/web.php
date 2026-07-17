<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PdfController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\JurusanIndex;
use App\Livewire\Admin\JurusanCreate;
use App\Livewire\Admin\JurusanEdit;
use App\Livewire\Admin\ProdiIndex;
use App\Livewire\Admin\ProdiCreate;
use App\Livewire\Admin\ProdiEdit;
use App\Livewire\Admin\UserIndex;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\RoleIndex;
use App\Livewire\Admin\RoleCreate;
use App\Livewire\Admin\RoleEdit;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\NotificationSettings;
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
use App\Livewire\Mahasiswa\DaftarRevisi as MahasiswaDaftarRevisi;

// Panitia
use App\Livewire\Panitia\Verifikasi\Dashboard as PanitiaVerifikasiDashboard;
use App\Livewire\Panitia\Verifikasi\VerifikasiBerkas;
use App\Livewire\Panitia\Penjadwalan\Dashboard as PanitiaPenjadwalanDashboard;
use App\Livewire\Panitia\Penjadwalan\JadwalUjians;
use App\Livewire\Panitia\Penjadwalan\SettingRuangan;
use App\Livewire\Panitia\Penjadwalan\SettingWaktu;
// use App\Livewire\Panitia\Penjadwalan\GeneratePenguji;
use App\Livewire\Panitia\Administrasi\Dashboard as PanitiaAdminDashboard;
use App\Livewire\Panitia\Administrasi\Laporan as PanitiaAdminLaporan;
use App\Livewire\Panitia\Administrasi\KelolaNilaiBerkas as PanitiaAdminKelolaNilaiBerkas;
use App\Http\Controllers\Panitia\LaporanController as PanitiaLaporanController;

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

//test wa
use App\Livewire\WhatsAppTest;

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

// PDF Guide Routes
Route::get('/panduan/mahasiswa', [PdfController::class, 'mahasiswa'])->name('pdf.mahasiswa');
Route::get('/panduan/dosen', [PdfController::class, 'dosen'])->name('pdf.dosen');
Route::get('/panduan/panitia-verifikasi', [PdfController::class, 'panitiaVerifikasi'])->name('pdf.panitia-verifikasi');
Route::get('/panduan/panitia-penjadwalan', [PdfController::class, 'panitiaPenjadwalan'])->name('pdf.panitia-penjadwalan');
Route::get('/panduan/kajur', [PdfController::class, 'kajur'])->name('pdf.kajur');
Route::get('/panduan/sekjur', [PdfController::class, 'sekjur'])->name('pdf.sekjur');

Route::get('/whatsapp-test', WhatsAppTest::class)->name('whatsapp.test');
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboard::class)->name('dashboard');

        Route::prefix('jurusans')->name('jurusans.')->group(function () {
            Route::get('/', JurusanIndex::class)->name('index')->middleware('can:view_jurusan');
            Route::get('/create', JurusanCreate::class)->name('create')->middleware('can:create_jurusan');
            Route::get('/{jurusan}/edit', JurusanEdit::class)->name('edit')->middleware('can:edit_jurusan');
        });

        Route::prefix('prodis')->name('prodis.')->group(function () {
            Route::get('/', ProdiIndex::class)->name('index')->middleware('can:view_prodi');
            Route::get('/create', ProdiCreate::class)->name('create')->middleware('can:create_prodi');
            Route::get('/{prodi}/edit', ProdiEdit::class)->name('edit')->middleware('can:edit_prodi');
        });

        Route::get('/kajur-sekjur', UserIndex::class)->name('kajur-sekjur.index')->middleware('can:view_users');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UserIndex::class)->name('index')->middleware('can:view_users');
            Route::get('/create', UserCreate::class)->name('create')->middleware('can:create_users');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', RoleIndex::class)->name('index')->middleware('can:view_roles');
            Route::get('/create', RoleCreate::class)->name('create')->middleware('can:assign_roles');
            Route::get('/{role}/edit', RoleEdit::class)->name('edit')->middleware('can:assign_roles');
        });

        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/notification-settings', NotificationSettings::class)->name('notification-settings')->middleware('can:view_notification_settings');
    });

    /*
    |--------------------------------------------------------------------------
    | Kajur Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('kajur')->name('kajur.')->group(function () {
        Route::get('/', KajurDashboard::class)->name('dashboard');

        Route::prefix('data-master')->name('data-master.')->group(function () {
            Route::get('/dosen', DosenIndex::class)->name('dosen')->middleware('can:view_dosen');
            Route::get('/mahasiswa', MahasiswaIndex::class)->name('mahasiswa')->middleware('can:view_mahasiswa');
            Route::get('/panitia', PanitiaIndex::class)->name('panitia')->middleware('can:view_panitia');
            Route::get('/kuota-dosen', KuotaDosen::class)->name('kuota-dosen')->middleware('can:view_kuota_dosen');
            Route::get('/bidang-keahlian', BidangKeahlians::class)->name('bidang-keahlian')->middleware('can:view_bidang_keahlian');
            Route::get('/kepakaran', Kepakaran::class)->name('kepakaran')->middleware('can:view_kepakaran');
            Route::get('/atur-atribut-dosen', AturAtributDosen::class)->name('atur-atribut-dosen')->middleware('can:view_atribut_dosen');
            Route::get('/pengaturan-reminder', PengaturanReminder::class)->name('pengaturan-reminder')->middleware('can:view_pengaturan_reminder');
        });

        Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
            Route::get('/seminar-proposal', VerifikasiSeminarProposal::class)->name('seminar-proposal')->middleware('can:verify_seminar_proposal');
            Route::get('/seminar-hasil', VerifikasiSeminarHasil::class)->name('seminar-hasil')->middleware('can:verify_seminar_hasil');
            Route::get('/sidang-skripsi', VerifikasiSidangSkripsi::class)->name('sidang-skripsi')->middleware('can:verify_sidang_skripsi');
        });

        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/settings', Settings::class)->name('settings');
    });

    /*
|--------------------------------------------------------------------------
| Sekjur Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('sekjur')->name('sekjur.')->group(function () {
        Route::get('/', SekjurDashboard::class)->name('dashboard');

        Route::prefix('data-master')->name('data-master.')->group(function () {
            Route::get('/dosen', DosenIndex::class)->name('dosen')->middleware('can:view_dosen');
            Route::get('/mahasiswa', MahasiswaIndex::class)->name('mahasiswa')->middleware('can:view_mahasiswa');
            Route::get('/panitia', PanitiaIndex::class)->name('panitia')->middleware('can:view_panitia');
            Route::get('/kuota-dosen', KuotaDosen::class)->name('kuota-dosen')->middleware('can:view_kuota_dosen');
            Route::get('/bidang-keahlian', BidangKeahlians::class)->name('bidang-keahlian')->middleware('can:view_bidang_keahlian');
            Route::get('/kepakaran', Kepakaran::class)->name('kepakaran')->middleware('can:view_kepakaran');

            Route::get('/penguji', PengujiIndex::class)->name('penguji')->middleware('can:view_penguji');
            Route::get('/penguji/{pendaftaran}/generate', GeneratePengujiSekjur::class)->name('penguji.generate')->middleware('can:create_penguji');
        });

        Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
            Route::get('/seminar-proposal', VerifikasiSeminarProposal::class)->name('seminar-proposal')->middleware('can:verify_seminar_proposal');
            Route::get('/seminar-hasil', VerifikasiSeminarHasil::class)->name('seminar-hasil')->middleware('can:verify_seminar_hasil');
            Route::get('/sidang-skripsi', VerifikasiSidangSkripsi::class)->name('sidang-skripsi')->middleware('can:verify_sidang_skripsi');
        });

        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/settings', Settings::class)->name('settings');
    });
    /*
    |--------------------------------------------------------------------------
    | Mahasiswa Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/', MahasiswaDashboard::class)->name('dashboard');

        Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
            Route::get('/', PendaftaranIndex::class)->name('index')->middleware('can:view_pendaftaran');
            Route::get('/create', PendaftaranCreate::class)->name('create')->middleware('can:create_pendaftaran');
            Route::get('/{pendaftaran}/edit', PendaftaranCreate::class)->name('edit')->middleware('can:edit_pendaftaran');
        });

        Route::get('/jadwal', JadwalUjian::class)->name('jadwal')->middleware('can:view_jadwal');
        Route::get('/revisi', MahasiswaDaftarRevisi::class)->name('revisi')->middleware('can:view_revisi');
        Route::get('/nilai', Nilai::class)->name('nilai')->middleware('can:view_nilai');
        Route::get('/profile', MahasiswaProfile::class)->name('profile');
    });

    /*
|--------------------------------------------------------------------------
| Panitia Verifikasi Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('panitia/verifikasi')->name('panitia.verifikasi.')->group(function () {
        Route::get('/', PanitiaVerifikasiDashboard::class)->name('dashboard');
        Route::get('/berkas', VerifikasiBerkas::class)->name('berkas')->middleware('can:verify_berkas');
        Route::get('/profile', Profile::class)->name('profile');
    });

    /*
    |--------------------------------------------------------------------------
    | Panitia Penjadwalan Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('panitia/penjadwalan')->name('panitia.penjadwalan.')->group(function () {
        Route::get('/', PanitiaPenjadwalanDashboard::class)->name('dashboard');
        Route::get('/jadwal', JadwalUjians::class)->name('jadwal')->middleware('can:manage_jadwal');
        Route::get('/setting-waktu', SettingWaktu::class)->name('setting-waktu')->middleware('can:manage_jadwal');
        Route::get('/setting-ruangan', SettingRuangan::class)->name('setting-ruangan')->middleware('can:manage_jadwal');
        Route::get('/profile', Profile::class)->name('profile');
    });

    /*
    |--------------------------------------------------------------------------
    | Panitia Administrasi Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('panitia/administrasi')->name('panitia.administrasi.')->group(function () {
        Route::get('/', PanitiaAdminDashboard::class)->name('dashboard');
        Route::get('/nilai-berkas', PanitiaAdminKelolaNilaiBerkas::class)->name('nilai-berkas')->middleware('can:view_nilai_berkas');
        Route::get('/laporan', PanitiaAdminLaporan::class)->name('laporan')->middleware('can:view_laporan');
        Route::get('/laporan/{jenis}/download', [PanitiaLaporanController::class, 'download'])->name('laporan.download')->middleware('can:export_laporan');
        Route::get('/profile', Profile::class)->name('profile');
    });

    /*
|--------------------------------------------------------------------------
| Dosen Routes
|--------------------------------------------------------------------------
*/
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/', DosenDashboard::class)->name('dashboard');

        Route::prefix('revisi')->name('revisi.')->group(function () {
            Route::get('/', DaftarRevisi::class)->name('index')->middleware('can:view_revisi');
            Route::get('/{pendaftaran}/berikan', BerikanRevisi::class)->name('berikan')->middleware('can:create_revisi');
        });

        Route::prefix('nilai')->name('nilai.')->group(function () {
            Route::get('/', BerikanNilai::class)->name('index')->middleware('can:view_nilai');
            Route::get('/{pendaftaran}/input', InputNilaiSistem::class)->name('input')->middleware('can:create_nilai');
            Route::get('/{pendaftaran}/upload', UploadNilaiBerkas::class)->name('upload')->middleware('can:create_nilai');
        });

        Route::get('/kuota', KuotaSaya::class)->name('kuota')->middleware('can:view_kuota_saya');
        Route::get('/jadwal', JadwalMenguji::class)->name('jadwal')->middleware('can:view_jadwal_dosen');
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
