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

        foreach ($mahasiswaUsers as $idx => $user) {
            MahasiswaModel::create([
                'user_id' => $user->user_id,
                'nim' => '12345678' . ($idx + 1),
                'program_studi_id' => mt_rand(1, count(ProgramStudiModel::all())),
                'wilayah_id' => mt_rand(150, 200),
                'skema_id' => mt_rand(1, count(SkemaModel::all())),
                'periode_id' => 1,
                'ipk' => 3.2 + ($idx * 0.01)
            ]);
        }

        $this->command->info('Data mahasiswa berhasil diimpor.');
    }
}
