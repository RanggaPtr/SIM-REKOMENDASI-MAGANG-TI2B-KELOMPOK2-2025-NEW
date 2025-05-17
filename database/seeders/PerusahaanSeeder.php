<?php

namespace Database\Seeders;

use App\Models\PerusahaanModel;
use App\Models\WilayahModel;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
   public function run()
    {
        $jakarta = WilayahModel::where('kode_wilayah', '3171')->first(); // KOTA JAKARTA PUSAT
        $bandung = WilayahModel::where('kode_wilayah', '3273')->first(); // KOTA BANDUNG

        if (!$jakarta) {
            $this->command->error('Wilayah KOTA JAKARTA PUSAT tidak ditemukan. Pastikan WilayahSeeder membuat data ini.');
            return;
        }

        if (!$bandung) {
            $this->command->error('Wilayah KOTA BANDUNG tidak ditemukan. Pastikan WilayahSeeder membuat data ini.');
            return;
        }

        $perusahaan = [
            [
                'nama' => 'PT Teknologi Nusantara',
                'ringkasan' => 'Perusahaan teknologi terkemuka di Jakarta.',
                'deskripsi' => 'PT Teknologi Nusantara adalah perusahaan yang bergerak di bidang pengembangan perangkat lunak dan solusi IT.',
                'logo' => 'teknologi_nusantara.jpg',
                'alamat' => 'Jl. Thamrin No. 55, Jakarta Pusat',
                'wilayah_id' => $jakarta->wilayah_id, // Gunakan wilayah_id yang benar
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
                'wilayah_id' => $bandung->wilayah_id, // Gunakan wilayah_id yang benar
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

        $this->command->info('Data perusahaan berhasil diimpor.');
    }
}
