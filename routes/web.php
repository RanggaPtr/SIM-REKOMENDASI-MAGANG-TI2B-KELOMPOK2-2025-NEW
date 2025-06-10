<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LowonganMagangController;
use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PengajuanMagangController as AdminPengajuanMagangController; // Tambahkan alias
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\MonitoringMagangController;
use App\Http\Controllers\Dosen\SertifikatDosenController as DosenSertifikatDosenController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\FeedbackController;
use App\Http\Controllers\Mahasiswa\LogAktivitasController;
use App\Http\Controllers\Mahasiswa\PengajuanMagangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikatDosenController;
use App\Models\EvaluasiMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file defines the web routes for the application. Routes are loaded
| by the RouteServiceProvider within the "web" middleware group.
|
*/

// Public Routes
Route::group(['name' => 'public.'], function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');
    Route::get('/mitra-ajax', [LandingPageController::class, 'mitraAjax'])->name('mitra-ajax');
});

// Authentication Routes
Route::group(['name' => 'auth.'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/postlogin', [AuthController::class, 'postlogin'])->name('postlogin');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/postregister', [AuthController::class, 'postregister'])->name('postregister');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Authenticated Routes
Route::group(['middleware' => 'auth'], function () {
    // Profile Update
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Home Redirect Based on Role
    Route::get('/home', function () {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            case 'perusahaan':
                return redirect()->route('perusahaan.dashboard');
            default:
                return redirect()->route('login')->with('error', 'Invalid user role');
        }
    })->name('home');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('authorize:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Tambahkan route show untuk lowongan magang di group admin management-lowongan-magang
        Route::prefix('management-lowongan-magang')->group(function () {
            Route::get('/', [LowonganMagangController::class, 'index'])->name('lowongan.index');
            Route::get('/create', [LowonganMagangController::class, 'create'])->name('lowongan.create');
            Route::post('/', [LowonganMagangController::class, 'store'])->name('lowongan.store');
            Route::get('/{id}', [LowonganMagangController::class, 'show'])->name('lowongan.show'); // <-- Tambahkan ini
            Route::get('/{id}/edit', [LowonganMagangController::class, 'edit'])->name('lowongan.edit');
            Route::put('/{id}', [LowonganMagangController::class, 'update'])->name('lowongan.update');
            Route::delete('/{id}', [LowonganMagangController::class, 'destroy'])->name('lowongan.destroy');
        });

        // Manajemen Pengajuan Magang 
        Route::prefix('management-pengajuan-magang')->name('pengajuan.')->group(function () {
            Route::get('/', [AdminPengajuanMagangController::class, 'index'])->name('index');
            Route::post('/list', [AdminPengajuanMagangController::class, 'list'])->name('list');
            Route::get('/{id}/show_ajax', [AdminPengajuanMagangController::class, 'show_ajax'])->name('show_ajax');
            Route::get('/{id}/edit_ajax', [AdminPengajuanMagangController::class, 'edit_ajax'])->name('edit_ajax');
            Route::put('/{id}/update_ajax', [AdminPengajuanMagangController::class, 'update_ajax'])->name('update_ajax');
            Route::get('/{id}/delete_ajax', [AdminPengajuanMagangController::class, 'confirm_ajax'])->name('confirm_ajax');
            Route::delete('/{id}/delete_ajax', [AdminPengajuanMagangController::class, 'delete_ajax'])->name('delete_ajax');
            Route::get('/export_excel', [AdminPengajuanMagangController::class, 'export_excel'])->name('export_excel');
            Route::get('/export_pdf', [AdminPengajuanMagangController::class, 'export_pdf'])->name('export_pdf');
        });
        
        // Manajemen Pengguna
        Route::prefix('management-pengguna')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/list', [UserController::class, 'list'])->name('list');
            Route::get('/create_ajax', [UserController::class, 'create_ajax'])->name('create_ajax');
            Route::post('/ajax', [UserController::class, 'store_ajax'])->name('store_ajax');
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax'])->name('show_ajax');
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax'])->name('edit_ajax');
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax'])->name('update_ajax');
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax'])->name('confirm_ajax');
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax'])->name('delete_ajax');
            Route::get('/import', [UserController::class, 'import'])->name('import');
            Route::post('/import_ajax', [UserController::class, 'import_ajax'])->name('import_ajax');
            Route::get('/export_excel', [UserController::class, 'export_excel'])->name('export_excel');
            Route::get('/export_pdf', [UserController::class, 'export_pdf'])->name('export_pdf');
        });

        // Manajemen Program Studi
        Route::prefix('management-prodi')->name('programstudi.')->group(function () {
            Route::get('/', [ProgramStudiController::class, 'index'])->name('index');
            Route::post('/list', [ProgramStudiController::class, 'list'])->name('list');
            Route::get('/create_ajax', [ProgramStudiController::class, 'create_ajax'])->name('create_ajax');
            Route::post('/ajax', [ProgramStudiController::class, 'store_ajax'])->name('store_ajax');
            Route::get('/{id}/show_ajax', [ProgramStudiController::class, 'show_ajax'])->name('show_ajax');
            Route::get('/{id}/edit_ajax', [ProgramStudiController::class, 'edit_ajax'])->name('edit_ajax');
            Route::put('/{id}/update_ajax', [ProgramStudiController::class, 'update_ajax'])->name('update_ajax');
            Route::get('/{id}/delete_ajax', [ProgramStudiController::class, 'confirm_ajax'])->name('confirm_ajax');
            Route::delete('/{id}/delete_ajax', [ProgramStudiController::class, 'delete_ajax'])->name('delete_ajax');
            Route::get('/import', [ProgramStudiController::class, 'import'])->name('import');
            Route::post('/import_ajax', [ProgramStudiController::class, 'import_ajax'])->name('import_ajax');
            Route::get('/export_excel', [ProgramStudiController::class, 'export_excel'])->name('export_excel');
            Route::get('/export_pdf', [ProgramStudiController::class, 'export_pdf'])->name('export_pdf');
        });

        // Manajemen Periode Magang
        Route::prefix('management-periode-magang')->name('periode.')->group(function () {
            Route::get('/', [PeriodeMagangController::class, 'index'])->name('index');
            Route::post('/list', [PeriodeMagangController::class, 'list'])->name('list');
            Route::get('/create_ajax', [PeriodeMagangController::class, 'create_ajax'])->name('create_ajax');
            Route::post('/ajax', [PeriodeMagangController::class, 'store_ajax'])->name('store_ajax');
            Route::get('/{id}/show_ajax', [PeriodeMagangController::class, 'show_ajax'])->name('show_ajax');
            Route::get('/{id}/edit_ajax', [PeriodeMagangController::class, 'edit_ajax'])->name('edit_ajax');
            Route::put('/{id}/update_ajax', [PeriodeMagangController::class, 'update_ajax'])->name('update_ajax');
            Route::get('/{id}/delete_ajax', [PeriodeMagangController::class, 'confirm_ajax'])->name('confirm_ajax');
            Route::delete('/{id}/delete_ajax', [PeriodeMagangController::class, 'delete_ajax'])->name('delete_ajax');
            Route::get('/import', [PeriodeMagangController::class, 'import'])->name('import');
            Route::post('/import_ajax', [PeriodeMagangController::class, 'import_ajax'])->name('import_ajax');
            Route::get('/export_excel', [PeriodeMagangController::class, 'export_excel'])->name('export_excel');
            Route::get('/export_pdf', [PeriodeMagangController::class, 'export_pdf'])->name('export_pdf');
        });

        // Manajemen Perusahaan Mitra
        Route::prefix('management-mitra')->name('perusahaan.')->group(function () {
            Route::get('/', [PerusahaanController::class, 'index'])->name('index');
            Route::post('/list', [PerusahaanController::class, 'list'])->name('list');
            Route::get('/create_ajax', [PerusahaanController::class, 'create_ajax'])->name('create_ajax');
            Route::post('/ajax', [PerusahaanController::class, 'store_ajax'])->name('store_ajax');
            Route::get('/{id}/show_ajax', [PerusahaanController::class, 'show_ajax'])->name('show_ajax');
            Route::get('/{id}/edit_ajax', [PerusahaanController::class, 'edit_ajax'])->name('edit_ajax');
            Route::put('/{id}/update_ajax', [PerusahaanController::class, 'update_ajax'])->name('update_ajax');
            Route::get('/{id}/delete_ajax', [PerusahaanController::class, 'confirm_ajax'])->name('confirm_ajax');
            Route::delete('/{id}/delete_ajax', [PerusahaanController::class, 'delete_ajax'])->name('delete_ajax');
            Route::get('/import', [PerusahaanController::class, 'import'])->name('import');
            Route::post('/import_ajax', [PerusahaanController::class, 'import_ajax'])->name('import_ajax');
            Route::get('/export_excel', [PerusahaanController::class, 'export_excel'])->name('export_excel');
            Route::get('/export_pdf', [PerusahaanController::class, 'export_pdf'])->name('export_pdf');
        });
    });

    // Dosen Routes
    Route::prefix('dosen')->name('dosen.')->middleware('authorize:dosen')->group(function () {
        // Dashboard dosen
        Route::get('/dashboard', fn() => view('roles.dosen.dashboard', ['activeMenu' => 'Dashboard']))->name('dashboard');

        // route index monitoring mahasiswa
        Route::get('/monitoring-mahasiswa', [MonitoringMagangController::class, 'index'])->name('monitoring.mahasiswa');

        // route show detail mahasiswa dan log aktivitas
        Route::get('/monitoring-mahasiswa/{pengajuanId}', [MonitoringMagangController::class, 'show'])->name('monitoring.show');

        // route untuk simpan feedback dosen
        Route::post('/monitoring-mahasiswa/feedback/{logId}', [MonitoringMagangController::class, 'storeFeedback'])->name('monitoring.feedback.store');

        // CRUD Sertifikat Dosen
        Route::get('/sertifikat', [\App\Http\Controllers\Dosen\SertifikatDosenController::class, 'index'])->name('sertifikat.index');
        Route::get('/sertifikat/create', [\App\Http\Controllers\Dosen\SertifikatDosenController::class, 'create'])->name('sertifikat.create');
        Route::post('/sertifikat', [\App\Http\Controllers\Dosen\SertifikatDosenController::class, 'store'])->name('sertifikat.store');
        Route::get('/sertifikat/{id}/edit', [\App\Http\Controllers\Dosen\SertifikatDosenController::class, 'edit'])->name('sertifikat.edit');
        Route::put('/sertifikat/{id}', [\App\Http\Controllers\Dosen\SertifikatDosenController::class, 'update'])->name('sertifikat.update');
        Route::delete('/sertifikat/{id}', [\App\Http\Controllers\Dosen\SertifikatDosenController::class, 'destroy'])->name('sertifikat.destroy');
    });


    // Mahasiswa Routes
    Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('authorize:mahasiswa')->group(function () {
        // Dashboard
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::post('/getLowonganDetail', [MahasiswaDashboardController::class, 'getLowonganDetail'])->name('mahasiswa.getLowonganDetail');
        Route::post('/getLowongan', [MahasiswaDashboardController::class, 'getLowongan'])->name('mahasiswa.getLowongan');

        // Bookmark routes (inside mahasiswa group)
        Route::post('/bookmark', [MahasiswaDashboardController::class, 'addBookmark'])->name('mahasiswa.bookmark.add');
        Route::delete('/bookmark', [MahasiswaDashboardController::class, 'removeBookmark'])->name('mahasiswa.bookmark.remove');

        // Log Harian

   Route::prefix('log-harian')->name('log-harian.')->group(function () {
    Route::get('/', [LogAktivitasController::class, 'index'])->name('index');
    Route::get('/list', [LogAktivitasController::class, 'list'])->name('list');
    Route::get('/create', [LogAktivitasController::class, 'create'])->name('create'); // Tambahkan ini
    Route::get('/create_ajax', [LogAktivitasController::class, 'create_ajax'])->name('create_ajax');
    Route::post('/store', [LogAktivitasController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [LogAktivitasController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LogAktivitasController::class, 'update'])->name('update');
    Route::delete('/{id}', [LogAktivitasController::class, 'destroy'])->name('destroy');
    Route::get('/check-status', [LogAktivitasController::class, 'checkStatus'])->name('check-status');
    Route::get('/mahasiswa/log-harian/{id}/feedback', [LogAktivitasController::class, 'showFeedback'])->name('mahasiswa.log-harian.feedback');
});


        // Pengajuan Magang
        Route::prefix('/pengajuan-magang')->group(function () {
            Route::get('/', [PengajuanMagangController::class, 'index'])->name('pengajuan-magang.index');
            Route::post('/list', [PengajuanMagangController::class, 'list'])->name('pengajuan-magang.list');
            Route::post('/pengajuan-magang', [PengajuanMagangController::class, 'store'])->name('pengajuan-magang.store');
            // AJAX routes
            // Route::post('/', [PengajuanMagangController::class, 'store'])->name('pengajuan-magang.store');
            Route::get('/{pengajuan_id}/show_ajax', [PengajuanMagangController::class, 'show_ajax'])->name('pengajuan-magang.show_ajax');
            Route::get('/{pengajuan_id}/confirm_ajax', [PengajuanMagangController::class, 'confirm_ajax'])->name('pengajuan-magang.confirm_ajax');
            Route::delete('/{pengajuan_id}', [PengajuanMagangController::class, 'destroy_ajax'])->name('pengajuan-magang.destroy_ajax');
        });

        // Sertifikat & Feedback
        Route::get('/sertifikat', fn() => view('roles.mahasiswa.sertifikat', ['activeMenu' => 'sertifikasiFeedback']))->name('sertifikat');

        Route::get('/feedback', fn() => view('roles.mahasiswa.feedback', ['activeMenu' => 'sertifikasiFeedback']))->name('feedback');
        // routes/web.php

        // Dalam group route mahasiswa

        // ... route lainnya
        // Feedback
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    });

    // Perusahaan Routes
    Route::prefix('perusahaan')->name('perusahaan.')->middleware('authorize:perusahaan')->group(function () {
        Route::get('/dashboard', fn() => view('roles.perusahaan.dashboard', ['activeMenu' => 'dashboard']))->name('dashboard');
    });

    Route::middleware(['auth'])->group(function () {
        // ...route lain...
        Route::get('/profile/notifikasi', [\App\Http\Controllers\ProfileController::class, 'getMahasiswaNotifikasi'])->name('profile.notifikasi');
        Route::delete('/profile/notifikasi/{id}', [\App\Http\Controllers\ProfileController::class, 'deleteMahasiswaNotifikasi'])->name('profile.notifikasi.delete');
        Route::delete('/profile/notifikasi/all', [ProfileController::class, 'deleteAllMahasiswaNotifikasi'])->name('profile.notifikasi.deleteAll');
    });
});
