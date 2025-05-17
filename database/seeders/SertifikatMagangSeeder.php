<?php

namespace Database\Seeders;

use App\Models\SertifikatMagangModel;
use App\Models\PengajuanMagangModel;
use Illuminate\Database\Seeder;

class SertifikatMagangSeeder extends Seeder
{
   public function run()
    {
        $pengajuan = PengajuanMagangModel::where('status', 'diajukan')->first();

        if (!$pengajuan) {
            $this->command->error('Pengajuan dengan status "diajukan" tidak ditemukan. Pastikan PengajuanMagangSeeder membuat data ini.');
            return;
        }

        SertifikatMagangModel::create([
            'pengajuan_id' => $pengajuan->pengajuan_id, // Gunakan primary key yang benar
            'nama_dokumen' => 'Sertifikat Magang Web Developer',
            'jenis_dokumen' => 'sertifikat',
            'file_dokumen' => 'sertifikat/magang_web_developer.pdf'
        ]);

        $this->command->info('Data sertifikat magang berhasil diimpor.');
    }
}
