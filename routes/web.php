<?php

use App\Http\Controllers\Admin\LowonganMagangController;
use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\EvaluasiMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');
Route::get('/mitra-ajax', [LandingPageController::class, 'mitraAjax'])->name('landing-page.mitra');

// Authentication Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/postlogin', [AuthController::class, 'postlogin'])->name('postlogin');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/postregister', [AuthController::class, 'postregister'])->name('postregister');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

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
                return redirect()->route('login');
        }
    })->name('home');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('authorize:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', fn() => view('roles.admin.dashboard', ['activeMenu' => 'dashboard']))->name('dashboard');

        // Manajemen Lowongan Magang
        Route::prefix('management-lowongan-magang')->group(function () {
            Route::get('/', [LowonganMagangController::class, 'index'])->name('lowongan.index');
            Route::get('/create', [LowonganMagangController::class, 'create'])->name('lowongan.create');
            Route::post('/', [LowonganMagangController::class, 'store'])->name('lowongan.store');
            Route::get('/{id}/edit', [LowonganMagangController::class, 'edit'])->name('lowongan.edit');
            Route::put('/{id}', [LowonganMagangController::class, 'update'])->name('lowongan.update');
            Route::delete('/{id}', [LowonganMagangController::class, 'destroy'])->name('lowongan.destroy');
        });

        // Manajemen Pengajuan Magang (Placeholder)
        Route::prefix('management-pengajuan-magang')->group(function () {
            Route::get('/', fn() => view('roles.admin.management-pengajuan-magang.index', ['activeMenu' => 'manajemenMagang']))->name('pengajuan.index');
            // Tambahkan rute lain seperti create, store, dll. jika diperlukan
        });

        // Manajemen Pengguna
        Route::prefix('management-pengguna')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::post('/list', [UserController::class, 'list'])->name('user.list');
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);
            Route::post('/ajax', [UserController::class, 'store_ajax']);
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
            Route::get('/import', [UserController::class, 'import']);
            Route::post('/import_ajax', [UserController::class, 'import_ajax']);
            Route::get('/export_excel', [UserController::class, 'export_excel']);
            Route::get('/export_pdf', [UserController::class, 'export_pdf']);
        });

        // Manajemen Program Studi
        Route::prefix('management-prodi')->group(function () {
            Route::get('/', [ProgramStudiController::class, 'index'])->name('programstudi.index');
            Route::post('/list', [ProgramStudiController::class, 'list'])->name('programstudi.list');
            Route::get('/create_ajax', [ProgramStudiController::class, 'create_ajax']);
            Route::post('/ajax', [ProgramStudiController::class, 'store_ajax']);
            Route::get('/{id}/show_ajax', [ProgramStudiController::class, 'show_ajax']);
            Route::get('/{id}/edit_ajax', [ProgramStudiController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [ProgramStudiController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [ProgramStudiController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [ProgramStudiController::class, 'delete_ajax']);
            Route::get('/import', [ProgramStudiController::class, 'import']);
            Route::post('/import_ajax', [ProgramStudiController::class, 'import_ajax']);
            Route::get('/export_excel', [ProgramStudiController::class, 'export_excel']);
            Route::get('/export_pdf', [ProgramStudiController::class, 'export_pdf']);
        });

        // Manajemen Periode Magang
        Route::prefix('management-periode-magang')->group(function () {
            Route::get('/', [PeriodeMagangController::class, 'index'])->name('periode.index');
            Route::post('/list', [PeriodeMagangController::class, 'list'])->name('periode.list');
            Route::get('/create_ajax', [PeriodeMagangController::class, 'index']);
            Route::post('/ajax', [PeriodeMagangController::class, 'store_ajax']);
            Route::get('/{id}/show_ajax', [PeriodeMagangController::class, 'show_ajax']);
            Route::get('/{id}/edit_ajax', [PeriodeMagangController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [PeriodeMagangController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [PeriodeMagangController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [PeriodeMagangController::class, 'delete_ajax']);
            Route::get('/import', [PeriodeMagangController::class, 'import']);
            Route::post('/import_ajax', [PeriodeMagangController::class, 'import_ajax']);
            Route::get('/export_excel', [PeriodeMagangController::class, 'export_excel']);
            Route::get('/export_pdf', [PeriodeMagangController::class, 'export_pdf']);
        });

        // Manajemen Perusahaan Mitra
        Route::prefix('management-mitra')->group(function () {
            Route::get('/', [PerusahaanController::class, 'index'])->name('perusahaan.index');
            Route::post('/list', [PerusahaanController::class, 'list'])->name('perusahaan.list');
            Route::get('/create_ajax', [PerusahaanController::class, 'create_ajax']);
            Route::post('/ajax', [PerusahaanController::class, 'store_ajax']);
            Route::get('/{id}/show_ajax', [PerusahaanController::class, 'show_ajax']);
            Route::get('/{id}/edit_ajax', [PerusahaanController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [PerusahaanController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [PerusahaanController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [PerusahaanController::class, 'delete_ajax']);
            Route::get('/import', [PerusahaanController::class, 'import']);
            Route::post('/import_ajax', [PerusahaanController::class, 'import_ajax']);
            Route::get('/export_excel', [PerusahaanController::class, 'export_excel']);
            Route::get('/export_pdf', [PerusahaanController::class, 'export_pdf']);
        });

        // Statistik Tren (Placeholder)
        Route::prefix('statistik-data-tren')->name('statistik-data-tren.')->group(function () {
            Route::get('/', [StatistikController::class, 'index'])->name('index');
        });
    });

    // Dosen Routes
    Route::prefix('dosen')->name('dosen.')->middleware('authorize:dosen')->group(function () {
        Route::get('/dashboard', fn() => view('roles.dosen.dashboard', ['activeMenu' => 'dashboard']))->name('dashboard');
        Route::get('/monitoring-mahasiswa', fn() => view('roles.dosen.monitoring-mahasiswa', ['activeMenu' => 'monitoringMahasiswa']))->name('monitoring.mahasiswa');

        // Evaluasi Magang
        Route::prefix('evaluasi-magang')->group(function () {
            // Index: Menampilkan daftar evaluasi magang
            Route::get('/', function () {
                $evaluasiMagangList = EvaluasiMagangModel::with('pengajuan')->get();
                return view('roles.dosen.evaluasi-magang', [
                    'activeMenu' => 'evaluasiMagang',
                    'evaluasiMagangList' => $evaluasiMagangList
                ]);
            })->name('evaluasi-magang');

            // Create: Form untuk membuat evaluasi baru
            Route::get('/create', function () {
                return view('roles.dosen.evaluasi-magang-form', [
                    'activeMenu' => 'evaluasiMagang',
                    'isEdit' => false
                ]);
            })->name('evaluasi-magang.create');

            // Store: Menyimpan evaluasi baru
            Route::post('/', function (Request $request) {
                $request->validate([
                    'pengajuan_id' => 'required|exists:t_pengajuan_magang,pengajuan_id',
                    'nilai' => 'required|numeric|min:0|max:100',
                    'komentar' => 'required|string'
                ]);

                EvaluasiMagangModel::create([
                    'pengajuan_id' => $request->pengajuan_id,
                    'nilai' => $request->nilai,
                    'komentar' => $request->komentar,
                ]);

                return redirect()->route('dosen.evaluasi-magang')
                    ->with('success', 'Data evaluasi magang berhasil ditambahkan');
            })->name('evaluasi-magang.store');

            // Show: Menampilkan detail evaluasi
            Route::get('/{evaluasi_magang_id}', function ($evaluasi_magang_id) {
                $evaluasi = EvaluasiMagangModel::with('pengajuan')->findOrFail($evaluasi_magang_id);
                return view('roles.dosen.evaluasi-magang-detail', [
                    'activeMenu' => 'evaluasiMagang',
                    'evaluasi' => $evaluasi
                ]);
            })->name('evaluasi-magang.show');

            // Edit: Form untuk mengedit evaluasi
            Route::get('/{evaluasi_magang_id}/edit', function ($evaluasi_magang_id) {
                $evaluasi = EvaluasiMagangModel::findOrFail($evaluasi_magang_id);
                return view('roles.dosen.evaluasi-magang-form', [
                    'activeMenu' => 'evaluasiMagang',
                    'evaluasi' => $evaluasi,
                    'isEdit' => true
                ]);
            })->name('evaluasi-magang.edit');

            // Update: Memperbarui evaluasi
            Route::put('/{evaluasi_magang_id}', function (Request $request, $evaluasi_magang_id) {
                $request->validate([
                    'pengajuan_id' => 'required|exists:t_pengajuan_magang,pengajuan_id',
                    'nilai' => 'required|numeric|min:0|max:100',
                    'komentar' => 'required|string'
                ]);

                $evaluasi = EvaluasiMagangModel::findOrFail($evaluasi_magang_id);
                $evaluasi->update([
                    'pengajuan_id' => $request->pengajuan_id,
                    'nilai' => $request->nilai,
                    'komentar' => $request->komentar,
                ]);

                return redirect()->route('dosen.evaluasi-magang')
                    ->with('success', 'Data evaluasi magang berhasil diperbarui');
            })->name('evaluasi-magang.update');

            // Destroy: Menghapus evaluasi
            Route::delete('/{evaluasi_magang_id}', function ($evaluasi_magang_id) {
                $evaluasi = EvaluasiMagangModel::findOrFail($evaluasi_magang_id);
                $evaluasi->delete();
                return redirect()->route('dosen.evaluasi-magang')
                    ->with('success', 'Data evaluasi magang berhasil dihapus');
            })->name('evaluasi-magang.destroy');
        });
    });

    // Mahasiswa Routes
    Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('authorize:mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/log-harian', fn() => view('roles.mahasiswa.log-harian', ['activeMenu' => 'logHarian']))->name('log.harian');

        // Placeholder Routes for Mahasiswa
        Route::get('/pengajuan-magang', fn() => view('roles.mahasiswa.pengajuan-magang', ['activeMenu' => 'pengajuanMagang']))->name('pengajuan.index');
        Route::get('/sertifikat', fn() => view('roles.mahasiswa.sertifikat', ['activeMenu' => 'sertifikasiFeedback']))->name('sertifikat');
        Route::get('/feedback', fn() => view('roles.mahasiswa.feedback', ['activeMenu' => 'sertifikasiFeedback']))->name('feedback');
    });

    // Perusahaan Routes
    Route::prefix('perusahaan')->name('perusahaan.')->middleware('authorize:perusahaan')->group(function () {
        Route::get('/dashboard', fn() => view('roles.perusahaan.dashboard', ['activeMenu' => 'dashboard']))->name('dashboard');
    });
});