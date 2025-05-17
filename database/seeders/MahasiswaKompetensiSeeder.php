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

        if (!$mahasiswa) {
            $this->command->error('Mahasiswa dengan nim 123456789 tidak ditemukan. Pastikan MahasiswaSeeder membuat data ini.');
            return;
        }

        if (!$kompetensi) {
            $this->command->error('Kompetensi Pemrograman PHP tidak ditemukan. Pastikan KompetensiSeeder membuat data ini.');
            return;
        }

        MahasiswaKompetensiModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id, // Gunakan primary key yang benar
            'kompetensi_id' => $kompetensi->kompetensi_id // Gunakan primary key yang benar
        ]);

        $this->command->info('Data mahasiswa kompetensi berhasil diimpor.');
    }
}
