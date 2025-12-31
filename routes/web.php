<?php

use App\Http\Controllers\Dosen\DosenRiskAssessmentController;
use App\Http\Controllers\KepalaLab\KepalaLabController;
use App\Http\Controllers\Mahasiswa\RiskAssessmentController;
use App\Http\Controllers\SafetyOfficer\SafetyOfficerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\KelolaUserController;

use App\Http\Controllers\RegistrasiController;

// ============================================
// REGISTRATION ROUTES (Public)
// ============================================

// Show registration form
Route::get('/registrasi', [RegistrasiController::class, 'index'])->name('registrasi');

// Submit registration (send verification email)
Route::post('/registrasi', [RegistrasiController::class, 'store'])->name('registrasi.store');

// Pending verification page
Route::get('/registrasi/pending', [RegistrasiController::class, 'pending'])->name('registrasi.pending');

// Email verification (click link from email)
Route::get('/registrasi/verify/{token}', [RegistrasiController::class, 'verify'])->name('registrasi.verify');

// Registration success page
Route::get('/registrasi/success', [RegistrasiController::class, 'success'])->name('registrasi.success');

// Resend verification email
Route::post('/registrasi/resend', [RegistrasiController::class, 'resendVerification'])->name('registrasi.resend');

// Route untuk test - hapus setelah masalah solved
Route::get('/test', function () {
    return 'Test route berhasil diakses!';
});

Route::get('/test-auth', function () {
    if (Auth::check()) {
        return 'User: ' . Auth::user()->email . ' | Role: ' . Auth::user()->Role_User;
    }
    return 'Belum login';
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgotPasswordForm'])
    ->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetPasswordForm'])
    ->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

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
        return match($user->Role_User) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Dosen' => redirect()->route('dosen.dashboard'),
            'Mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            'Laboran' => redirect()->route('laboran.dashboard'),
            'Kepala Laboratorium' => redirect()->route('kepala-lab.dashboard'),
            'Safety Officer' => redirect()->route('safety-officer.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('home');

// Login & Registrasi
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/registrasi', [App\Http\Controllers\RegistrasiController::class, 'index'])->name('registrasi');
Route::post('/registrasi', [App\Http\Controllers\RegistrasiController::class, 'store'])->name('registrasi.store');

// ============================================
// ROUTE ADMIN (PERLU AUTH + ROLE:Admin)
// ============================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Kelola User Routes
    Route::get('/kelola-user', [KelolaUserController::class, 'index'])->name('kelola-user.index');
    Route::get('/kelola-user/{id}/edit', [KelolaUserController::class, 'edit'])->name('kelola-user.edit');
    Route::put('/kelola-user/{id}', [KelolaUserController::class, 'update'])->name('kelola-user.update');
    Route::delete('/kelola-user/{id}', [KelolaUserController::class, 'destroy'])->name('kelola-user.destroy');
    
    // Reset Password Routes
    Route::get('/kelola-user/{id}/reset-password', [KelolaUserController::class, 'showResetPassword'])->name('kelola-user.reset-password');
    Route::put('/kelola-user/{id}/reset-password', [KelolaUserController::class, 'resetPassword'])->name('kelola-user.reset-password.update');
    
    // Toggle Status (Optional - jika ingin fitur aktif/nonaktif user)
    Route::post('/kelola-user/{id}/toggle-status', [KelolaUserController::class, 'toggleStatus'])->name('kelola-user.toggle-status');
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin (Welcome Page)
    Route::get('/dashboard', function () {
        return view('welcome', [
            'daftar_labs' => \App\Models\DaftarLab::all(),
            'daftar_users' => \App\Models\DaftarUser::all(),
            'daftar_laborans' => \App\Models\DaftarLaboranLaboratorium::all()
        ]);
    })->name('dashboard');

    Route::prefix('tambah-user')->name('tambah-user.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\TambahUserController::class, 'index'])->name('index');
    // Route::get('/create', [App\Http\Controllers\Admin\TambahUserController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\TambahUserController::class, 'store'])->name('store');
    // Route::get('/{id}/edit', [App\Http\Controllers\Admin\TambahUserController::class, 'edit'])->name('edit');
    // Route::put('/{id}', [App\Http\Controllers\Admin\TambahUserController::class, 'update'])->name('update');
    // Route::delete('/{id}', [App\Http\Controllers\Admin\TambahUserController::class, 'destroy'])->name('destroy');

    Route::get('/kelola-user', [KelolaUserController::class, 'index'])->name('kelola-user.index');
    Route::get('/kelola-user/{id}/edit', [KelolaUserController::class, 'edit'])->name('kelola-user.edit');
    Route::put('/kelola-user/{id}', [KelolaUserController::class, 'update'])->name('kelola-user.update');
    Route::delete('/kelola-user/{id}', [KelolaUserController::class, 'destroy'])->name('kelola-user.destroy');
    
    // Reset Password Routes
    Route::get('/kelola-user/{id}/reset-password', [KelolaUserController::class, 'showResetPassword'])->name('kelola-user.reset-password');
    Route::put('/kelola-user/{id}/reset-password', [KelolaUserController::class, 'resetPassword'])->name('kelola-user.reset-password.update');
    
    // Toggle Status (Optional - jika ingin fitur aktif/nonaktif user)
    Route::post('/kelola-user/{id}/toggle-status', [KelolaUserController::class, 'toggleStatus'])->name('kelola-user.toggle-status');
});
    
    // Alat Lab
    Route::prefix('alat-lab')->name('alat-lab.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AlatLabController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AlatLabController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AlatLabController::class, 'store'])->name('store');
        Route::get('/{alat}/edit', [App\Http\Controllers\Admin\AlatLabController::class, 'edit'])->name('edit');
        Route::put('/{alat}', [App\Http\Controllers\Admin\AlatLabController::class, 'update'])->name('update');
        Route::delete('/{alat}', [App\Http\Controllers\Admin\AlatLabController::class, 'destroy'])->name('destroy');
    });
});

// Ganti bagian ROUTE MAHASISWA di web.php dengan ini:

// ============================================
// ROUTE MAHASISWA (PERLU AUTH + ROLE)
// ============================================

Route::middleware(['auth', 'role:Mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    Route::get('/profil', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'profil'])->name('profil');
    Route::post('/profil', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'updateProfil'])->name('profil.update');
    
    // Dashboard - Pilih Lab
    Route::get('/', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'dashboard', 'pengumuman'])->name('dashboard');

    // Route::get('/dashboard1', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'dashboard1', 'pengumuman'])->name('dashboard1');
    
    Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'formPeminjamanAlat'])->name('pinjam-alat');
    Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePeminjamanAlat'])->name('pinjam-alat.store');
    
    // Detail Lab - Lihat Alat yang Tersedia
    Route::get('/lab/{id}', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'detailLab'])->name('lab.detail');
    
    // Peminjaman Ruangan
    // Route::get('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'formPeminjamanRuangan'])->name('pinjam-ruangan');
    // Route::post('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePeminjamanRuangan'])->name('pinjam-ruangan.store');
    
    Route::get('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'formPeminjamanRuangan'])->name('pinjam-ruangan');
    Route::post('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePeminjamanRuangan'])->name('pinjam-ruangan.store');

    // Peminjaman Alat
    Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'formPeminjamanAlat'])->name('pinjam-alat');
    Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePeminjamanAlat'])->name('pinjam-alat.store');
    
    // Pengajuan Penelitian
    // Route::get('/lab/{id}/pengajuan-penelitian', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'formPengajuanPenelitian'])->name('pengajuan-penelitian');
    // Route::post('/lab/{id}/pengajuan-penelitian', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePengajuanPenelitian'])->name('pengajuan-penelitian.store');
    
    Route::get('/pengajuan-penelitian/{id}', [App\Http\Controllers\Mahasiswa\PengajuanPenelitianController::class, 'create'])->name('pengajuan-penelitian');
    Route::post('/pengajuan-penelitian/{id}', [App\Http\Controllers\Mahasiswa\PengajuanPenelitianController::class, 'store'])->name('pengajuan-penelitian.store');

    // Aktivitas Mahasiswa
    // Route::get('/lab/{id}/aktivitas', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'aktivitas'])->name('aktivitas');
    Route::get('/lab/{id}/aktivitas', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'aktivitas'])->name('aktivitas');
    
    // Pengumuman
    Route::get('/pengumuman', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'pengumuman'])->name('pengumuman');

    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
        // Lihat daftar RA mahasiswa
        Route::get('/', [RiskAssessmentController::class, 'index'])->name('index');
        
        // Buat RA baru dari lab tertentu
        Route::get('/lab/{labId}/create', [RiskAssessmentController::class, 'create'])->name('create');
        Route::post('/lab/{labId}/store', [RiskAssessmentController::class, 'store'])->name('store');
        
        // Lihat detail RA
        Route::get('/{id}', [RiskAssessmentController::class, 'show'])->name('show');
        
        // Edit RA (hanya jika status draft)
        Route::get('/{id}/edit', [RiskAssessmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RiskAssessmentController::class, 'update'])->name('update');
        
        // Download PDF
        Route::get('/{id}/download-pdf', [RiskAssessmentController::class, 'downloadPdf'])->name('download-pdf');
    });
});



// ============================================
// ROUTE LABORAN (PERLU AUTH + ROLE)
// ============================================

Route::middleware(['auth', 'role:Laboran'])->prefix('laboran')->name('laboran.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\LaboranController::class, 'dashboard'])->name('dashboard');
    // Route::get('/dashboard1', [App\Http\Controllers\LaboranController::class, 'dashboard1'])->name('dashboard1');
    
    // Peminjaman Ruangan
    Route::put('/ruangan/{id}/setujui', [App\Http\Controllers\LaboranController::class, 'setujuiRuangan'])->name('ruangan.setujui');
    Route::put('/ruangan/{id}/tolak', [App\Http\Controllers\LaboranController::class, 'tolakRuangan'])->name('ruangan.tolak');
    
    // Peminjaman Alat
    Route::put('/alat/{id}/setujui', [App\Http\Controllers\LaboranController::class, 'setujuiAlat'])->name('alat.setujui');
    Route::put('/alat/{id}/tolak', [App\Http\Controllers\LaboranController::class, 'tolakAlat'])->name('alat.tolak');
    Route::put('/alat/{id}/kembalikan', [App\Http\Controllers\LaboranController::class, 'kembalikanAlat'])->name('alat.kembalikan');
    
    // Pengajuan Penelitian
    Route::get('/penelitian/{id}', [App\Http\Controllers\LaboranController::class, 'detailPenelitian'])->name('penelitian.detail');
    Route::put('/penelitian/{id}/setujui', [App\Http\Controllers\LaboranController::class, 'setujuiPenelitian'])->name('penelitian.setujui');
    Route::put('/penelitian/{id}/tolak', [App\Http\Controllers\LaboranController::class, 'tolakPenelitian'])->name('penelitian.tolak');
    
    // Pengumuman
    Route::get('/pengumuman/create', [App\Http\Controllers\LaboranController::class, 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\LaboranController::class, 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\LaboranController::class, 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class, 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class, 'destroyPengumuman'])->name('pengumuman.destroy');

    // Peminjaman Ruangan - Halaman Terpisah
    Route::get('/lab/{id}/peminjaman-ruangan', [App\Http\Controllers\LaboranController::class, 'peminjamanRuangan'])->name('peminjaman-ruangan');
    Route::put('/ruangan/{id}/setujui', [App\Http\Controllers\LaboranController::class, 'setujuiRuangan'])->name('ruangan.setujui');
    Route::put('/ruangan/{id}/tolak', [App\Http\Controllers\LaboranController::class, 'tolakRuangan'])->name('ruangan.tolak');
     
    // Peminjaman Alat - Halaman Terpisah
    Route::get('/lab/{id}/peminjaman-alat', [App\Http\Controllers\LaboranController::class, 'peminjamanAlat'])->name('peminjaman-alat');
    Route::put('/alat/{id}/setujui', [App\Http\Controllers\LaboranController::class, 'setujuiAlat'])->name('alat.setujui');
    Route::put('/alat/{id}/tolak', [App\Http\Controllers\LaboranController::class, 'tolakAlat'])->name('alat.tolak');
    Route::put('/alat/{id}/kembalikan', [App\Http\Controllers\LaboranController::class, 'kembalikanAlat'])->name('alat.kembalikan');
    
    // Pengajuan Penelitian - Halaman Terpisah
    Route::get('/lab/{id}/pengajuan-penelitian', [App\Http\Controllers\LaboranController::class, 'pengajuanPenelitian'])->name('pengajuan-penelitian');
    Route::get('/penelitian/{id}', [App\Http\Controllers\LaboranController::class, 'detailPenelitian'])->name('penelitian.detail');
    Route::put('/penelitian/{id}/setujui', [App\Http\Controllers\LaboranController::class, 'setujuiPenelitian'])->name('penelitian.setujui');
    Route::put('/penelitian/{id}/tolak', [App\Http\Controllers\LaboranController::class, 'tolakPenelitian'])->name('penelitian.tolak');
    
    // Kelola Pengumuman - Halaman Terpisah
    Route::get('/lab/{id}/pengumuman', [App\Http\Controllers\LaboranController::class, 'pengumuman'])->name('pengumuman');
    Route::get('/pengumuman/create', [App\Http\Controllers\LaboranController::class, 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\LaboranController::class, 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\LaboranController::class, 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class, 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\LaboranController::class, 'destroyPengumuman'])->name('pengumuman.destroy');

    Route::get('/lab/{id}/profil1', [App\Http\Controllers\LaboranController::class, 'profil1'])->name('profil1');
    Route::post('/profil1', [App\Http\Controllers\LaboranController::class, 'updateProfil1'])->name('profil1.update');

    // Tambahkan route ini di dalam Route::middleware(['auth', 'role:Laboran'])->prefix('laboran')->name('laboran.')->group

// Risk Assessment (Read-Only untuk Laboran)
Route::get('/lab/{id}/risk-assessment', [App\Http\Controllers\LaboranController::class, 'riskAssessment'])->name('risk-assessment');
Route::get('/risk-assessment/{id}/detail', [App\Http\Controllers\LaboranController::class, 'detailRiskAssessment'])->name('risk-assessment.detail');
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
    Route::get('/dashboard', [App\Http\Controllers\Dosen\DosenController::class, 'dashboard'])->name('dashboard');
    
    // Profil
    // Route::get('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'profil'])->name('profil');
    // Route::post('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'updateProfil'])->name('profil.update');

    Route::get('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'profil'])->name('profil');
    Route::post('/profil', [App\Http\Controllers\Dosen\DosenController::class, 'updateProfil'])->name('profil.update');
    
    // Pengajuan Penelitian
    // Route::get('/pengajuan/{id}', [App\Http\Controllers\Dosen\DosenController::class, 'detailPengajuanPenelitian'])->name('pengajuan.detail');
    // Route::post('/pengajuan/{id}/setujui', [App\Http\Controllers\Dosen\DosenController::class, 'setujuiPengajuan'])->name('pengajuan.setujui');
    // Route::post('/pengajuan/{id}/tolak', [App\Http\Controllers\Dosen\DosenController::class, 'tolakPengajuan'])->name('pengajuan.tolak');
    
    Route::get('/pengajuan/{id}', [App\Http\Controllers\Dosen\DosenController::class, 'detailPengajuanPenelitian'])->name('pengajuan.detail');
    Route::post('/pengajuan/{id}/setujui', [App\Http\Controllers\Dosen\DosenController::class, 'setujuiPengajuan'])->name('pengajuan.setujui');
    Route::post('/pengajuan/{id}/tolak', [App\Http\Controllers\Dosen\DosenController::class, 'tolakPengajuan'])->name('pengajuan.tolak');
    

    // Daftar Lab
    Route::get('/lab', [App\Http\Controllers\Dosen\DosenController::class, 'daftarLab'])->name('lab');
    
    // Peminjaman Ruangan
    // Route::get('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class, 'formPeminjamanRuangan'])->name('pinjam-ruangan');
    // Route::post('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class, 'storePeminjamanRuangan'])->name('pinjam-ruangan.store');
    
    Route::get('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class, 'formPeminjamanRuangan'])->name('pinjam-ruangan');
    Route::post('/lab/{id}/pinjam-ruangan', [App\Http\Controllers\Dosen\DosenController::class, 'storePeminjamanRuangan'])->name('pinjam-ruangan.store');
    

    // Peminjaman Alat
    // Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class, 'formPeminjamanAlat'])->name('pinjam-alat');
    // Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class, 'storePeminjamanAlat'])->name('pinjam-alat.store');
    
    Route::get('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class, 'formPeminjamanAlat'])->name('pinjam-alat');
    Route::post('/lab/{id}/pinjam-alat', [App\Http\Controllers\Dosen\DosenController::class, 'storePeminjamanAlat'])->name('pinjam-alat.store');
   

    // Pengumuman
    // Route::get('/pengumuman', [App\Http\Controllers\Dosen\DosenController::class, 'pengumuman'])->name('pengumuman.index');
    // Route::get('/pengumuman/create', [App\Http\Controllers\Dosen\DosenController::class, 'createPengumuman'])->name('pengumuman.create');
    // Route::post('/pengumuman', [App\Http\Controllers\Dosen\DosenController::class, 'storePengumuman'])->name('pengumuman.store');
    // Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\Dosen\DosenController::class, 'editPengumuman'])->name('pengumuman.edit');
    // Route::put('/pengumuman/{id}', [App\Http\Controllers\Dosen\DosenController::class, 'updatePengumuman'])->name('pengumuman.update');
    // Route::delete('/pengumuman/{id}', [App\Http\Controllers\Dosen\DosenController::class, 'destroyPengumuman'])->name('pengumuman.destroy');

    Route::get('/pengumuman', [App\Http\Controllers\Dosen\DosenController::class, 'pengumuman'])->name('pengumuman.index');
    Route::get('/pengumuman/create', [App\Http\Controllers\Dosen\DosenController::class, 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\Dosen\DosenController::class, 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\Dosen\DosenController::class, 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [App\Http\Controllers\Dosen\DosenController::class, 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\Dosen\DosenController::class, 'destroyPengumuman'])->name('pengumuman.destroy');

    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
        // Daftar RA yang perlu di-review
        Route::get('/', [DosenRiskAssessmentController::class, 'index'])->name('index');
        
        // Review detail RA
        Route::get('/{id}', [DosenRiskAssessmentController::class, 'show'])->name('show');
        
        // Approve atau reject RA
        Route::post('/{id}/approve', [DosenRiskAssessmentController::class, 'approve'])->name('approve');
        
        // Minta revisi
        Route::post('/{id}/request-revision', [DosenRiskAssessmentController::class, 'requestRevision'])->name('request-revision');
    });
});

Route::middleware(['auth', 'role:Safety Officer'])
    ->prefix('safety-officer')
    ->name('safety-officer.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [SafetyOfficerController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/pengumuman', [SafetyOfficerController::class, 'pengumuman'])->name('pengumuman.index');
    Route::get('/pengumuman/create', [SafetyOfficerController::class, 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [SafetyOfficerController::class, 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [SafetyOfficerController::class, 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [SafetyOfficerController::class, 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [SafetyOfficerController::class, 'destroyPengumuman'])->name('pengumuman.destroy');

    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {

        // ✅ LIST RA
        Route::get('/', [SafetyOfficerController::class, 'index'])
            ->name('index');

        // ✅ Jadwal wawancara list (put specific routes before {id})
        Route::get('/schedules/list', [SafetyOfficerController::class, 'schedules'])
            ->name('schedules');

        // ✅ DETAIL RA (PAKAI ID)
        Route::get('/{id}', [SafetyOfficerController::class, 'show'])
            ->name('show');

        // ✅ Jadwalkan wawancara (with ID parameter)
        Route::post('/{id}/schedule-interview', [SafetyOfficerController::class, 'scheduleInterview'])
            ->name('schedule-interview');

        // ✅ Approve / Reject (with ID parameter)
        Route::post('/{id}/approve', [SafetyOfficerController::class, 'approve'])
            ->name('approve');

        // ✅ Minta revisi (with ID parameter)
        Route::post('/{id}/request-revision', [SafetyOfficerController::class, 'requestRevision'])
            ->name('request-revision');
    });
});


Route::middleware(['auth', 'role:Kepala Laboratorium'])->prefix('kepala-lab')->name('kepala-lab.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KepalaLabController::class, 'dashboard'])->name('dashboard');

    Route::get('/pengumuman', [App\Http\Controllers\KepalaLab\KepalaLabController::class, 'pengumuman'])->name('pengumuman.index');
    Route::get('/pengumuman/create', [App\Http\Controllers\KepalaLab\KepalaLabController::class, 'createPengumuman'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\KepalaLab\KepalaLabController::class, 'storePengumuman'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\KepalaLab\KepalaLabController::class, 'editPengumuman'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [App\Http\Controllers\KepalaLab\KepalaLabController::class, 'updatePengumuman'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\KepalaLab\KepalaLabController::class, 'destroyPengumuman'])->name('pengumuman.destroy');

    
    Route::prefix('risk-assessment')->name('risk-assessment.')->group(function () {
        Route::get('/report', [KepalaLabController::class, 'report'])->name('report');
        
        // List
        Route::get('/', [KepalaLabController::class, 'index'])->name('index');
        
        // Review detail
        Route::get('/{id}', [KepalaLabController::class, 'show'])->name('show');
        
        // Approve/Reject
        Route::post('/{id}/approve', [KepalaLabController::class, 'approve'])->name('approve');
        
        // Request revision
        Route::post('/{id}/request-revision', [KepalaLabController::class, 'requestRevision'])->name('request-revision');
        
        // Report
        
    });
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
// ROUTE ADMIN (PERLU AUTH + ROLE:Admin)
// ============================================

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Alat Lab
    Route::prefix('alat-lab')->name('alat-lab.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AlatLabController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AlatLabController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AlatLabController::class, 'store'])->name('store');
        Route::get('/{alat}/edit', [App\Http\Controllers\Admin\AlatLabController::class, 'edit'])->name('edit');
        Route::put('/{alat}', [App\Http\Controllers\Admin\AlatLabController::class, 'update'])->name('update');
        Route::delete('/{alat}', [App\Http\Controllers\Admin\AlatLabController::class, 'destroy'])->name('destroy');
    });
});

// ============================================
// ROUTE YANG PERLU AUTH (SEMUA ROLE)
// ============================================

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
    });
    
    // Activity Log
    Route::get('/aktivitas-administrator', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('aktivitas-administrator');
    
    // Pengumuman
    Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
        Route::get('/', [App\Http\Controllers\PengumumanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\PengumumanController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PengumumanController::class, 'store'])->name('store');
        Route::get('/{pengumuman}/edit', [App\Http\Controllers\PengumumanController::class, 'edit'])->name('edit');
        Route::put('/{pengumuman}', [App\Http\Controllers\PengumumanController::class, 'update'])->name('update');
        Route::delete('/{pengumuman}', [App\Http\Controllers\PengumumanController::class, 'destroy'])->name('destroy');
    });
});

// ============================================
// ROUTE DEMO/DEVELOPMENT (HAPUS DI PRODUCTION!)
// ============================================

// Daftar Lab
Route::prefix('daftar-lab')->name('daftar-lab.')->group(function () {
    Route::get('/', [App\Http\Controllers\DaftarLabController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\DaftarLabController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\DaftarLabController::class, 'store'])->name('store');
    Route::get('/{lab}/edit', [App\Http\Controllers\DaftarLabController::class, 'edit'])->name('edit');
    Route::put('/{lab}', [App\Http\Controllers\DaftarLabController::class, 'update'])->name('update');
    Route::delete('/{lab}', [App\Http\Controllers\DaftarLabController::class, 'destroy'])->name('destroy');
});

// Tambah Laboran
Route::prefix('tambah-laboran')->name('tambah-laboran.')->group(function () {
    Route::get('/', [App\Http\Controllers\TambahLaboranController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\TambahLaboranController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\TambahLaboranController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [App\Http\Controllers\TambahLaboranController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\TambahLaboranController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\TambahLaboranController::class, 'destroy'])->name('destroy');
});

Route::post('/update-role', [App\Http\Controllers\RoleController::class, 'updateRole'])->name('update.role');