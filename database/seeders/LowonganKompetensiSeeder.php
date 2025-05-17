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

        if (!$lowongan) {
            $this->command->error('Lowongan Magang Pengembang Web tidak ditemukan. Pastikan LowonganMagangSeeder membuat data ini.');
            return;
        }

        if (!$kompetensi) {
            $this->command->error('Kompetensi Pemrograman PHP tidak ditemukan. Pastikan KompetensiSeeder membuat data ini.');
            return;
        }

        LowonganKompetensiModel::create([
            'lowongan_id' => $lowongan->lowongan_id, // Gunakan primary key yang benar
            'kompetensi_id' => $kompetensi->kompetensi_id // Gunakan primary key yang benar
        ]);

        $this->command->info('Data lowongan kompetensi berhasil diimpor.');
    }
}
