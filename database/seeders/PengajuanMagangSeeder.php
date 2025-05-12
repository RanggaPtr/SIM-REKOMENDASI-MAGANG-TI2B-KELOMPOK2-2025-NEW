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
        $mahasiswa = MahasiswaModel::where('nim', '123456789')->first();
        $lowongan = LowonganMagangModel::where('judul', 'Magang Pengembang Web')->first();
        $dosen = DosenModel::where('nidn', '1234567890')->first();
        $periode = PeriodeMagangModel::where('nama', 'Januari-Juni 2025')->first();

        PengajuanMagangModel::create([
            'mahasiswa_id' => $mahasiswa->id,
            'lowongan_id' => $lowongan->id,
            'dosen_id' => $dosen->id,
            'periode_id' => $periode->id,
            'status' => 'diajukan'
        ]);
    }
}
