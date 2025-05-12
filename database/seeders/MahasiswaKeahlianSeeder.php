<?php

namespace Database\Seeders;

use App\Models\MahasiswaKeahlianModel;
use App\Models\MahasiswaModel;
use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;

class MahasiswaKeahlianSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = MahasiswaModel::where('nim', '123456789')->first();
        $keahlian = KeahlianModel::where('nama', 'Laravel')->first();

        MahasiswaKeahlianModel::create([
            'mahasiswa_id' => $mahasiswa->id,
            'keahlian_id' => $keahlian->id
        ]);
    }
}