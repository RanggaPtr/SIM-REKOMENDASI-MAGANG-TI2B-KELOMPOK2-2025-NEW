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

        if (!$mahasiswa) {
            $this->command->error('Mahasiswa dengan nim 123456789 tidak ditemukan. Pastikan MahasiswaSeeder membuat data ini.');
            return;
        }

        if (!$lowongan) {
            $this->command->error('Lowongan Magang Pengembang Web tidak ditemukan. Pastikan LowonganMagangSeeder membuat data ini.');
            return;
        }

        BookmarkModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => $lowongan->lowongan_id
        ]);

        $this->command->info('Data bookmark berhasil diimpor.');
    }
}
