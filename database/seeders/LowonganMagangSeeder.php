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
        $perusahaan = PerusahaanModel::where('nama', 'PT Teknologi Maju')->first();
        $periode = PeriodeMagangModel::where('nama', 'Januari-Juni 2025')->first();
        $skema = SkemaModel::where('nama', 'Magang Reguler')->first();

        LowonganMagangModel::create([
            'perusahaan_id' => $perusahaan->id,
            'periode_id' => $periode->id,
            'skema_id' => $skema->id,
            'judul' => 'Magang Pengembang Web',
            'deskripsi' => 'Mengembangkan aplikasi web menggunakan Laravel',
            'persyaratan' => 'Mahir PHP, mengerti Laravel',
            'bidang_keahlian' => 'Pengembangan Web',
            'tanggal_buka' => '2024-11-01',
            'tanggal_tutup' => '2024-12-31'
        ]);
    }
}
