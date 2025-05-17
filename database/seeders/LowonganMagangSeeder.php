<?php

namespace Database\Seeders;

use App\Models\LowonganMagangModel;
use App\Models\PerusahaanModel;
use App\Models\PeriodeMagangModel;
use App\Models\SkemaModel;
use Illuminate\Database\Seeder;

class LowonganMagangSeeder extends Seeder
{
public function run()
    {
        $perusahaan = PerusahaanModel::where('nama', 'PT Teknologi Nusantara')->first(); // Sesuaikan dengan PerusahaanSeeder
        $periode = PeriodeMagangModel::where('nama', 'Januari-Juni 2025')->first();
        $skema = SkemaModel::where('nama', 'Magang Reguler')->first();

        if (!$perusahaan) {
            $this->command->error('Perusahaan PT Teknologi Nusantara tidak ditemukan. Pastikan PerusahaanSeeder membuat data ini.');
            return;
        }

        if (!$periode) {
            $this->command->error('Periode Januari-Juni 2025 tidak ditemukan. Pastikan PeriodeMagangSeeder membuat data ini.');
            return;
        }

        if (!$skema) {
            $this->command->error('Skema Magang Reguler tidak ditemukan. Pastikan SkemaSeeder membuat data ini.');
            return;
        }

        LowonganMagangModel::create([
            'perusahaan_id' => $perusahaan->perusahaan_id, // Gunakan primary key yang benar
            'periode_id' => $periode->periode_id,
            'skema_id' => $skema->skema_id,
            'judul' => 'Magang Pengembang Web',
            'deskripsi' => 'Mengembangkan aplikasi web menggunakan Laravel',
            'persyaratan' => 'Mahir PHP, mengerti Laravel',
            'bidang_keahlian' => 'Pengembangan Web',
            'tanggal_buka' => '2024-11-01',
            'tanggal_tutup' => '2024-12-31'
        ]);

        $this->command->info('Data lowongan magang berhasil diimpor.');
    }
}
