<?php

namespace Database\Seeders;

use App\Models\ProgramStudiModel;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run()
    {
        ProgramStudiModel::create(['nama' => 'Teknik Informatika']);
        ProgramStudiModel::create(['nama' => 'Sistem Informasi']);
        ProgramStudiModel::create(['nama' => 'Manajemen Informatika']);
    }
}
