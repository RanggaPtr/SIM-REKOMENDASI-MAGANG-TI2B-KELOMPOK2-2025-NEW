<?php

namespace Database\Seeders;

use App\Models\PerusahaanModel;
use App\Models\WilayahModel;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
   public function run()
    {
        $perusahaan = [
            [
                'nama' => 'PT Teknologi Nusantara',
                'ringkasan' => 'Perusahaan teknologi terkemuka di Jakarta.',
                'deskripsi' => 'PT Teknologi Nusantara adalah perusahaan yang bergerak di bidang pengembangan perangkat lunak dan solusi IT.',
                'logo' => 'teknologi_nusantara.jpg',
                'alamat' => 'Jl. Thamrin No. 55, Jakarta Pusat',
                'wilayah_id' => 1, // Jakarta Pusat
                'kontak' => '02112345678',
                'bidang_industri' => 'Teknologi Informasi',
                'rating' => 4.5,
                'deskripsi_rating' => 'Perusahaan dengan lingkungan kerja yang inovatif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Data Solusi',
                'ringkasan' => 'Perusahaan analisis data di Bandung.',
                'deskripsi' => 'PT Data Solusi menyediakan layanan analisis data dan big data untuk berbagai industri.',
                'logo' => 'data_solusi.jpg',
                'alamat' => 'Jl. Dago No. 100, Bandung',
                'wilayah_id' => 2, // Bandung
                'kontak' => '02298765432',
                'bidang_industri' => 'Analisis Data',
                'rating' => 4.2,
                'deskripsi_rating' => 'Perusahaan dengan tim yang suportif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($perusahaan as $data) {
            PerusahaanModel::create($data);
        }
    }
}
