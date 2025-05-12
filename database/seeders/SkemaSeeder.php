<?php

namespace Database\Seeders;

use App\Models\SkemaModel;
use Illuminate\Database\Seeder;

class SkemaSeeder extends Seeder
{
    public function run()
    {
        SkemaModel::create(['nama' => 'Magang Reguler', 'deskripsi' => 'Magang selama 6 bulan']);
        SkemaModel::create(['nama' => 'Magang Intensif', 'deskripsi' => 'Magang selama 3 bulan dengan proyek khusus']);
    }
}
