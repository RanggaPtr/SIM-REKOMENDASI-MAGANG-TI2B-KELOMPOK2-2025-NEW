<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
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