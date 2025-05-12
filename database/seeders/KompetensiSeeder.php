<?php

namespace Database\Seeders;

use App\Models\KompetensiModel;
use Illuminate\Database\Seeder;

class KompetensiSeeder extends Seeder
{
    public function run()
    {
        KompetensiModel::create(['nama' => 'Pemrograman PHP', 'deskripsi' => 'Kemampuan membuat aplikasi web dengan PHP']);
        KompetensiModel::create(['nama' => 'Machine Learning', 'deskripsi' => 'Kemampuan membangun model ML']);
        KompetensiModel::create(['nama' => 'Manajemen Database', 'deskripsi' => 'Kemampuan mengelola database relasional']);
    }
}
