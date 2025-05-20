<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;


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
// Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/', function () {
    return view('landing-page');
})->name('landingPage');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/postlogin', [AuthController::class, 'postlogin'])->name('postlogin');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/postregister', [AuthController::class, 'postregister'])->name('postregister');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            default:
                return redirect('login');
        }
    })->name('home');

    // Rute untuk admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('roles.admin.dashboard', ['activeMenu' => 'dashboard']);
        })->name('dashboard');
        Route::get('/management-lowongan-magang', function () {
            return view('roles.admin.management-lowongan-magang', ['activeMenu' => 'manajemenMagang']);
        })->name('management.lowongan');
        // Tambahkan rute lain untuk admin di sini

        // Manajemen Pengguna
        Route::group(['prefix' => 'management-pengguna'], function () {
            Route::get('/', [UserController::class, 'index'])->name('user.index'); // menampilkan halaman awal user
            Route::post('/list', [UserController::class, 'list'])->name('user.list'); // menampilkan data user dalam bentuk json untuk datatable


            Route::get('/create_ajax', [UserController::class, 'create_ajax']); // menampilkan halaman form tambah user ajax


            Route::post('/ajax', [UserController::class, 'store_ajax']); // menyimpan data user baru ajax
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']); // menampilkan detail user ajax
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user ajax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // untuk tampilan form confirm delete user ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // menghapus data user ajax
            Route::get('/import', [UserController::class, 'import']); // menampilkan halaman form import User
            Route::post('/import_ajax', [UserController::class, 'import_ajax']); // menyimpan data User dari file import
            Route::get('/export_excel', [UserController::class, 'export_excel']); // ajax export excel
            Route::get('/export_pdf', [UserController::class, 'export_pdf']); // ajax export pdf
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
            Route::get('/', [PeriodeMagangController::class, 'index'])->name('programstudi.index');
            Route::post('/list', [PeriodeMagangController::class, 'list'])->name('programstudi.list');
             Route::get('/create_ajax', [PeriodeMagangController::class, 'create_ajax']); // menampilkan halaman form tambah user ajax
            Route::post('/ajax', [PeriodeMagangController::class, 'store_ajax']); // menyimpan data user baru ajax
            Route::get('/{id}/show_ajax', [PeriodeMagangController::class, 'show_ajax']); // menampilkan detail user ajax
            Route::get('/{id}/edit_ajax', [PeriodeMagangController::class, 'edit_ajax']); // menampilkan halaman form edit user ajax
            Route::put('/{id}/update_ajax', [PeriodeMagangController::class, 'update_ajax']); // menyimpan perubahan data user ajax
            Route::get('/{id}/delete_ajax', [PeriodeMagangController::class, 'confirm_ajax']); // untuk tampilan form confirm delete user ajax
            Route::delete('/{id}/delete_ajax', [PeriodeMagangController::class, 'delete_ajax']); // menghapus data user ajax
            Route::get('/import', [PeriodeMagangController::class, 'import']); // menampilkan halaman form import User
            Route::post('/import_ajax', [PeriodeMagangController::class, 'import_ajax']); // menyimpan data User dari file import
            Route::get('/export_excel', [PeriodeMagangController::class, 'export_excel']); // ajax export excel
            Route::get('/export_pdf', [PeriodeMagangController::class, 'export_pdf']); // ajax export pdf
        });

        //Manajemen Perusahaan Mitra 
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
        // dashboard
        Route::get('/dashboard', function () {
            return view('roles.dosen.dashboard', ['activeMenu' => 'dashboard']); // Perbaiki di sini
        })->name('dashboard');
        // Monitoring Mahasiswa
        Route::get('/monitoring-mahasiswa', function () {
            return view('roles.dosen.monitoring-mahasiswa', ['activeMenu' => 'monitoringMahasiswa']);
        })->name('monitoring.mahasiswa');
        // Evaluasi Magang
        Route::get('/evaluasi-magang', function () {
            return view('roles.dosen.evaluasi-magang', ['activeMenu' => 'evaluasiMagang']);
        });
        // Route::get('/management-akun-profile', function () {
        //     return view('roles.dosen.management-akun-profile', ['activeMenu' => 'managementAkun']);
        // })->name('management.akun');
    })->middleware('authorize:dosen');

    // Rute untuk mahasiswa
   Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('authorize:mahasiswa')->group(function () {
    Route::get('/dashboard', function () {
        return view('roles.mahasiswa.dashboard', ['activeMenu' => 'dashboard']);
    })->name('dashboard');

    Route::get('/log-harian', function () {
        return view('roles.mahasiswa.log-harian', ['activeMenu' => 'logHarian']);
    })->name('log.harian');
});

// Manajemen Profil
Route::middleware('authorize:mahasiswa')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change_password');
    Route::post('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update_password');
});

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

});

});
