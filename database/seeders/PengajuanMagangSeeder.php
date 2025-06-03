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
        $periode = PeriodeMagangModel::where('nama', 'Januari-Juni 2025')->first();

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

        $this->command->info('Data pengajuan magang berhasil diimpor.');
    }
}
