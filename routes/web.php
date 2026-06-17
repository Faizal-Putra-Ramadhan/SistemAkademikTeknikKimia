<?php

use App\Http\Controllers\Admin\KelolaUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dosen\DosenRiskAssessmentController;
use App\Http\Controllers\Kaprodi\KaprodiController;
use App\Http\Controllers\KepalaLab\KepalaLabController;
use App\Http\Controllers\LaboranController;
use App\Http\Controllers\Mahasiswa\BebasLabController;
use App\Http\Controllers\Mahasiswa\PeminjamanRuanganController;
use App\Http\Controllers\Mahasiswa\PengembalianAlatController;
use App\Http\Controllers\Mahasiswa\PengembalianRuanganController;
use App\Http\Controllers\Mahasiswa\RiskAssessmentController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\SafetyOfficer\SafetyOfficerController;
use Illuminate\Support\Facades\Route;

// ============================================
// REGISTRATION ROUTES (Public)
// ============================================

// Show registration form
Route::get('/registrasi', [RegistrasiController::class , 'index'])->name('registrasi');

// Submit registration (send verification email)
Route::post('/registrasi', [RegistrasiController::class , 'store'])
    ->middleware('throttle:10,1')
    ->name('registrasi.store');

// Pending verification page
Route::get('/registrasi/pending', [RegistrasiController::class , 'pending'])->name('registrasi.pending');

// Email verification (click link from email)
Route::get('/registrasi/verify/{token}', [RegistrasiController::class , 'verify'])->name('registrasi.verify');

// Registration success page
Route::get('/registrasi/success', [RegistrasiController::class , 'success'])->name('registrasi.success');

// Resend verification email
Route::post('/registrasi/resend', [RegistrasiController::class , 'resendVerification'])
    ->middleware('throttle:5,1')
    ->name('registrasi.resend');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/test', function () {
        return 'Test route berhasil diakses!';
    });

    Route::get('/test-auth', function () {
        $user = Auth::user();
        $ras = \App\Models\RiskAssessment::where('user_id', $user->id)
            ->where('daftar_lab_id', 1)
            ->where('status', 'disetujui')
            ->get();

        return "User ID: {$user->id} | Nama: {$user->Nama} | Role: {$user->Role_User} | RA Count for Lab 1: " . $ras->count();
    });
});

Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class , 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class , 'logout'])->name('logout');

Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'showForgotPasswordForm'])
    ->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'sendResetLinkEmail'])
    ->middleware('throttle:5,1')
    ->name('password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'showResetPasswordForm'])
    ->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'resetPassword'])
    ->middleware('throttle:5,1')
    ->name('password.update');

// Switch active role for multi-role user (single account)
Route::post('/switch-role', [RoleSelectionController::class , 'switch'])
    ->middleware('auth')
    ->name('role.switch-role');

// MSDS download - didefinisikan early agar route [msds.show] tersedia
Route::get('/msds/{id}', [App\Http\Controllers\MsdsController::class , 'show'])
    ->middleware('auth')
    ->name('msds.show');

// ============================================
// ROUTE PUBLIK (TANPA AUTH)
// ============================================

// ============================================
// ROUTE PUBLIK (TANPA AUTH)
// ============================================

// Landing Page
Route::get('/', function () {
    // Jika sudah login, redirect ke dashboard sesuai role
    if (Auth::check()) {
        $user = Auth::user();

        return match ($user->Role_User) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Dosen' => redirect()->route('dosen.dashboard'),
            'Mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            'Peneliti Eksternal' => redirect()->route('peneliti-eksternal.dashboard'),
            'Laboran' => redirect()->route('laboran.dashboard'),
            'Kepala Laboratorium' => redirect()->route('kepala-lab.dashboard'),
            'Safety Officer' => redirect()->route('safety-officer.dashboard'),
            'Kaprodi' => redirect()->route('kaprodi.dashboard'), // ✅ BARU
            default => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
})->name('home');



// ============================================
// ROUTE ADMIN (PERLU AUTH + ROLE:Admin|Operator)
// ============================================

Route::middleware(['auth', 'role:Admin|Operator'])->prefix('admin')->name('admin.')->group(function () {

    // Aktivitas Administrator
    Route::get('/aktivitas-administrator', [App\Http\Controllers\ActivityLogController::class , 'index'])
        ->name('aktivitas-administrator');

    // Daftar Lab
    Route::prefix('daftar-lab')->name('daftar-lab.')->group(function () {
        Route::get('/', [App\Http\Controllers\DaftarLabController::class , 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\DaftarLabController::class , 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\DaftarLabController::class , 'store'])->name('store');
        Route::get('/{lab}/edit', [App\Http\Controllers\DaftarLabController::class , 'edit'])->name('edit');
        Route::put('/{lab}', [App\Http\Controllers\DaftarLabController::class , 'update'])->name('update');
        Route::delete('/{lab}', [App\Http\Controllers\DaftarLabController::class , 'destroy'])->name('destroy');
    });

    // Tambah Laboran
    Route::prefix('tambah-laboran')->name('tambah-laboran.')->group(function () {
        Route::get('/', [App\Http\Controllers\TambahLaboranController::class , 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\TambahLaboranController::class , 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TambahLaboranController::class , 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\TambahLaboranController::class , 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\TambahLaboranController::class , 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\TambahLaboranController::class , 'destroy'])->name('destroy');
    });

    // Strictly ADMIN only routes
    Route::middleware('role:Admin')->group(function() {
        
        // Dashboard Admin (Welcome Page)
        Route::get('/dashboard', function () {
                return view('welcome', [
                'daftar_labs' => \App\Models\DaftarLab::all(),
                'daftar_users' => \App\Models\DaftarUser::with('roles')->orderBy('created_at', 'desc')->get(),
                'daftar_laborans' => \App\Models\DaftarLaboranLaboratorium::all(),
                ]);
            }
        )->name('dashboard');

        // Kelola User
        Route::get('/kelola-user', [KelolaUserController::class , 'index'])->name('kelola-user.index');
        Route::get('/kelola-user/{id}/edit', [KelolaUserController::class , 'edit'])->name('kelola-user.edit');
        Route::put('/kelola-user/{id}', [KelolaUserController::class , 'update'])->middleware('throttle.sensitive')->name('kelola-user.update');
        Route::delete('/kelola-user/{id}', [KelolaUserController::class , 'destroy'])->middleware('throttle.sensitive')->name('kelola-user.destroy');

        // Reset Password
        Route::get('/kelola-user/{id}/reset-password', [KelolaUserController::class , 'showResetPassword'])->name('kelola-user.reset-password');
        Route::put('/kelola-user/{id}/reset-password', [KelolaUserController::class , 'resetPassword'])->middleware(['throttle.sensitive', 'secure.password'])->name('kelola-user.reset-password.update');

        // Toggle Status
        Route::post('/kelola-user/{id}/toggle-status', [KelolaUserController::class , 'toggleStatus'])->name('kelola-user.toggle-status');

        // Tambah User
        Route::prefix('tambah-user')->name('tambah-user.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\TambahUserController::class , 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\TambahUserController::class , 'store'])->middleware(['throttle.sensitive', 'secure.password'])->name('store');
        });

        // Alat Lab
        Route::prefix('alat-lab')->name('alat-lab.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AlatLabController::class , 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\AlatLabController::class , 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\AlatLabController::class , 'store'])->name('store');
            Route::get('/{alat}/edit', [App\Http\Controllers\Admin\AlatLabController::class , 'edit'])->name('edit');
            Route::put('/{alat}', [App\Http\Controllers\Admin\AlatLabController::class , 'update'])->name('update');
            Route::delete('/{alat}', [App\Http\Controllers\Admin\AlatLabController::class , 'destroy'])->name('destroy');
        });

        // Kelola Template
        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\TemplateController::class , 'index'])->name('index');
            Route::post('/upload-ra', [App\Http\Controllers\Admin\TemplateController::class , 'uploadRATemplate'])->name('upload-ra');
            Route::post('/upload-bebas-lab', [App\Http\Controllers\Admin\TemplateController::class , 'uploadBebasLabTemplate'])->name('upload-bebas-lab');
        });

        // Kelola Pengumuman
        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            Route::get('/', [App\Http\Controllers\PengumumanController::class , 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\PengumumanController::class , 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\PengumumanController::class , 'store'])->name('store');
            Route::get('/{pengumuman}/edit', [App\Http\Controllers\PengumumanController::class , 'edit'])->name('edit');
            Route::put('/{pengumuman}', [App\Http\Controllers\PengumumanController::class , 'update'])->name('update');
            Route::delete('/{pengumuman}', [App\Http\Controllers\PengumumanController::class , 'destroy'])->name('destroy');
        });

        // Update Role
        Route::post('/update-role', [App\Http\Controllers\RoleController::class , 'updateRole'])->name('update-role');

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\ProfileController::class , 'edit'])->name('edit');
            Route::put('/', [App\Http\Controllers\ProfileController::class , 'update'])->name('update');
            Route::patch('/', [App\Http\Controllers\ProfileController::class , 'update'])->name('patch');
            Route::delete('/', [App\Http\Controllers\ProfileController::class , 'destroy'])->name('destroy');
        });
    });
});

// ============================================
// ROUTE KAPRODI (KEPALA PROGRAM STUDI)
// ============================================

Route::middleware(['auth', 'role:Kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [KaprodiController::class , 'dashboard'])->name('dashboard');

    // Profil
    Route::get('/profil', [KaprodiController::class , 'profil'])->name('profil');
    Route::post('/profil', [KaprodiController::class , 'updateProfil'])->name('profil.update');

    // Risk Assessment Management
    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
            // List RA yang menunggu persetujuan
            Route::get('/', [KaprodiController::class , 'index'])->name('index');

            // Review detail RA
            Route::get('/{id}', [KaprodiController::class , 'show'])->name('show');

            // Final Approve/Reject dengan pengaturan durasi peminjaman
            Route::post('/{id}/approve', [KaprodiController::class , 'approve'])->name('approve');

            // Request revision
            Route::post('/{id}/request-revision', [KaprodiController::class , 'requestRevision'])->name('request-revision');

            // Report
            Route::get('/report/list', [KaprodiController::class , 'report'])->name('report');
        }
        );

        // Pengumuman Management
        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            Route::get('/', [KaprodiController::class , 'pengumuman'])->name('index');
            Route::get('/create', [KaprodiController::class , 'createPengumuman'])->name('create');
            Route::post('/', [KaprodiController::class , 'storePengumuman'])->name('store');
            Route::get('/{id}/edit', [KaprodiController::class , 'editPengumuman'])->name('edit');
            Route::put('/{id}', [KaprodiController::class , 'updatePengumuman'])->name('update');
            Route::delete('/{id}', [KaprodiController::class , 'destroyPengumuman'])->name('destroy');
        }
        );

        // Routes untuk perpanjangan Risk Assessment
        Route::get('/perpanjangan', [KaprodiController::class , 'indexPerpanjangan'])
            ->name('perpanjangan.index');
        Route::get('/perpanjangan/{id}', [KaprodiController::class , 'showPerpanjangan'])
            ->name('perpanjangan.show');
        Route::post('/perpanjangan/{id}/approve', [KaprodiController::class , 'approvePerpanjangan'])
            ->name('perpanjangan.approve');

        // Peminjaman Ruangan - Kaprodi hanya bisa lihat (notification only)
        Route::get('/peminjaman-ruangan', [KaprodiController::class , 'peminjamanRuanganIndex'])
            ->name('peminjaman-ruangan.index');
        Route::get('/peminjaman-ruangan/{id}', [KaprodiController::class , 'peminjamanRuanganShow'])
            ->name('peminjaman-ruangan.show');
    });

// Ganti bagian ROUTE MAHASISWA di web.php dengan ini:

// ============================================
// ROUTE MAHASISWA (PERLU AUTH + ROLE)
// ============================================

Route::middleware(['auth', 'role:Mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    Route::get('/profil', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'profil'])->name('profil');
    Route::post('/profil', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'updateProfil'])->name('profil.update');

    // Dashboard - Pilih Lab
    Route::get('/', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'dashboard', 'pengumuman'])->name('dashboard');

    // Route::get('/dashboard1', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'dashboard1', 'pengumuman'])->name('dashboard1');

    Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'formPeminjamanAlat'])->name('pinjam-alat');
    Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'storePeminjamanAlat'])
        ->middleware('stock.group.access')
        ->name('pinjam-alat.store');

    // Detail Lab - Lihat Alat yang Tersedia
    Route::get('/lab/{id}', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'detailLab'])->name('lab.detail');

    // Peminjaman Ruangan
    // Route::get('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'formPeminjamanRuangan'])->name('pinjam-ruangan');
    // Route::post('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePeminjamanRuangan'])->name('pinjam-ruangan.store');

    Route::get('/lab/{labId}/pinjam-ruangan', [PeminjamanRuanganController::class , 'create'])
        ->name('pinjam-ruangan');
    Route::post('/lab/{labId}/pinjam-ruangan', [PeminjamanRuanganController::class , 'store'])
        ->name('pinjam-ruangan.store');
    // Peminjaman Alat
    Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'formPeminjamanAlat'])->name('pinjam-alat');
    Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'storePeminjamanAlat'])
        ->middleware('stock.group.access')
        ->name('pinjam-alat.store');

    // Pengajuan Penelitian
    // Route::get('/lab/{id}/pengajuan-penelitian', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'formPengajuanPenelitian'])->name('pengajuan-penelitian');
    // Route::post('/lab/{id}/pengajuan-penelitian', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePengajuanPenelitian'])->name('pengajuan-penelitian.store');

    Route::get('/pengajuan-penelitian/{id}', [App\Http\Controllers\Mahasiswa\PengajuanPenelitianController::class , 'create'])->name('pengajuan-penelitian');
    Route::post('/pengajuan-penelitian/{id}', [App\Http\Controllers\Mahasiswa\PengajuanPenelitianController::class , 'store'])->name('pengajuan-penelitian.store');

    // Aktivitas Mahasiswa
    // Route::get('/lab/{id}/aktivitas', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'aktivitas'])->name('aktivitas');
    Route::get('/lab/{id}/aktivitas', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'aktivitas'])->name('aktivitas');

    // Pengumuman
    Route::get('/pengumuman', [App\Http\Controllers\Mahasiswa\MahasiswaController::class , 'pengumuman'])->name('pengumuman');

    // Bebas Lab
    Route::get('/bebas-lab', [BebasLabController::class , 'index'])->name('bebas-lab.index');
    Route::post('/bebas-lab', [BebasLabController::class , 'store'])->name('bebas-lab.store');
    Route::post('/bebas-lab/{id}/cancel', [BebasLabController::class , 'cancelBebasLab'])->name('bebas-lab.cancel');
    Route::get('/bebas-lab/{id}', [BebasLabController::class , 'show'])->name('bebas-lab.show');
    Route::get('/bebas-lab/{id}/download', [BebasLabController::class , 'download'])->name('bebas-lab.download');

    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
            // Lihat daftar RA mahasiswa
            Route::get('/', [RiskAssessmentController::class , 'index'])->name('index');

            // Buat RA baru dari lab tertentu
            Route::get('/lab/{labId}/create', [RiskAssessmentController::class , 'create'])->name('create');
            Route::post('/lab/{labId}/store', [RiskAssessmentController::class , 'store'])->name('store');
            // 👇 Route baru untuk ajukan ke Kaprodi
            Route::post('/{id}/ajukan-kaprodi', [RiskAssessmentController::class , 'ajukanKeKaprodi'])->name('ajukan-kaprodi');

            // Lihat detail RA
            Route::get('/{id}', [RiskAssessmentController::class , 'show'])->name('show');

            // Edit RA (hanya jika status draft)
            Route::get('/{id}/edit', [RiskAssessmentController::class , 'edit'])->name('edit');
            Route::put('/{id}', [RiskAssessmentController::class , 'update'])->name('update');

            // Download PDF
            Route::get('/{id}/download-pdf', [RiskAssessmentController::class , 'downloadPdf'])->name('download-pdf');

            // NEW: Select interview schedule option (Mahasiswa memilih jadwal)
            Route::post('/{id}/select-schedule', [SafetyOfficerController::class , 'selectScheduleOption'])->name('select-schedule');

            // NEW: List pending schedule options (Mahasiswa lihat jadwal yang perlu dipilih)
            Route::get('/pending/schedules', [SafetyOfficerController::class , 'listPendingScheduleOptions'])->name('pending-schedules');
        }
        );

        // Routes untuk perpanjangan Risk Assessment
        Route::get('/risk-assessment/{id}/perpanjangan', [RiskAssessmentController::class , 'formPerpanjangan'])
            ->name('risk-assessment.perpanjangan');

        Route::post('/risk-assessment/{id}/perpanjangan', [RiskAssessmentController::class , 'ajukanPerpanjangan'])
            ->name('risk-assessment.perpanjangan.store');

        Route::delete('/risk-assessment/{id}/perpanjangan', [RiskAssessmentController::class , 'batalkanPerpanjangan'])
            ->name('risk-assessment.perpanjangan.batalkan');

        // Pengajuan pengembalian alat
        Route::post('/peminjaman-alat/{id}/ajukan-pengembalian', [App\Http\Controllers\Mahasiswa\PeminjamanAlatController::class , 'ajukanPengembalian'])
            ->name('peminjaman-alat.ajukan-pengembalian');

        // Pengajuan pengembalian ruangan
        Route::post('/peminjaman-ruangan/{id}/ajukan-pengembalian', [App\Http\Controllers\Mahasiswa\PeminjamanRuanganController::class , 'ajukanPengembalian'])
            ->name('peminjaman-ruangan.ajukan-pengembalian');
    });

// ============================================
// ROUTE PENELITI EKSTERNAL (PERLU AUTH + ROLE)
// ============================================

Route::middleware(['auth', 'role:Peneliti Eksternal'])->prefix('peneliti-eksternal')->name('peneliti-eksternal.')->group(function () {

    Route::get('/profil', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'profil'])->name('profil');
    Route::post('/profil', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'updateProfil'])->name('profil.update');

    // Dashboard - Pilih Lab
    Route::get('/', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'dashboard'])->name('dashboard');

    Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'formPeminjamanAlat'])->name('pinjam-alat');
    Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'storePeminjamanAlat'])->name('pinjam-alat.store');

    // Detail Lab - Lihat Alat yang Tersedia
    Route::get('/lab/{id}', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'detailLab'])->name('lab.detail');

    // Peminjaman Ruangan
    Route::get('/lab/{labId}/pinjam-ruangan', [App\Http\Controllers\PenelitiEksternal\PeminjamanRuanganController::class , 'create'])
        ->name('pinjam-ruangan');
    Route::post('/lab/{labId}/pinjam-ruangan', [App\Http\Controllers\PenelitiEksternal\PeminjamanRuanganController::class , 'store'])
        ->name('pinjam-ruangan.store');

    // Pengajuan Penelitian
    Route::get('/pengajuan-penelitian/{id}', [App\Http\Controllers\PenelitiEksternal\PengajuanPenelitianController::class , 'create'])->name('pengajuan-penelitian');
    Route::post('/pengajuan-penelitian/{id}', [App\Http\Controllers\PenelitiEksternal\PengajuanPenelitianController::class , 'store'])->name('pengajuan-penelitian.store');

    // Aktivitas Peneliti Eksternal
    Route::get('/lab/{id}/aktivitas', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'aktivitas'])->name('aktivitas');

    // Bebas Lab
    Route::get('/bebas-lab', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'formBebasLab'])->name('bebas-lab');
    Route::post('/bebas-lab', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'storeBebasLab'])->name('bebas-lab.store');
    Route::get('/bebas-lab/{id}/download', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'downloadBebasLab'])->name('bebas-lab.download');

    // Pengumuman
    Route::get('/pengumuman', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'pengumuman'])->name('pengumuman');

    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
            // Lihat daftar RA peneliti eksternal
            Route::get('/', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'index'])->name('index');

            // Buat RA baru dari lab tertentu
            Route::get('/lab/{labId}/create', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'create'])->name('create');
            Route::post('/lab/{labId}/store', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'store'])->name('store');
            // Ajukan ke Kaprodi
            Route::post('/{id}/ajukan-kaprodi', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'ajukanKeKaprodi'])->name('ajukan-kaprodi');

            // Lihat detail RA
            Route::get('/{id}', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'show'])->name('show');

            // Edit RA (hanya jika status draft)
            Route::get('/{id}/edit', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'update'])->name('update');

            // Download PDF
            Route::get('/{id}/download-pdf', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'downloadPdf'])->name('download-pdf');

            // NEW: Select interview schedule option (Peneliti Eksternal memilih jadwal)
            Route::post('/{id}/select-schedule', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'selectScheduleOption'])->name('select-schedule');
        }
        );

        // Routes untuk perpanjangan Risk Assessment
        Route::get('/risk-assessment/{id}/perpanjangan', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'formPerpanjangan'])
            ->name('risk-assessment.perpanjangan');

        Route::post('/risk-assessment/{id}/perpanjangan', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'ajukanPerpanjangan'])
            ->name('risk-assessment.perpanjangan.store');

        Route::delete('/risk-assessment/{id}/perpanjangan', [App\Http\Controllers\PenelitiEksternal\RiskAssessmentController::class , 'batalkanPerpanjangan'])
            ->name('risk-assessment.perpanjangan.batalkan');

        // Pengajuan pengembalian alat
        Route::post('/peminjaman-alat/{id}/ajukan-pengembalian', [App\Http\Controllers\PenelitiEksternal\PenelitiEksternalController::class , 'ajukanPengembalian'])
            ->name('peminjaman-alat.ajukan-pengembalian');

        // Pengajuan pengembalian ruangan
        Route::post('/peminjaman-ruangan/{id}/ajukan-pengembalian', [App\Http\Controllers\PenelitiEksternal\PeminjamanRuanganController::class , 'ajukanPengembalian'])
            ->name('peminjaman-ruangan.ajukan-pengembalian');
    });

// ============================================
// ROUTE LABORAN (PERLU AUTH + ROLE)
// ============================================

Route::middleware(['auth', 'role:Laboran'])->prefix('laboran')->name('laboran.')->group(function () {
    // Lab switcher route
    Route::post('/lab/{id}/switch', [App\Http\Controllers\LabSwitchController::class , 'switch'])->name('lab.switch');

    Route::get('/dashboard/{id?}', [App\Http\Controllers\LaboranController::class , 'dashboard'])->name('dashboard');
    // Route::get('/dashboard1', [App\Http\Controllers\LaboranController::class, 'dashboard1'])->name('dashboard1');

    // Peminjaman Ruangan
    // Route::post('/ruangan/{id}/setujui', [LaboranController::class, 'setujuiRuangan'])->name('ruangan.setujui');
    // Route::post('/ruangan/{id}/tolak', [LaboranController::class, 'tolakRuangan'])->name('ruangan.tolak');

    // Peminjaman Alat
    Route::put('/alat/{id}/setujui', [App\Http\Controllers\LaboranController::class , 'setujuiAlat'])->name('alat.setujui');
    Route::put('/alat/{id}/tolak', [App\Http\Controllers\LaboranController::class , 'tolakAlat'])->name('alat.tolak');
    Route::put('/alat/{id}/kembalikan', [App\Http\Controllers\LaboranController::class , 'kembalikanAlat'])->name('alat.kembalikan');

    // Pengajuan Penelitian
    Route::get('/penelitian/{id}', [App\Http\Controllers\LaboranController::class , 'detailPenelitian'])->name('penelitian.detail');
    Route::put('/penelitian/{id}/setujui', [App\Http\Controllers\LaboranController::class , 'setujuiPenelitian'])->name('penelitian.setujui');
    Route::put('/penelitian/{id}/tolak', [App\Http\Controllers\LaboranController::class , 'tolakPenelitian'])->name('penelitian.tolak');

    // Pengumuman
    Route::get('/pengumuman/create', [App\Http\Controllers\LaboranController::class , 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\LaboranController::class , 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\LaboranController::class , 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class , 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class , 'destroyPengumuman'])->name('pengumuman.destroy');

    // Peminjaman Ruangan - Halaman Terpisah
    Route::get('/lab/{id}/peminjaman-ruangan', [App\Http\Controllers\LaboranController::class , 'peminjamanRuangan'])->name('peminjaman-ruangan');
    Route::put('/ruangan/{id}/setujui', [App\Http\Controllers\LaboranController::class , 'setujuiRuangan'])->name('ruangan.setujui');
    Route::put('/ruangan/{id}/tolak', [App\Http\Controllers\LaboranController::class , 'tolakRuangan'])->name('ruangan.tolak');
    Route::put('/ruangan/{id}/kembalikan', [App\Http\Controllers\LaboranController::class , 'kembalikanRuangan'])->name('ruangan.kembalikan');

    // Peminjaman Alat - Halaman Terpisah
    Route::get('/lab/{id}/peminjaman-alat', [App\Http\Controllers\LaboranController::class , 'peminjamanAlat'])->name('peminjaman-alat');
    Route::put('/alat/{id}/setujui', [App\Http\Controllers\LaboranController::class , 'setujuiAlat'])->name('alat.setujui');
    Route::put('/alat/{id}/tolak', [App\Http\Controllers\LaboranController::class , 'tolakAlat'])->name('alat.tolak');
    Route::put('/alat/{id}/kembalikan', [App\Http\Controllers\LaboranController::class , 'kembalikanAlat'])->name('alat.kembalikan');

    // Bebas Lab
    Route::get('/lab/{id}/bebas-lab', [App\Http\Controllers\LaboranController::class , 'bebasLab'])->name('bebas-lab');
    Route::get('/lab/{labId}/bebas-lab/{requestId}', [App\Http\Controllers\LaboranController::class , 'bebasLabDetail'])->name('bebas-lab.detail');
    Route::get('/lab/{labId}/bebas-lab/{requestId}/download', [App\Http\Controllers\LaboranController::class , 'downloadBebasLab'])->name('bebas-lab.download');
    Route::put('/lab/{labId}/bebas-lab/{requestId}/setujui', [App\Http\Controllers\LaboranController::class , 'setujuiBebasLab'])->name('bebas-lab.approve');

    // Pengajuan Penelitian - Halaman Terpisah
    Route::get('/lab/{id}/pengajuan-penelitian', [App\Http\Controllers\LaboranController::class , 'pengajuanPenelitian'])->name('pengajuan-penelitian');
    Route::get('/penelitian/{id}', [App\Http\Controllers\LaboranController::class , 'detailPenelitian'])->name('penelitian.detail');
    Route::put('/penelitian/{id}/setujui', [App\Http\Controllers\LaboranController::class , 'setujuiPenelitian'])->name('penelitian.setujui');
    Route::put('/penelitian/{id}/tolak', [App\Http\Controllers\LaboranController::class , 'tolakPenelitian'])->name('penelitian.tolak');

    // Kelola Pengumuman - Halaman Terpisah
    Route::get('/lab/{id}/pengumuman', [App\Http\Controllers\LaboranController::class , 'pengumuman'])->name('pengumuman');
    Route::get('/pengumuman/create', [App\Http\Controllers\LaboranController::class , 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\LaboranController::class , 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\LaboranController::class , 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class , 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class , 'destroyPengumuman'])->name('pengumuman.destroy');

    // Profil
    Route::get('/profil', [App\Http\Controllers\LaboranController::class , 'profil'])->name('profil');
    Route::post('/profil', [App\Http\Controllers\LaboranController::class , 'updateProfil'])->name('profil.update');

    Route::get('/lab/{id}/profil1', [App\Http\Controllers\LaboranController::class , 'profil1'])->name('profil1');
    Route::post('/profil1', [App\Http\Controllers\LaboranController::class , 'updateProfil1'])->name('profil1.update');

    // Tambahkan route ini di dalam Route::middleware(['auth', 'role:Laboran'])->prefix('laboran')->name('laboran.')->group

    // Risk Assessment (Read-Only untuk Laboran)
    Route::get('/lab/{id}/risk-assessment', [App\Http\Controllers\LaboranController::class , 'riskAssessment'])->name('risk-assessment');
    Route::get('/risk-assessment/{id}/detail', [App\Http\Controllers\LaboranController::class , 'detailRiskAssessment'])->name('risk-assessment.detail');

    // NEW: Risk Assessment Download & Notification
    Route::get('/risk-assessment/{id}/download', [App\Http\Controllers\LaboranController::class , 'downloadRiskAssessment'])->defaults('format', 'pdf')->name('risk-assessment.download');
    Route::get('/risk-assessment/{id}/download-word', [App\Http\Controllers\LaboranController::class , 'downloadRiskAssessment'])->defaults('format', 'word')->name('risk-assessment.download-word');
    Route::post('/risk-assessment/{id}/send-notification', [App\Http\Controllers\LaboranController::class , 'sendDeadlineNotification'])->name('risk-assessment.send-notification');
    Route::get('/risk-assessment/{id}/kaprodi-status', [App\Http\Controllers\LaboranController::class , 'getRiskAssessmentWithKaprodiStatus'])->name('risk-assessment.kaprodi-status');

    // Route Manajemen Alat
    Route::get('/lab/{id}/alat', [LaboranController::class , 'indexAlat'])->name('alat.index');
    Route::post('/lab/{id}/alat', [LaboranController::class , 'storeAlat'])->name('alat.store');
    Route::put('/lab/{id}/alat/{alat_id}', [LaboranController::class , 'updateAlat'])->name('alat.update');
    Route::delete('/lab/{id}/alat/{alat_id}', [LaboranController::class , 'destroyAlat'])->name('alat.destroy');

    // Pengembalian Alat
    Route::prefix('pengembalian-alat')->name('pengembalian-alat.')->group(function () {
            // List pengajuan pengembalian
            Route::get('/', [PengembalianAlatController::class , 'index'])->name('index');

            // Detail pengajuan
            Route::get('/{id}', [PengembalianAlatController::class , 'show'])->name('show');

            // Setujui pengembalian
            Route::post('/{id}/setujui', [PengembalianAlatController::class , 'setujui'])->name('setujui');

            // Tolak pengembalian
            Route::post('/{id}/tolak', [PengembalianAlatController::class , 'tolak'])->name('tolak');
        }
        );

        // Pengembalian Ruangan
        Route::prefix('pengembalian-ruangan')->name('pengembalian-ruangan.')->group(function () {
            // List pengajuan pengembalian
            Route::get('/', [PengembalianRuanganController::class , 'index'])->name('index');

            // Detail pengajuan
            Route::get('/{id}', [PengembalianRuanganController::class , 'show'])->name('show');

            // Setujui pengembalian
            Route::post('/{id}/setujui', [PengembalianRuanganController::class , 'setujui'])->name('setujui');

            // Tolak pengembalian
            Route::post('/{id}/tolak', [PengembalianRuanganController::class , 'tolak'])->name('tolak');
        }
        );
    });

// ============================================
// ROUTE DOSEN (PERLU AUTH + ROLE)
// ============================================

// Ganti bagian ROUTE DOSEN di web.php dengan ini:

// ============================================
// ROUTE DOSEN (PERLU AUTH + ROLE)
// ============================================

Route::middleware(['auth', 'role:Dosen'])->prefix('dosen')->name('dosen.')->group(function () {

    // Dashboard
    // Route::get('/dashboard', [App\Http\Controllers\Dosen\DosenController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Dosen\DosenController::class , 'dashboard'])->name('dashboard');

    // Profil
    // Route::get('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'profil'])->name('profil');
    // Route::post('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'updateProfil'])->name('profil.update');

    Route::get('/profil', [App\Http\Controllers\Dosen\DosenController::class , 'profil'])->name('profil');
    Route::post('/profil', [App\Http\Controllers\Dosen\DosenController::class , 'updateProfil'])->name('profil.update');

    // Pengajuan Penelitian
    // Route::get('/pengajuan/{id}', [App\Http\Controllers\Dosen\DosenController::class, 'detailPengajuanPenelitian'])->name('pengajuan.detail');
    // Route::post('/pengajuan/{id}/setujui', [App\Http\Controllers\Dosen\DosenController::class, 'setujuiPengajuan'])->name('pengajuan.setujui');
    // Route::post('/pengajuan/{id}/tolak', [App\Http\Controllers\Dosen\DosenController::class, 'tolakPengajuan'])->name('pengajuan.tolak');

    Route::get('/pengajuan/{id}', [App\Http\Controllers\Dosen\DosenController::class , 'detailPengajuanPenelitian'])->name('pengajuan.detail');
    Route::post('/pengajuan/{id}/setujui', [App\Http\Controllers\Dosen\DosenController::class , 'setujuiPengajuan'])->name('pengajuan.setujui');
    Route::post('/pengajuan/{id}/tolak', [App\Http\Controllers\Dosen\DosenController::class , 'tolakPengajuan'])->name('pengajuan.tolak');

    // Daftar Lab
    Route::get('/lab', [App\Http\Controllers\Dosen\DosenController::class , 'daftarLab'])->name('lab');

    // Peminjaman Ruangan
    // Route::get('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class, 'formPeminjamanRuangan'])->name('pinjam-ruangan');
    // Route::post('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class, 'storePeminjamanRuangan'])->name('pinjam-ruangan.store');

    Route::get('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class , 'formPeminjamanRuangan'])->name('pinjam-ruangan');
    Route::post('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class , 'storePeminjamanRuangan'])->name('pinjam-ruangan.store');

    // Peminjaman Alat
    // Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class, 'formPeminjamanAlat'])->name('pinjam-alat');
    // Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class, 'storePeminjamanAlat'])->name('pinjam-alat.store');

    Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class , 'formPeminjamanAlat'])->name('pinjam-alat');
    Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class , 'storePeminjamanAlat'])->name('pinjam-alat.store');

    // Pengumuman
    Route::get('/pengumuman', [App\Http\Controllers\Dosen\DosenController::class , 'pengumuman'])->name('pengumuman.index');

    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
            // Daftar RA yang perlu di-review
            Route::get('/', [DosenRiskAssessmentController::class , 'index'])->name('index');

            // Review detail RA
            Route::get('/{id}', [DosenRiskAssessmentController::class , 'show'])->name('show');

            // Approve atau reject RA
            Route::post('/{id}/approve', [DosenRiskAssessmentController::class , 'approve'])->name('approve');

            // Minta revisi
            Route::post('/{id}/request-revision', [DosenRiskAssessmentController::class , 'requestRevision'])->name('request-revision');
        }
        );
    });

Route::middleware(['auth', 'role:Safety Officer'])
    ->prefix('safety-officer')
    ->name('safety-officer.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [SafetyOfficerController::class , 'dashboard'])
            ->name('dashboard');

        // Profil
        Route::get('/profil', [SafetyOfficerController::class , 'profil'])->name('profil');
        Route::post('/profil', [SafetyOfficerController::class , 'updateProfil'])->name('profil.update');

        Route::get('/pengumuman', [SafetyOfficerController::class , 'pengumuman'])->name('pengumuman.index');

        Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {

            // ✅ LIST RA
            Route::get('/', [SafetyOfficerController::class , 'index'])
                ->name('index');

            // ✅ Jadwal wawancara list (put specific routes before {id})
            Route::get('/schedules/list', [SafetyOfficerController::class , 'schedules'])
                ->name('schedules');

            // ✅ NEW: Create schedule options (multiple jadwal)
            Route::get('/{id}/create-schedule-options', [SafetyOfficerController::class , 'showCreateScheduleOptions'])
                ->name('create-schedule-options');
            Route::post('/{id}/store-schedule-options', [SafetyOfficerController::class , 'storeScheduleOptions'])
                ->name('store-schedule-options');

            // ✅ DETAIL RA (PAKAI ID)
            Route::get('/{id}', [SafetyOfficerController::class , 'show'])
                ->name('show');

            // ✅ Jadwalkan wawancara (with ID parameter)
            Route::post('/{id}/schedule-interview', [SafetyOfficerController::class , 'scheduleInterview'])
                ->name('schedule-interview');

            // ✅ Approve / Reject (with ID parameter)
            Route::post('/{id}/approve', [SafetyOfficerController::class , 'approve'])
                ->name('approve');

            // ✅ Minta revisi (with ID parameter)
            Route::post('/{id}/request-revision', [SafetyOfficerController::class , 'requestRevision'])
                ->name('request-revision');
        }
        );
    });

Route::middleware(['auth', 'role:Kepala Laboratorium'])->prefix('kepala-lab')->name('kepala-lab.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KepalaLabController::class , 'dashboard'])->name('dashboard');

    // Profil
    Route::get('/profil', [KepalaLabController::class , 'profil'])->name('profil');
    Route::post('/profil', [KepalaLabController::class , 'updateProfil'])->name('profil.update');

    Route::get('/pengumuman', [App\Http\Controllers\KepalaLab\KepalaLabController::class , 'pengumuman'])->name('pengumuman.index');
    Route::get('/pengumuman/create', [App\Http\Controllers\KepalaLab\KepalaLabController::class , 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\KepalaLab\KepalaLabController::class , 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\KepalaLab\KepalaLabController::class , 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [App\Http\Controllers\KepalaLab\KepalaLabController::class , 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\KepalaLab\KepalaLabController::class , 'destroyPengumuman'])->name('pengumuman.destroy');

    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
            Route::get('/report', [KepalaLabController::class , 'report'])->name('report');

            // List
            Route::get('/', [KepalaLabController::class , 'index'])->name('index');

            // Review detail
            Route::get('/{id}', [KepalaLabController::class , 'show'])->name('show');

            // Approve/Reject
            Route::post('/{id}/approve', [KepalaLabController::class , 'approve'])->name('approve');

            // Request revision
            Route::post('/{id}/request-revision', [KepalaLabController::class , 'requestRevision'])->name('request-revision');

        // Report
    
        }
        );

        // NEW: Peminjaman Ruangan - Final Approval oleh Kepala Lab (changed from Kaprodi)
        Route::prefix('peminjaman-ruangan')->name('peminjaman-ruangan.')->group(function () {
            Route::get('/', [KepalaLabController::class , 'peminjamanRuanganIndex'])->name('index');
            Route::get('/{id}', [KepalaLabController::class , 'peminjamanRuanganShow'])->name('show');
            Route::post('/{id}/approve', [KepalaLabController::class , 'peminjamanRuanganApprove'])->name('approve');
            Route::post('/{id}/reject', [KepalaLabController::class , 'peminjamanRuanganReject'])->name('reject');
            Route::get('/report', [KepalaLabController::class , 'peminjamanRuanganReport'])->name('report');
        }
        );
    });

// Route::middleware(['auth', 'role:Dosen'])->prefix('dosen')->name('dosen.')->group(function () {
//     Route::get('/dashboard', [App\Http\Controllers\Dosen\DosenController::class, 'dashboard'])->name('dashboard');
//     Route::get('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'profil'])->name('profil');
//     Route::post('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'updateProfil'])->name('profil.update');
//     Route::post('/pengajuan/{id}/setujui', [App\Http\Controllers\Dosen\DosenController::class, 'setujui'])->name('setujui');
//     Route::post('/pengajuan/{id}/tolak', [App\Http\Controllers\Dosen\DosenController::class, 'tolak'])->name('tolak');
// });

// ============================================
// ROUTE MAHASISWA (PERLU AUTH + ROLE)
// ============================================

// Route::middleware(['auth', 'role:Mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
//     Route::get('/', function () {
//         return view('mahasiswa.dashboard');
//     })->name('dashboard');

//     Route::get('/lab', function () {
//         return view('mahasiswa.dashboard');
//     })->name('lab');

//     Route::get('/alat/{id}', function ($id) {
//         $lab = \App\Models\DaftarLab::with('alatLabs')->findOrFail($id);
//         return view('mahasiswa.alat', compact('lab'));
//     })->name('alat');

//     Route::get('/aktivitas/{id}', function ($id) {
//         $lab = \App\Models\DaftarLab::findOrFail($id);
//         return view('mahasiswa.aktivitas', compact('lab'));
//     })->name('aktivitas');

//     // Pinjam Ruangan
//     Route::get('/pinjam-ruangan/{id}', [App\Http\Controllers\Mahasiswa\PeminjamanRuanganController::class, 'create'])->name('pinjam-ruangan');
//     Route::post('/pinjam-ruangan/{id}', [App\Http\Controllers\Mahasiswa\PeminjamanRuanganController::class, 'store'])->name('pinjam-ruangan.store');

//     // Pengajuan Penelitian
//     Route::get('/pengajuan-penelitian/{id}', [App\Http\Controllers\Mahasiswa\PengajuanPenelitianController::class, 'create'])->name('pengajuan-penelitian');
//     Route::post('/pengajuan-penelitian/{id}', [App\Http\Controllers\Mahasiswa\PengajuanPenelitianController::class, 'store'])->name('pengajuan-penelitian.store');

//     // Pinjam Alat
//     Route::get('/pinjam-alat/{id}', [App\Http\Controllers\Mahasiswa\PeminjamanAlatController::class, 'create'])->name('pinjam-alat');
//     Route::post('/pinjam-alat/{id}', [App\Http\Controllers\Mahasiswa\PeminjamanAlatController::class, 'store'])->name('pinjam-alat.store');
// });

// ============================================
// ROUTE YANG PERLU AUTH (SEMUA ROLE)
// ============================================

// End of routes
