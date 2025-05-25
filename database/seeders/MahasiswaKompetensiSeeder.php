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

        MahasiswaKompetensiModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id, // Gunakan primary key yang benar
            'kompetensi_id' => 1
        ]);

        MahasiswaKompetensiModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id, // Gunakan primary key yang benar
            'kompetensi_id' => 3
        ]);

        $this->command->info('Data mahasiswa kompetensi berhasil diimpor.');
    }
}
