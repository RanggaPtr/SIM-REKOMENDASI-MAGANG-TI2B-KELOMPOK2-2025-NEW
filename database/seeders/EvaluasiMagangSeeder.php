<?php

namespace Database\Seeders;

use App\Models\EvaluasiMagangModel;
use App\Models\PengajuanMagangModel;
use Illuminate\Database\Seeder;

class EvaluasiMagangSeeder extends Seeder
{
    public function run()
    {
        $pengajuan = PengajuanMagangModel::where('status', 'diajukan')->first();

        EvaluasiMagangModel::create([
            'pengajuan_id' => $pengajuan->id,
            'nilai' => 85,
            'komentar' => 'Kinerja baik, perlu meningkatkan komunikasi'
        ]);
    }
}
