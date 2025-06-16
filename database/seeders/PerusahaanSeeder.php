<?php

namespace Database\Seeders;

use App\Models\PerusahaanModel;
use App\Models\WilayahModel;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
   public function run()
    {
        $jakarta = WilayahModel::where('kode_wilayah', '3173')->first(); // KOTA JAKARTA PUSAT
        $bandung = WilayahModel::where('kode_wilayah', '3273')->first(); // KOTA BANDUNG
        $surabaya = WilayahModel::where('kode_wilayah', '3578')->first(); // KOTA SURABAYA
        $yogyakarta = WilayahModel::where('kode_wilayah', '3471')->first(); // KOTA YOGYAKARTA
        $semarang = WilayahModel::where('kode_wilayah', '3374')->first(); // KOTA SEMARANG

        if (!$jakarta || !$bandung || !$surabaya || !$yogyakarta || !$semarang) {
            $this->command->error('Wilayah tidak ditemukan. Pastikan WilayahSeeder membuat data wilayah yang dibutuhkan.');
            return;
        }

        $perusahaan = [
            [
                'nama' => 'PT Teknologi Nusantara',
                'ringkasan' => 'Perusahaan teknologi terkemuka di Jakarta.',
                'deskripsi' => 'PT Teknologi Nusantara adalah perusahaan yang bergerak di bidang pengembangan perangkat lunak dan solusi IT.',
                'logo' => 'images/default_logo1.png',
                'alamat' => 'Jl. Thamrin No. 55, Jakarta Pusat',
                'wilayah_id' => $jakarta->wilayah_id,
                'kontak' => '02112345678',
                'bidang_industri' => 'Teknologi Informasi',
                'rating' => mt_rand(100, 500) / 100, // Random rating between 1 and 5
                'deskripsi_rating' => 'Perusahaan dengan lingkungan kerja yang inovatif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Data Solusi',
                'ringkasan' => 'Perusahaan analisis data di Bandung.',
                'deskripsi' => 'PT Data Solusi menyediakan layanan analisis data dan big data untuk berbagai industri.',
                'logo' => 'images/default_logo2.png',
                'alamat' => 'Jl. Dago No. 100, Bandung',
                'wilayah_id' => $bandung->wilayah_id,
                'kontak' => '02298765432',
                'bidang_industri' => 'Analisis Data',
                'rating' => mt_rand(150, 500) / 100, // Random rating between 1 and 5
                'deskripsi_rating' => 'Perusahaan dengan tim yang suportif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Surabaya Digital',
                'ringkasan' => 'Startup digital di Surabaya.',
                'deskripsi' => 'PT Surabaya Digital fokus pada pengembangan aplikasi mobile dan web.',
                'logo' => 'images/default_logo3.png',
                'alamat' => 'Jl. Pemuda No. 1, Surabaya',
                'wilayah_id' => $surabaya->wilayah_id,
                'kontak' => '0311234567',
                'bidang_industri' => 'Startup Teknologi',
                'rating' => mt_rand(120, 500) / 100, // Random rating between 1 and 5
                'deskripsi_rating' => 'Lingkungan kerja kreatif dan kolaboratif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Jogja Kreatif',
                'ringkasan' => 'Agensi kreatif digital di Yogyakarta.',
                'deskripsi' => 'PT Jogja Kreatif bergerak di bidang desain grafis dan pemasaran digital.',
                'logo' => 'images/default_logo4.png',
                'alamat' => 'Jl. Malioboro No. 10, Yogyakarta',
                'wilayah_id' => $yogyakarta->wilayah_id,
                'kontak' => '0274123456',
                'bidang_industri' => 'Kreatif & Digital',
                'rating' => mt_rand(140, 500) / 100, // Random rating between 1 and 5
                'deskripsi_rating' => 'Tim muda dan inovatif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Semarang Cerdas',
                'ringkasan' => 'Perusahaan edukasi digital di Semarang.',
                'deskripsi' => 'PT Semarang Cerdas menyediakan platform pembelajaran online dan pelatihan digital.',
                'logo' => 'images/default_logo5.png',
                'alamat' => 'Jl. Pandanaran No. 88, Semarang',
                'wilayah_id' => $semarang->wilayah_id,
                'kontak' => '02433445566',
                'bidang_industri' => 'Edukasi Digital',
                'rating' => mt_rand(180, 500) / 100, // Random rating between 1 and 5
                'deskripsi_rating' => 'Fokus pada pengembangan SDM dan teknologi pendidikan.',
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