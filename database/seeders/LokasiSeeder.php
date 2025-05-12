<?php

namespace Database\Seeders;

use App\Models\LokasiModel;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run()
    {
        LokasiModel::create(['nama' => 'Malang']);
        LokasiModel::create(['nama' => 'Jakarta']);
        LokasiModel::create(['nama' => 'Surabaya']);
    }
}