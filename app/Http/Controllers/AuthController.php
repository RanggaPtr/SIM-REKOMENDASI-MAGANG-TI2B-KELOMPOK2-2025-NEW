<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('home');
        }
        return redirect('login')->with('error', 'Login gagal, periksa username dan password.');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function postregister(Request $request)
    {
        // Validasi dasar untuk semua role
        $rules = [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:m_users,username',
            'email' => 'required|email|unique:m_users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:dosen,mahasiswa',
        ];

        // Validasi tambahan berdasarkan role
        if ($request->role === 'mahasiswa') {
            $rules['nim'] = 'required|string|unique:m_mahasiswa,nim|max:20';
        } elseif ($request->role === 'dosen') {
            $rules['nik'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        try {
            // Buat user terlebih dahulu
            $user = UsersModel::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Buat record di tabel sesuai role
            if ($request->role === 'dosen') {
                DosenModel::create([
                    'user_id' => $user->user_id,
                    'nik' => $request->nik,
                    'prodi_id' => $request->prodi_id ?? 1,
                    'jumlah_bimbingan' => 0,
                    'kompetensi_id' => $request->kompetensi_id ?? null,
                ]);
            } elseif ($request->role === 'mahasiswa') {
                MahasiswaModel::create([
                    'user_id' => $user->user_id,
                    'nim' => $request->nim,
                    'program_studi_id' => $request->program_studi_id ?? null,
                    'wilayah_id' => $request->wilayah_id ?? null,
                    'skema_id' => $request->skema_id ?? null,
                    'ipk' => $request->ipk ?? null,
                    'periode_id' => $request->periode_id ?? null,
                    'file_cv' => null,
                ]);
            }

            Log::info('Registrasi berhasil: ' . $request->username . ' (' . $request->nama . ') sebagai ' . $request->role);
            return redirect('login')->with('success', 'Registrasi berhasil, silakan login.');
        } catch (\Exception $e) {
            Log::error('Registrasi gagal: ' . $request->username . ' (' . $request->nama . ') - Error: ' . $e->getMessage());
            return redirect('register')->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}