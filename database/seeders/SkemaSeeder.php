<?php

namespace Database\Seeders;

use App\Models\SkemaModel;
use Illuminate\Database\Seeder;

class SkemaSeeder extends Seeder
{
    public function run()
    {
        SkemaModel::create(['nama' => 'Magang Reguler (6 bulan)', 'deskripsi' => 'Magang selama 6 bulan']);
        SkemaModel::create(['nama' => 'Magang Intensif (3 bulan)', 'deskripsi' => 'Magang selama 3 bulan dengan proyek khusus']);
        SkemaModel::create(['nama' => 'Magang Moderate (4 bulan)', 'deskripsi' => 'Magang selama 4 bulan dengan tugas terstruktur']);
    }
}