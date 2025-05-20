<?php
namespace App\Http\Controllers;

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
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'username' => 'required|string|unique:m_users,username|unique:m_users,nim_nik',
        'email' => 'required|email|unique:m_users',
        'password' => 'required|string|min:8',
        'role' => 'required|in:dosen,mahasiswa',
    ]);

    try {
        $user = UsersModel::create([
            'nama' => $request->nama,
            'nim_nik' => $request->username, 
            'username' => $request->username,// Gunakan username sebagai nim_nik
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        \Log::info('Registrasi berhasil: ' . $request->username . ' (' . $request->nama . ') sebagai ' . $request->role);
        return redirect('login')->with('success', 'Registrasi berhasil, silakan login.');
    } catch (\Exception $e) {
        \Log::error('Registrasi gagal: ' . $request->username . ' (' . $request->nama . ') - Error: ' . $e->getMessage());
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