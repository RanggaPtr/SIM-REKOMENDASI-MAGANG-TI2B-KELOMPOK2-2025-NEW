<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;

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
                return redirect()->route('admin.dashboard', ['activeMenu' => 'dashboard']);
            case 'dosen':
                return redirect()->route('dosen.dashboard', ['activeMenu' => 'dosen']);
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard', ['activeMenu' => 'mahasiswa']);
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
            Route::get('/export_excel', [UserController::class,'export_excel']); // ajax export excel
            Route::get('/export_pdf', [UserController::class,'export_pdf']); // ajax export pdf
        });
    })->middleware('authorize:admin');

   
      
 
    // Rute untuk dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', function () {
            return view('roles.dosen.dashboard', ['activeMenu' => 'dosen']);
        })->name('dashboard');
        Route::get('/management-akun-profile', function () {
            return view('roles.dosen.management-akun-profile', ['activeMenu' => 'manajemenData']);
        })->name('management.akun');
        // Tambahkan rute lain untuk dosen di sini
    })->middleware('authorize:dosen');

    // Rute untuk mahasiswa
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', function () {
            return view('roles.mahasiswa.dashboard', ['activeMenu' => 'mahasiswa']);
        })->name('dashboard');
        Route::get('/log-harian', function () {
            return view('roles.mahasiswa.log-harian', ['activeMenu' => 'log']);
        })->name('log.harian');
        // Tambahkan rute lain untuk mahasiswa di sini
    })->middleware('authorize:mahasiswa');
});