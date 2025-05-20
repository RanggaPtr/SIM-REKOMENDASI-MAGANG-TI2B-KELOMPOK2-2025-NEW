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
                'wilayah_id' => $jakarta->wilayah_id,
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
                'wilayah_id' => $bandung->wilayah_id,
                'kontak' => '02298765432',
                'bidang_industri' => 'Analisis Data',
                'rating' => 4.2,
                'deskripsi_rating' => 'Perusahaan dengan tim yang suportif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Kreatif Media',
                'ringkasan' => 'Agensi kreatif digital di Jakarta.',
                'deskripsi' => 'PT Kreatif Media fokus pada pengembangan konten digital dan pemasaran online.',
                'logo' => 'kreatif_media.jpg',
                'alamat' => 'Jl. Sudirman No. 10, Jakarta Pusat',
                'wilayah_id' => $jakarta->wilayah_id,
                'kontak' => '02187654321',
                'bidang_industri' => 'Media & Kreatif',
                'rating' => 4.0,
                'deskripsi_rating' => 'Lingkungan kerja yang dinamis dan kreatif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Solusi Pintar',
                'ringkasan' => 'Konsultan IT di Bandung.',
                'deskripsi' => 'PT Solusi Pintar menawarkan solusi konsultasi IT dan pengembangan aplikasi.',
                'logo' => 'solusi_pintar.jpg',
                'alamat' => 'Jl. Merdeka No. 45, Bandung',
                'wilayah_id' => $bandung->wilayah_id,
                'kontak' => '02212345678',
                'bidang_industri' => 'Konsultan IT',
                'rating' => 4.3,
                'deskripsi_rating' => 'Tim profesional dan berpengalaman.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Inovasi Digital',
                'ringkasan' => 'Startup teknologi di Jakarta.',
                'deskripsi' => 'PT Inovasi Digital mengembangkan produk digital inovatif untuk kebutuhan bisnis.',
                'logo' => 'inovasi_digital.jpg',
                'alamat' => 'Jl. MH Thamrin No. 20, Jakarta Pusat',
                'wilayah_id' => $jakarta->wilayah_id,
                'kontak' => '02155566677',
                'bidang_industri' => 'Startup Teknologi',
                'rating' => 4.6,
                'deskripsi_rating' => 'Budaya kerja yang kolaboratif dan inovatif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Bandung Cerdas',
                'ringkasan' => 'Perusahaan edukasi digital di Bandung.',
                'deskripsi' => 'PT Bandung Cerdas menyediakan platform pembelajaran online dan pelatihan digital.',
                'logo' => 'bandung_cerdas.jpg',
                'alamat' => 'Jl. Asia Afrika No. 88, Bandung',
                'wilayah_id' => $bandung->wilayah_id,
                'kontak' => '02233445566',
                'bidang_industri' => 'Edukasi Digital',
                'rating' => 4.1,
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
