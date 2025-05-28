<?php

namespace Database\Seeders;

use App\Models\LogAktivitasModel;
use App\Models\PengajuanMagangModel;
use App\Models\UsersModel;
use Illuminate\Database\Seeder;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        $pengajuan = PengajuanMagangModel::where('status', 'diajukan')->first();

        if (!$pengajuan) {
            $this->command->error('Pengajuan dengan status "diajukan" tidak ditemukan.');
            return;
        }

        LogAktivitasModel::create([
            'pengajuan_id' => $pengajuan->pengajuan_id,
            'aktivitas' => 'Mengubah status pengajuan magang'
        ]);
    }
}
