<?php

namespace Database\Seeders;

use App\Models\PeriodeMagangModel;
use Illuminate\Database\Seeder;

class PeriodeMagangSeeder extends Seeder
{
    public function run()
    {
        PeriodeMagangModel::create([
            'nama' => 'Januari-Juni 2025',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30'
        ]);
        PeriodeMagangModel::create([
            'nama' => 'Juli-Desember 2025',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31'
        ]);
    }
}
