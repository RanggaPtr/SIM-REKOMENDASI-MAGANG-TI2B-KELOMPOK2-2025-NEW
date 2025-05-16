<?php

namespace Database\Seeders;

use App\Models\ProgramStudiModel;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run()
    {
        $programStudi = [
            ['nama' => 'Teknik Informatika', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sistem Informasi', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Manajemen Informatika', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($programStudi as $prodi) {
            ProgramStudiModel::create($prodi);
        }
    }
}
