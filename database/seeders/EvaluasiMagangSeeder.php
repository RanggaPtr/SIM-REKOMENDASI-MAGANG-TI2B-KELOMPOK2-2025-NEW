<?php

namespace Database\Seeders;

use App\Models\EvaluasiMagangModel;
use App\Models\PengajuanMagangModel;
use Illuminate\Database\Seeder;

class EvaluasiMagangSeeder extends Seeder
{
    public function run()
    {
        $pengajuan = PengajuanMagangModel::where('status', 'diajukan')->first();

        if (!$pengajuan) {
            $this->command->error('Pengajuan dengan status "diajukan" tidak ditemukan. Pastikan PengajuanMagangSeeder membuat data ini.');
            return;
        }

        EvaluasiMagangModel::create([
            'pengajuan_id' => $pengajuan->pengajuan_id, // Gunakan primary key yang benar
            'nilai' => 85,
            'komentar' => 'Kinerja baik, perlu meningkatkan komunikasi'
        ]);

        $this->command->info('Data evaluasi magang berhasil diimpor.');
    }
}
