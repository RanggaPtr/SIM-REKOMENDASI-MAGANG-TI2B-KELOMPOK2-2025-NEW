<?php

use App\Http\Controllers\Dosen\SertifikatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LowonganMagangController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

Route::get('/', function () {
    return view('landing-page');
})->name('landingPage');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/postlogin', [AuthController::class, 'postlogin'])->name('postlogin');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/postregister', [AuthController::class, 'postregister'])->name('postregister');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
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
                return redirect('login');
        }
    })->name('home');

    // Rute untuk admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('roles.admin.dashboard', ['activeMenu' => 'dashboard']);
        })->name('dashboard');

        // Manajemen Lowongan Magang
        Route::group(['prefix' => 'management-lowongan-magang'], function () {
            Route::get('/', [LowonganMagangController::class, 'index'])->name('lowongan.index');
            Route::get('/create', [LowonganMagangController::class, 'create'])->name('lowongan.create');
            Route::post('/', [LowonganMagangController::class, 'store'])->name('lowongan.store');
            Route::get('/{id}/edit', [LowonganMagangController::class, 'edit'])->name('lowongan.edit');
            Route::put('/{id}', [LowonganMagangController::class, 'update'])->name('lowongan.update');
            Route::delete('/{id}', [LowonganMagangController::class, 'destroy'])->name('lowongan.destroy');
        });

        // Manajemen Pengguna
        Route::group(['prefix' => 'management-pengguna'], function () {
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
        Route::group(['prefix' => 'management-prodi'], function () {
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

        Route::group(['prefix' => 'management-periode-magang'], function () {
            Route::get('/', [PeriodeMagangController::class, 'index'])->name('periode.index');
            Route::post('/list', [PeriodeMagangController::class, 'list'])->name('periode.list');
            Route::get('/create_ajax', [PeriodeMagangController::class, 'create_ajax']);
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
        Route::group(['prefix' => 'management-mitra'], function () {
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
    })->middleware('authorize:admin');

    // Rute untuk dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', function () {
            return view('roles.dosen.dashboard', ['activeMenu' => 'dashboard']);
        })->name('dashboard');

        Route::get('/monitoring-mahasiswa', function () {
            return view('roles.dosen.monitoring-mahasiswa', ['activeMenu' => 'monitoringMahasiswa']);
        })->name('monitoring.mahasiswa');

        Route::get('/evaluasi-magang', function () {
            $evaluasiMagangList = DB::table('evaluasi_magang')->get();
            return view('roles.dosen.evaluasi-magang', [
                'activeMenu' => 'evaluasiMagang',
                'evaluasiMagangList' => $evaluasiMagangList
            ]);
        })->name('evaluasi-magang');

        Route::get('/evaluasi-magang/create', function () {
            return view('roles.dosen.evaluasi-magang-form', [
                'activeMenu' => 'evaluasiMagang',
                'isEdit' => false
            ]);
        })->name('evaluasi-magang.create');

        Route::post('/evaluasi-magang', function (Request $request) {
            $request->validate([
                'pengajuan_id' => 'required',
                'nilai' => 'required|numeric|min:0|max:100',
                'komentar' => 'required|string'
            ]);

            DB::table('evaluasi_magang')->insert([
                'pengajuan_id' => $request->pengajuan_id,
                'nilai' => $request->nilai,
                'komentar' => $request->komentar,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('dosen.evaluasi-magang')
                ->with('success', 'Data evaluasi magang berhasil ditambahkan');
        })->name('evaluasi-magang.store');

        Route::get('/evaluasi-magang/{id}', function ($id) {
            $evaluasi = DB::table('evaluasi_magang')->where('id', $id)->first();
            return view('roles.dosen.evaluasi-magang-detail', [
                'activeMenu' => 'evaluasiMagang',
                'evaluasi' => $evaluasi
            ]);
        })->name('evaluasi-magang.show');

        Route::get('/evaluasi-magang/{id}/edit', function ($id) {
            $evaluasi = DB::table('evaluasi_magang')->where('id', $id)->first();
            return view('roles.dosen.evaluasi-magang-form', [
                'activeMenu' => 'evaluasiMagang',
                'evaluasi' => $evaluasi,
                'isEdit' => true
            ]);
        })->name('evaluasi-magang.edit');

        Route::put('/evaluasi-magang/{id}', function (Request $request, $id) {
            $request->validate([
                'pengajuan_id' => 'required',
                'nilai' => 'required|numeric|min:0|max:100',
                'komentar' => 'required|string'
            ]);

            DB::table('evaluasi_magang')
                ->where('id', $id)
                ->update([
                    'pengajuan_id' => $request->pengajuan_id,
                    'nilai' => $request->nilai,
                    'komentar' => $request->komentar,
                    'updated_at' => now()
                ]);

            return redirect()->route('dosen.evaluasi-magang')
                ->with('success', 'Data evaluasi magang berhasil diperbarui');
        })->name('evaluasi-magang.update');

        Route::delete('/evaluasi-magang/{id}', function ($id) {
            DB::table('evaluasi_magang')->where('id', $id)->delete();
            return redirect()->route('dosen.evaluasi-magang')
                ->with('success', 'Data evaluasi magang berhasil dihapus');
        })->name('evaluasi-magang.destroy');
    })->middleware('authorize:dosen');

    // Rute untuk mahasiswa
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', function () {
            return view('roles.mahasiswa.dashboard', ['activeMenu' => 'dashboard']);
        })->name('dashboard');
        Route::get('/log-harian', function () {
            return view('roles.mahasiswa.log-harian', ['activeMenu' => 'logHarian']);
        })->name('log.harian');
    })->middleware('authorize:mahasiswa');

    // Rute untuk perusahaan
    Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
        Route::get('/dashboard', function () {
            return view('roles.perusahaan.dashboard', ['activeMenu' => 'dashboard']);
        })->name('dashboard');
    })->middleware('authorize:perusahaan');
});