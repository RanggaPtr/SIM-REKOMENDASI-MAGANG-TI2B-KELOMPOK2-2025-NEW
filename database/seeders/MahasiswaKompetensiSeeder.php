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
        // Ambil semua mahasiswa dan kompetensi
        $mahasiswas = MahasiswaModel::all();
        $kompetensis = KompetensiModel::all()->shuffle();

        // Pastikan jumlah kompetensi cukup
        if ($kompetensis->count() < $mahasiswas->count()) {
            $this->command->error('Jumlah kompetensi kurang dari jumlah mahasiswa!');
            return;
        }

        // Kaitkan satu kompetensi acak ke setiap mahasiswa (tanpa duplikat)
        foreach ($mahasiswas as $index => $mahasiswa) {
            $kompetensi = $kompetensis[$index];
            MahasiswaKompetensiModel::create([
                'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                'kompetensi_id' => $kompetensi->kompetensi_id,
            ]);
        }

        $this->command->info('Data mahasiswa kompetensi berhasil diimpor.');
    }
}
