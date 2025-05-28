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
                "persyaratan" => "1. Mahasiswa aktif\n2. Memiliki laptop pribadi",
                "bidang_keahlian" => "Web Development",
                "minimal_ipk" => 3.0,
                "tunjangan" => 1500000,
                "tanggal_buka" => "2024-11-01",
                "tanggal_tutup" => "2027-12-31"
            ],
            [
                "perusahaan_id" => 2,
                "periode_id" => 1,
                "skema_id" => 2,
                "judul" => "Magang Data Analyst",
                "deskripsi" => "Analisis data dan pembuatan dashboard.",
                "persyaratan" => "1. Pernah mengikuti organisasi\n2. Memiliki ketelitian tinggi",
                "bidang_keahlian" => "Data Analysis",
                "minimal_ipk" => 3.2,
                "tunjangan" => 1200000,
                "tanggal_buka" => "2024-11-10",
                "tanggal_tutup" => "2028-12-25"
            ],
            [
                "perusahaan_id" => 3,
                "periode_id" => 2,
                "skema_id" => 1,
                "judul" => "Magang Content Creator",
                "deskripsi" => "Membuat konten digital untuk media sosial.",
                "persyaratan" => "1. Aktif di media sosial\n2. Kreatif dan komunikatif",
                "bidang_keahlian" => "Content Creation",
                "minimal_ipk" => 2.8,
                "tunjangan" => 1000000,
                "tanggal_buka" => "2024-05-01",
                "tanggal_tutup" => "2025-06-15"
            ],
            [
                "perusahaan_id" => 4,
                "periode_id" => 2,
                "skema_id" => 2,
                "judul" => "Magang Mobile Developer",
                "deskripsi" => "Pengembangan aplikasi mobile Android.",
                "persyaratan" => "1. Mampu bekerja secara tim\n2. Bersedia kerja remote",
                "bidang_keahlian" => "Mobile Development",
                "minimal_ipk" => 3.0,
                "tunjangan" => 1300000,
                "tanggal_buka" => "2024-07-01",
                "tanggal_tutup" => "2025-08-31"
            ],
            [
                "perusahaan_id" => 5,
                "periode_id" => 1,
                "skema_id" => 1,
                "judul" => "Magang UI/UX Designer",
                "deskripsi" => "Mendesain tampilan aplikasi digital.",
                "persyaratan" => "1. Punya portofolio desain\n2. Detail-oriented",
                "bidang_keahlian" => "UI/UX Design",
                "minimal_ipk" => 3.0,
                "tunjangan" => 1100000,
                "tanggal_buka" => "2024-11-15",
                "tanggal_tutup" => "2028-12-20"
            ],
            [
                "perusahaan_id" => 6,
                "periode_id" => 2,
                "skema_id" => 2,
                "judul" => "Magang Backend Developer",
                "deskripsi" => "Membangun API dan backend aplikasi.",
                "persyaratan" => "1. Bisa bekerja di bawah tekanan\n2. Mahasiswa semester akhir",
                "bidang_keahlian" => "Backend Development",
                "minimal_ipk" => 3.0,
                "tunjangan" => 1400000,
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