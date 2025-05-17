<?php

namespace Database\Seeders;

use App\Models\SilabusKonversiSksModel;
use App\Models\LowonganMagangModel;
use Illuminate\Database\Seeder;

class SilabusKonversiSksSeeder extends Seeder
{
    public function run()
    {
        $lowongan = LowonganMagangModel::where('judul', 'Magang Pengembang Web')->first();

        if (!$lowongan) {
            $this->command->error('Lowongan Magang Pengembang Web tidak ditemukan. Pastikan LowonganMagangSeeder membuat data ini.');
            return;
        }

        $konversiSksData = [
            [
                'lowongan_id' => $lowongan->lowongan_id,
                'jumlah_sks' => 4,
                'deskripsi' => 'Konversi SKS untuk pengembangan web dengan fokus pada HTML, CSS, dan JavaScript.',
                'kriteria' => 'Minimal menyelesaikan 100 jam kerja dan melampirkan laporan.',
                'dokumen_path' => 'dokumen/konversi_sks_web_dev.pdf',
            ],
            [
                'lowongan_id' => $lowongan->lowongan_id,
                'jumlah_sks' => 3,
                'deskripsi' => 'Konversi SKS untuk proyek frontend menggunakan React.js.',
                'kriteria' => 'Minimal 80 jam kerja dan presentasi hasil proyek.',
                'dokumen_path' => 'dokumen/konversi_sks_frontend.pdf',
            ],
        ];

        foreach ($konversiSksData as $data) {
            SilabusKonversiSksModel::create($data);
        }

        $this->command->info('Data konversi SKS berhasil diimpor.');
    }
}