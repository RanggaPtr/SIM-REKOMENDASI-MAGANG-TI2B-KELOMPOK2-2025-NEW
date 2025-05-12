<?php

namespace Database\Seeders;

use App\Models\DosenModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'dosen1@simmagang.com')->first();
        $programStudi = ProgramStudiModel::where('nama', 'Teknik Informatika')->first();

        DosenModel::create([
            'user_id' => $user->id,
            'nidn' => '1234567890',
            'program_studi_id' => $programStudi->id,
            'jumlah_bimbingan' => 0
        ]);
    }
}