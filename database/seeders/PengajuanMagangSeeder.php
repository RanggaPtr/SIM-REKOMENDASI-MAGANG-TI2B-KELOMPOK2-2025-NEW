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
        $dosen = DosenModel::where('nik', '1234567890')->first();
        $periode = PeriodeMagangModel::where('nama', 'Semester Genap 2025')->first();

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

        PengajuanMagangModel::create([
            'mahasiswa_id' => 2,
            'lowongan_id' => 3,
            'status' => 'diajukan',
            'dosen_id' => 1234567890,
            'feedback_rating' => 4,
            'feedback_deskripsi' => 'Selama magang di sini, saya banyak belajar tentang alur kerja profesional dan bagaimana berkolaborasi dalam tim. Mentor sangat suportif dan terbuka terhadap pertanyaan, jadi saya merasa nyaman untuk terus belajar. Meskipun awalnya cukup menantang, tapi lingkungan kerjanya sangat membantu saya berkembang. Terima kasih atas kesempatan dan bimbingannya.',
        ]);

        $this->command->info('Data pengajuan magang berhasil diimpor.');
    }
}
