<?php

namespace Database\Seeders;

use App\Models\DosenModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use App\Models\PengajuanMagangModel;
use App\Models\KompetensiModel;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua user dengan role dosen dan data referensi
        $dosenUsers = UsersModel::where('role', 'dosen')->get();
        $prodiIds = ProgramStudiModel::pluck('prodi_id')->toArray();
        $kompetensiIds = KompetensiModel::pluck('kompetensi_id')->toArray();

        if ($prodiIds === [] || $kompetensiIds === []) {
            $this->command?->warn('Lewati DosenSeeder: Program Studi atau Kompetensi belum tersedia.');
            return;
        }

        foreach ($dosenUsers as $idx => $user) {
            $prodiId = $prodiIds[$idx % count($prodiIds)];
            $kompetensiId = $kompetensiIds[$idx % count($kompetensiIds)];

            DosenModel::create([
                'user_id' => $user->user_id,
                'nik' => '12345678' . ($idx + 1), 
                'prodi_id' => $prodiId,
                'kompetensi_id' => $kompetensiId,
                'jumlah_bimbingan' => PengajuanMagangModel::where('dosen_id', $user->user_id)->count(),
            ]);
        }

        $this->command->info('Data dosen berhasil diimpor.');
    }
}