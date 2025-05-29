<?php

namespace Database\Seeders;

use App\Models\ProgramStudiModel;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run()
    {
        $programStudi = [
            ['nama' => 'D-IV Teknik Informatika', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'D-IV Sistem Informasi Bisnis', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'D-II Perangkat Piranti Lunak Situs ', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($programStudi as $prodi) {
            ProgramStudiModel::create($prodi);
        }
    }
}
