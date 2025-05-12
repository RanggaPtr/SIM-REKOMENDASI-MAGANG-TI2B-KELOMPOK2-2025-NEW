<?php

namespace Database\Seeders;

use App\Models\SertifikatDosenModel;
use App\Models\DosenModel;
use Illuminate\Database\Seeder;

class SertifikatDosenSeeder extends Seeder
{
    public function run()
    {
        $dosen = DosenModel::where('nidn', '1234567890')->first();

        SertifikatDosenModel::create([
            'dosen_id' => $dosen->id,
            'nama_sertifikat' => 'Sertifikat Laravel Expert',
            'penerbit' => 'Laravel Academy',
            'tanggal_terbit' => '2024-01-01',
            'file_sertifikat' => 'sertifikat/laravel_expert.pdf' // Asumsi path file
        ]);
    }
}
