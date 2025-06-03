<?php

namespace Database\Seeders;

use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use App\Models\SkemaModel;
use App\Models\WilayahModel;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua user dengan role mahasiswa
        $mahasiswaUsers = UsersModel::where('role', 'mahasiswa')->get();

        // Data default
        $programStudi = ProgramStudiModel::first();
        $wilayah = WilayahModel::first();
        $skema = SkemaModel::first();

        foreach ($mahasiswaUsers as $idx => $user) {
            MahasiswaModel::create([
                'user_id' => $user->user_id,
                'nim' => '12345678' . ($idx + 1),
                'program_studi_id' => $programStudi ? $programStudi->prodi_id : 1,
                'wilayah_id' => $wilayah ? $wilayah->wilayah_id : 1,
                'skema_id' => $skema ? $skema->skema_id : 1,
                'periode_id' => 1,
                'ipk' => 3.5 + ($idx * 0.1)
            ]);
        }

        $this->command->info('Data mahasiswa berhasil diimpor.');
    }
}
