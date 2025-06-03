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

        if (!$dosen) {
            $this->command->error('Dosen dengan nik 1234567890 tidak ditemukan. Pastikan DosenSeeder membuat data ini.');
            return;
        }

        if (!$periode) {
            $this->command->error('Periode Januari-Juni 2025 tidak ditemukan. Pastikan PeriodeMagangSeeder membuat data ini.');
            return;
        }

        PengajuanMagangModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => 1,
            'dosen_id' => $dosen->dosen_id,
            'periode_id' => $periode->periode_id,
            'status' => 'diajukan'
        ]);

        $this->command->info('Data pengajuan magang berhasil diimpor.');
    }
}
