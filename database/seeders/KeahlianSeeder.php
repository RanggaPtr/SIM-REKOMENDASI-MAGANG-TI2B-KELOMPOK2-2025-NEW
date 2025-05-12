<?php

namespace Database\Seeders;

use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;

class KeahlianSeeder extends Seeder
{
    public function run()
    {
        KeahlianModel::create(['nama' => 'Laravel', 'deskripsi' => 'Framework PHP untuk pengembangan web']);
        KeahlianModel::create(['nama' => 'TensorFlow', 'deskripsi' => 'Library untuk machine learning']);
        KeahlianModel::create(['nama' => 'MySQL', 'deskripsi' => 'Sistem manajemen database']);
    }
}