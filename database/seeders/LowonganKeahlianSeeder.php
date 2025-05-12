<?php

namespace Database\Seeders;

use App\Models\LowonganKeahlianModel;
use App\Models\LowonganMagangModel;
use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;

class LowonganKeahlianSeeder extends Seeder
{
    public function run()
    {
        $lowongan = LowonganMagangModel::where('judul', 'Magang Pengembang Web')->first();
        $keahlian = KeahlianModel::where('nama', 'Laravel')->first();

        LowonganKeahlianModel::create([
            'lowongan_id' => $lowongan->id,
            'keahlian_id' => $keahlian->id
        ]);
    }
}
