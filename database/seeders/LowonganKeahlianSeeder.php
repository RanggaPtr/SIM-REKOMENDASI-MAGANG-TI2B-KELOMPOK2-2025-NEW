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

        if (!$lowongan) {
            $this->command->error('Lowongan Magang Pengembang Web tidak ditemukan. Pastikan LowonganMagangSeeder membuat data ini.');
            return;
        }

        if (!$keahlian) {
            $this->command->error('Keahlian Laravel tidak ditemukan. Pastikan KeahlianSeeder membuat data ini.');
            return;
        }

        LowonganKeahlianModel::create([
            'lowongan_id' => $lowongan->lowongan_id, // Gunakan primary key yang benar
            'keahlian_id' => $keahlian->keahlian_id // Gunakan primary key yang benar
        ]);

        $this->command->info('Data lowongan keahlian berhasil diimpor.');
    }
}
