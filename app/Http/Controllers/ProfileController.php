<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\PerusahaanModel;
use App\Models\ProgramStudiModel;
use App\Models\WilayahModel;
use App\Models\SkemaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Debug: Log request data
            Log::info('Profile update attempt', [
                'user_id' => $user->user_id,
                'request_data' => $request->except(['password', 'password_confirmation', 'foto_profile'])
            ]);

            // Validasi umum user
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:m_users,username,' . $user->user_id . ',user_id',
                'email' => 'required|string|email|max:255|unique:m_users,email,' . $user->user_id . ',user_id',
                'no_telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:500',
                'password' => 'nullable|string|min:8|confirmed',
                'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Prepare update data (hanya field yang ada di database)
            $updateData = [
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'no_telepon' => $validated['no_telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ];

            // Update password jika diisi
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Upload foto profile
            if ($request->hasFile('foto_profile')) {
                // Hapus foto lama jika ada
                if ($user->foto_profile && Storage::exists('public/' . $user->foto_profile)) {
                    Storage::delete('public/' . $user->foto_profile);
                }
                
                $path = $request->file('foto_profile')->store('public/profiles');
                $updateData['foto_profile'] = str_replace('public/', '', $path);
            }

            // Update user data
            $userUpdated = $user->update($updateData);
            
            if (!$userUpdated) {
                Log::error('Failed to update user data', ['user_id' => $user->user_id]);
                return redirect()->back()->with('error', 'Gagal memperbarui data user.');
            }

            // Update data role spesifik
            switch ($user->role) {
                case 'dosen':
                    $dosenValidated = $request->validate([
                        'nik' => 'required|string|max:50',
                        'prodi_id' => 'required|exists:m_program_studi,prodi_id',
                    ]);

                    $dosenUpdated = DosenModel::updateOrCreate(
                        ['user_id' => $user->user_id],
                        [
                            'nik' => $dosenValidated['nik'],
                            'prodi_id' => $dosenValidated['prodi_id'],
                        ]
                    );
                    
                    Log::info('Dosen profile updated', [
                        'user_id' => $user->user_id, 
                        'data' => $dosenValidated,
                        'updated' => $dosenUpdated->wasRecentlyCreated ? 'created' : 'updated'
                    ]);
                    break;

                case 'mahasiswa':
                    $mahasiswaValidated = $request->validate([
                        'nim' => 'required|string|max:50',
                        'program_studi_id' => 'required|exists:m_program_studi,prodi_id',
                        'wilayah_id' => 'required|exists:m_wilayah,wilayah_id',
                        'skema_id' => 'required|exists:m_skema,skema_id',
                        'ipk' => 'required|numeric|between:0,4',
                    ]);

                    $mahasiswaUpdated = MahasiswaModel::updateOrCreate(
                        ['user_id' => $user->user_id],
                        [
                            'nim' => $mahasiswaValidated['nim'],
                            'program_studi_id' => $mahasiswaValidated['program_studi_id'],
                            'wilayah_id' => $mahasiswaValidated['wilayah_id'],
                            'skema_id' => $mahasiswaValidated['skema_id'],
                            'ipk' => $mahasiswaValidated['ipk'],
                        ]
                    );
                    
                    Log::info('Mahasiswa profile updated', [
                        'user_id' => $user->user_id, 
                        'data' => $mahasiswaValidated,
                        'updated' => $mahasiswaUpdated->wasRecentlyCreated ? 'created' : 'updated'
                    ]);
                    break;

                case 'perusahaan':
                    $perusahaanValidated = $request->validate([
                        'nama_perusahaan' => 'required|string|max:255',
                    ]);

                    $perusahaanUpdated = PerusahaanModel::updateOrCreate(
                        ['user_id' => $user->user_id],
                        [
                            'nama_perusahaan' => $perusahaanValidated['nama_perusahaan'],
                        ]
                    );
                    
                    Log::info('Perusahaan profile updated', [
                        'user_id' => $user->user_id, 
                        'data' => $perusahaanValidated,
                        'updated' => $perusahaanUpdated->wasRecentlyCreated ? 'created' : 'updated'
                    ]);
                    break;
            }

            // Refresh user instance untuk mendapatkan data terbaru
            $user->refresh();
            
            Log::info('Profile update successful', ['user_id' => $user->user_id]);

            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in profile update', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Profile update error', [
                'user_id' => Auth::id(), 
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk mendapatkan data dropdown (opsional)
     */
    public function getDropdownData()
    {
        try {
            $programStudi = ProgramStudiModel::orderBy('nama')->get();
            $wilayah = WilayahModel::orderBy('nama')->get();
            $skema = SkemaModel::orderBy('nama')->get();

            return response()->json([
                'program_studi' => $programStudi,
                'wilayah' => $wilayah,
                'skema' => $skema
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching dropdown data', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal mengambil data dropdown'], 500);
        }
    }

    /**
     * Method untuk debugging - bisa dihapus setelah masalah teratasi
     */
    public function debugProfile()
    {
        $user = Auth::user();
        
        $debug = [
            'user_data' => $user->toArray(),
            'role_data' => null
        ];

        switch ($user->role) {
            case 'dosen':
                $debug['role_data'] = DosenModel::where('user_id', $user->user_id)->first();
                break;
            case 'mahasiswa':
                $debug['role_data'] = MahasiswaModel::where('user_id', $user->user_id)->first();
                break;
            case 'perusahaan':
                $debug['role_data'] = PerusahaanModel::where('user_id', $user->user_id)->first();
                break;
        }

        return response()->json($debug);
    }
}