<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\PerusahaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:m_users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:8|confirmed',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        if ($request->hasFile('foto_profile')) {
            if ($user->foto_profile) {
                Storage::delete('public/' . $user->foto_profile);
            }
            $path = $request->file('foto_profile')->store('public/profiles');
            $user->foto_profile = str_replace('public/', '', $path);
        }

        $user->save();

        // Handle role specific data
        if ($user->role === 'dosen') {
            // Validasi data khusus dosen
            $request->validate([
                'nik' => 'required|string',
                'prodi_id' => 'required|exists:m_program_studi,prodi_id',
            ]);

            // Check if dosen already exists
            $dosen = DosenModel::where('user_id', $user->user_id)->first();
            
            if ($dosen) {
                // Update existing dosen data
                $dosen->update([
                    'nik' => $request->nik,
                    'prodi_id' => $request->prodi_id,
                ]);
            } else {
                // Create new dosen data
                DosenModel::create([
                    'user_id' => $user->user_id,
                    'nik' => $request->nik,
                    'prodi_id' => $request->prodi_id,
                    'jumlah_bimbingan' => 0, // Default value
                ]);
            }
        } elseif ($user->role === 'mahasiswa') {
            // Handle mahasiswa specific updates if needed
            // Kode untuk mahasiswa
        } elseif ($user->role === 'perusahaan') {
            // Handle perusahaan specific updates if needed
            // Kode untuk perusahaan
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}