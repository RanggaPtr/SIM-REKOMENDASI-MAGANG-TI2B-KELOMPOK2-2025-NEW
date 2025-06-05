<?php

namespace Database\Seeders;

use App\Models\LowonganMagangModel;
use Illuminate\Database\Seeder;

class LowonganMagangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                "perusahaan_id" => 1,
                "periode_id" => 1,
                "skema_id" => 1,
                "judul" => "Magang Web Developer",
                "deskripsi" => "Membantu pengembangan aplikasi web berbasis Laravel.",
                "persyaratan" => "1. IPK minimal 3.0\n2. Mahasiswa aktif\n3. Memiliki laptop pribadi",
                "tunjangan" => true,
                "tanggal_buka" => "2024-11-01",
                "tanggal_tutup" => "2027-12-31"
            ],
            [
                "perusahaan_id" => 2,
                "periode_id" => 1,
                "skema_id" => 2,
                "judul" => "Magang Data Analyst",
                "deskripsi" => "Analisis data dan pembuatan dashboard.",
                "persyaratan" => "1. IPK minimal 3.2\n2. Pernah mengikuti organisasi\n3. Memiliki ketelitian tinggi",
                "tunjangan" => false,
                "tanggal_buka" => "2024-11-10",
                "tanggal_tutup" => "2028-12-25"
            ],
            [
                "perusahaan_id" => 3,
                "periode_id" => 2,
                "skema_id" => 1,
                "judul" => "Magang Content Creator",
                "deskripsi" => "Membuat konten digital untuk media sosial.",
                "persyaratan" => "1. IPK minimal 2.8\n2. Aktif di media sosial\n3. Kreatif dan komunikatif",
                "tunjangan" => false,
                "tanggal_buka" => "2024-05-01",
                "tanggal_tutup" => "2025-06-15"
            ],
            [
                "perusahaan_id" => 4,
                "periode_id" => 2,
                "skema_id" => 2,
                "judul" => "Magang Mobile Developer",
                "deskripsi" => "Pengembangan aplikasi mobile Android.",
                "persyaratan" => "1. IPK minimal 3.0\n2. Mampu bekerja secara tim\n3. Bersedia kerja remote",
                "tunjangan" => true,
                "tanggal_buka" => "2024-07-01",
                "tanggal_tutup" => "2025-08-31"
            ],
            [
                "perusahaan_id" => 5,
                "periode_id" => 1,
                "skema_id" => 1,
                "judul" => "Magang UI/UX Designer",
                "deskripsi" => "Mendesain tampilan aplikasi digital.",
                "persyaratan" => "1. IPK minimal 3.0\n2. Punya portofolio desain\n3. Detail-oriented",
                "tunjangan" => false,
                "tanggal_buka" => "2024-11-15",
                "tanggal_tutup" => "2028-12-20"
            ],
            [
                "perusahaan_id" => 6,
                "periode_id" => 2,
                "skema_id" => 2,
                "judul" => "Magang Backend Developer",
                "deskripsi" => "Membangun API dan backend aplikasi.",
                "persyaratan" => "1. IPK minimal 3.0\n2. Bisa bekerja di bawah tekanan\n3. Mahasiswa semester akhir",
                "tunjangan" => true,
                "tanggal_buka" => "2024-07-10",
                "tanggal_tutup" => "2025-09-01"
            ]
        ];

        foreach ($data as $item) {
            LowonganMagangModel::create($item);
        }

        $this->command->info('Data dummy lowongan magang berhasil diimpor.');
    }
}
