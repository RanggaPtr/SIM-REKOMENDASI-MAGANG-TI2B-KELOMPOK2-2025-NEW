<?php

namespace Database\Seeders;

use App\Models\SilabusKonversiSksModel;
use Illuminate\Database\Seeder;

class SilabusKonversiSksSeeder extends Seeder
{
    public function run()
    {
        $deskripsi = 'Silabus konversi SKS untuk magang sesuai bidang lowongan.';
        $kriteria = 'Minimal menyelesaikan 100 jam kerja dan melampirkan laporan akhir.';
        $dokumen_path = 'dokumen/default_silabus.pdf';

        // Misal untuk semua lowongan_id dari 1 sampai 51
        for ($i = 1; $i <= 51; $i++) {
            SilabusKonversiSksModel::create([
                'lowongan_id' => $i,
                'jumlah_sks' => mt_rand(18, 25),
                'deskripsi' => $deskripsi,
                'kriteria' => $kriteria,
                'dokumen_path' => $dokumen_path,
            ]);
        }

        $this->command->info('Data konversi SKS berhasil diimpor.');
    }
}