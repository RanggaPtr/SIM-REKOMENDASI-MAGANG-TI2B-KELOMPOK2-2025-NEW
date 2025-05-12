<?php

namespace Database\Seeders;

use App\Models\LowonganKompetensiModel;
use App\Models\LowonganMagangModel;
use App\Models\KompetensiModel;
use Illuminate\Database\Seeder;

class LowonganKompetensiSeeder extends Seeder
{
    public function run()
    {
        $lowongan = LowonganMagangModel::where('judul', 'Magang Pengembang Web')->first();
        $kompetensi = KompetensiModel::where('nama', 'Pemrograman PHP')->first();

        LowonganKompetensiModel::create([
            'lowongan_id' => $lowongan->id,
            'kompetensi_id' => $kompetensi->id
        ]);
    }
}
