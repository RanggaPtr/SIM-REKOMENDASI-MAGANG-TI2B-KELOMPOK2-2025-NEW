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

        SertifikatMagangModel::create([
            'pengajuan_id' => $pengajuan->id,
            'nama_dokumen' => 'Sertifikat Magang Web Developer',
            'jenis_dokumen' => 'sertifikat',
            'file_dokumen' => 'sertifikat/magang_web_developer.pdf' // Asumsi path file
        ]);
    }
}
