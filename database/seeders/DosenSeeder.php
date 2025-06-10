<?php

namespace Database\Seeders;

use App\Models\DosenModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use App\Models\PengajuanMagangModel;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua user dengan role dosen
        $dosenUsers = UsersModel::where('role', 'dosen')->get();

        foreach ($dosenUsers as $idx => $user) {
            DosenModel::create([
                'user_id' => $user->user_id,
                'nik' => '12345678' . ($idx + 1), 
                'prodi_id' => mt_rand(1, count(ProgramStudiModel::all())),
                'jumlah_bimbingan' => PengajuanMagangModel::where('dosen_id', $user->user_id)->count(),
            ]);
        }

        $this->command->info('Data dosen berhasil diimpor.');
    }
}