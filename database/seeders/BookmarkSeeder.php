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
        $mahasiswa = MahasiswaModel::where('nim', '123456781')->first();

        if (!$mahasiswa) {
            $this->command->error('Mahasiswa dengan nim 123456789 tidak ditemukan. Pastikan MahasiswaSeeder membuat data ini.');
            return;
        }

        BookmarkModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => 3
        ]);
        BookmarkModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => 2
        ]);
        BookmarkModel::create([
            'mahasiswa_id' => 2,
            'lowongan_id' => 3
        ]);
        BookmarkModel::create([
            'mahasiswa_id' => 2,
            'lowongan_id' => 2
        ]);

        $this->command->info('Data bookmark berhasil diimpor.');
    }
}
