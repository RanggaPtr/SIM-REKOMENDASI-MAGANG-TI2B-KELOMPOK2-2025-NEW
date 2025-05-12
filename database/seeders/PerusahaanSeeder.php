<?php

namespace Database\Seeders;

use App\Models\PerusahaanModel;
use App\Models\LokasiModel;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    public function run()
    {
        $lokasi = LokasiModel::where('nama', 'Jakarta')->first();

        PerusahaanModel::create([
            'nama' => 'PT Teknologi Maju',
            'lokasi_id' => $lokasi->id,
            'kontak' => 'info@tekmaju.com',
            'bidang_industri' => 'Teknologi Informasi'
        ]);

        PerusahaanModel::create([
            'nama' => 'PT Data Solusi',
            'lokasi_id' => $lokasi->id,
            'kontak' => 'info@datasolusi.com',
            'bidang_industri' => 'Data Analytics'
        ]);
    }
}
