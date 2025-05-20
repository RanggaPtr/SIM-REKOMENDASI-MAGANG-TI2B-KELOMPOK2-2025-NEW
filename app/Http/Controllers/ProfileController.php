<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
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
=======
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profiles.show', compact('user', 'profile'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profiles.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'academic_profile' => 'nullable|string',
            'skills' => 'nullable|string',
            'certifications' => 'nullable|string',
            'experiences' => 'nullable|string',
            'preferred_location' => 'nullable|string',
            'internship_type' => 'nullable|string',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $profile = Auth::user()->profile;

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = Auth::id();
        }

        $profile->fill($validated);

        if ($request->hasFile('cv')) {
            $profile->cv_path = $request->file('cv')->store('documents/cv', 'public');
        }
        if ($request->hasFile('cover_letter')) {
            $profile->cover_letter_path = $request->file('cover_letter')->store('documents/cover_letter', 'public');
        }
        if ($request->hasFile('certificate')) {
            $profile->certificate_path = $request->file('certificate')->store('documents/certificate', 'public');
        }

        $profile->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
>>>>>>> 365248c5101708fc24da2fc17e20a272dc5abbca
