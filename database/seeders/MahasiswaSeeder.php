<?php

namespace Database\Seeders;

use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use App\Models\LokasiModel;
use App\Models\MinatModel;
use App\Models\SkemaModel;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'mahasiswa1@simmagang.com')->first();
        $programStudi = ProgramStudiModel::where('nama', 'Teknik Informatika')->first();
        $lokasi = LokasiModel::where('nama', 'Malang')->first();
        $minat = MinatModel::where('nama', 'Pengembangan Web')->first();
        $skema = SkemaModel::where('nama', 'Magang Reguler')->first();

        MahasiswaModel::create([
            'user_id' => $user->id,
            'nim' => '123456789',
            'program_studi_id' => $programStudi->id,
            'lokasi_id' => $lokasi->id,
            'minat_id' => $minat->id,
            'skema_id' => $skema->id,
            'ipk' => 3.75
        ]);
    }
}