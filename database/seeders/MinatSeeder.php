<?php

namespace Database\Seeders;

use App\Models\MinatModel;
use Illuminate\Database\Seeder;

class MinatSeeder extends Seeder
{
    public function run()
    {
        MinatModel::create(['nama' => 'Pengembangan Web']);
        MinatModel::create(['nama' => 'Kecerdasan Buatan']);
        MinatModel::create(['nama' => 'Keamanan Siber']);
    }
}
