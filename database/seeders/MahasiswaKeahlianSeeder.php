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

        if (!$mahasiswa) {
            $this->command->error('Mahasiswa dengan nim 123456789 tidak ditemukan. Pastikan MahasiswaSeeder membuat data ini.');
            return;
        }

        if (!$keahlian) {
            $this->command->error('Keahlian Laravel tidak ditemukan. Pastikan KeahlianSeeder membuat data ini.');
            return;
        }

        MahasiswaKeahlianModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'keahlian_id' => $keahlian->keahlian_id
        ]);

        $this->command->info('Data mahasiswa keahlian berhasil diimpor.');
    }
}