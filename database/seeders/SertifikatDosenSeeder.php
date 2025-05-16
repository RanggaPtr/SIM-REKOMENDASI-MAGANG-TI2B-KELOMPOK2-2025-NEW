<?php

namespace Database\Seeders;

use App\Models\SertifikatDosenModel;
use App\Models\DosenModel;
use Illuminate\Database\Seeder;

class SertifikatDosenSeeder extends Seeder
{
   public function run()
    {
        $dosen = DosenModel::where('nik', '1234567890')->first();

        if (!$dosen) {
            $this->command->error('Dosen dengan nik 1234567890 tidak ditemukan. Pastikan DosenSeeder membuat data ini.');
            return;
        }

        SertifikatDosenModel::create([
            'dosen_id' => $dosen->dosen_id, // Ganti id menjadi dosen_id
            'nama_sertifikat' => 'Sertifikat Laravel Expert',
            'penerbit' => 'Laravel Academy',
            'tanggal_terbit' => '2024-01-01',
            'file_sertifikat' => 'sertifikat/laravel_expert.pdf'
        ]);

        $this->command->info('Data sertifikat dosen berhasil diimpor.');
    }
}
