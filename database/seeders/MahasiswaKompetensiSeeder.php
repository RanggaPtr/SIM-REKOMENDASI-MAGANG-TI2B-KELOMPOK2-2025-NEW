<?php

namespace Database\Seeders;

use App\Models\MahasiswaKompetensiModel;
use App\Models\MahasiswaModel;
use App\Models\KompetensiModel;
use Illuminate\Database\Seeder;

class MahasiswaKompetensiSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = MahasiswaModel::where('nim', '123456789')->first();
        $kompetensi = KompetensiModel::where('nama', 'Pemrograman PHP')->first();

        MahasiswaKompetensiModel::create([
            'mahasiswa_id' => $mahasiswa->id,
            'kompetensi_id' => $kompetensi->id
        ]);
    }
}
