<?php

namespace Database\Seeders;

use App\Models\PengajuanMagangModel;
use App\Models\MahasiswaModel;
use App\Models\LowonganMagangModel;
use App\Models\DosenModel;
use App\Models\PeriodeMagangModel;
use Illuminate\Database\Seeder;

class PengajuanMagangSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = MahasiswaModel::where('nim', '123456781')->first();

        if (!$mahasiswa) {
            $this->command->error('Mahasiswa dengan nim 123456789 tidak ditemukan. Pastikan MahasiswaSeeder membuat data ini.');
            return;
        }

        PengajuanMagangModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => 1,
            'status' => 'diajukan'
        ]);

        PengajuanMagangModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => 2,
            'status' => 'diajukan'
        ]);

        for ($i = 1; $i <= 51; $i++) {
            PengajuanMagangModel::create([
                'mahasiswa_id' => 2,
                'lowongan_id' => $i,
                'status' => 'selesai',
                'dosen_id' => 1234567890,
                'feedback_rating' => mt_rand(3, 5),
                'feedback_deskripsi' => 'Selama magang di sini, saya banyak belajar tentang alur kerja profesional dan bagaimana berkolaborasi dalam tim. Mentor sangat suportif dan terbuka terhadap pertanyaan, jadi saya merasa nyaman untuk terus belajar. Meskipun awalnya cukup menantang, tapi lingkungan kerjanya sangat membantu saya berkembang. Terima kasih atas kesempatan dan bimbingannya.',
            ]);
            PengajuanMagangModel::create([
                'mahasiswa_id' => 1,
                'lowongan_id' => $i,
                'status' => 'selesai',
                'dosen_id' => 1234567891,
                'feedback_rating' => mt_rand(3, 5),
                'feedback_deskripsi' => 'Selama magang di sini, saya banyak belajar tentang alur kerja profesional dan bagaimana berkolaborasi dalam tim. Mentor sangat suportif dan terbuka terhadap pertanyaan, jadi saya merasa nyaman untuk terus belajar. Meskipun awalnya cukup menantang, tapi lingkungan kerjanya sangat membantu saya berkembang. Terima kasih atas kesempatan dan bimbingannya.',
            ]);
        }

        $this->command->info('Data pengajuan magang berhasil diimpor.');
    }
}
