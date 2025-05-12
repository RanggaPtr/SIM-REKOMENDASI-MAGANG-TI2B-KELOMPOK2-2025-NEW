<?php

namespace Database\Seeders;

use App\Models\BookmarkModel;
use App\Models\MahasiswaModel;
use App\Models\LowonganMagangModel;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = MahasiswaModel::where('nim', '123456789')->first();
        $lowongan = LowonganMagangModel::where('judul', 'Magang Pengembang Web')->first();

        BookmarkModel::create([
            'mahasiswa_id' => $mahasiswa->id,
            'lowongan_id' => $lowongan->id
        ]);
    }
}
