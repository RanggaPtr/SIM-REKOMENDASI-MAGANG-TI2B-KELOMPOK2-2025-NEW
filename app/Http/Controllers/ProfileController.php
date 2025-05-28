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
            $request->validate([
                'nik' => 'required|string',
                'prodi_id' => 'required|exists:m_program_studi,prodi_id',
            ]);

            $dosen = DosenModel::where('user_id', $user->user_id)->first();

            if ($dosen) {
                $dosen->update([
                    'nik' => $request->nik,
                    'prodi_id' => $request->prodi_id,
                ]);
            } else {
                DosenModel::create([
                    'user_id' => $user->user_id,
                    'nik' => $request->nik,
                    'prodi_id' => $request->prodi_id,
                    'jumlah_bimbingan' => 0,
                ]);
            }
        } elseif ($user->role === 'mahasiswa') {
            $request->validate([
                'nim' => 'required|string',
                'program_studi_id' => 'required|exists:m_program_studi,prodi_id',
                'wilayah_id' => 'required|exists:m_wilayah,wilayah_id',
                'ipk' => 'required|numeric|min:0|max:4',
            ]);

            $mahasiswa = MahasiswaModel::where('user_id', $user->user_id)->first();

            if ($mahasiswa) {
                $mahasiswa->update([
                    'nim' => $request->nim,
                    'program_studi_id' => $request->program_studi_id,
                    'wilayah_id' => $request->wilayah_id,
                    'ipk' => $request->ipk,
                ]);
            } else {
                MahasiswaModel::create([
                    'user_id' => $user->user_id,
                    'nim' => $request->nim,
                    'program_studi_id' => $request->program_studi_id,
                    'wilayah_id' => $request->wilayah_id,
                    'ipk' => $request->ipk,
                ]);
            }
        } elseif ($user->role === 'perusahaan') {
            $request->validate([
                'nama_perusahaan' => 'required|string|max:255',
            ]);

            $perusahaan = PerusahaanModel::where('user_id', $user->user_id)->first();

            if ($perusahaan) {
                $perusahaan->update([
                    'nama_perusahaan' => $request->nama_perusahaan,
                ]);
            } else {
                PerusahaanModel::create([
                    'user_id' => $user->user_id,
                    'nama_perusahaan' => $request->nama_perusahaan,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
