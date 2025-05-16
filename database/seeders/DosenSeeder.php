<?php

namespace Database\Seeders;

use App\Models\DosenModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'dosen1@simmagang.com')->first();
        $programStudi = ProgramStudiModel::where('nama', 'Teknik Informatika')->first();

        if (!$user) {
            $this->command->error('Pengguna dengan email dosen1@simmagang.com tidak ditemukan.');
            return;
        }

        if (!$programStudi) {
            $this->command->error('Program studi Teknik Informatika tidak ditemukan.');
            return;
        }

        DosenModel::create([
            'user_id' => $user->user_id,
            'nik' => '1234567890',
            'prodi_id' => $programStudi->prodi_id,
            'jumlah_bimbingan' => 0
        ]);

        $this->command->info('Data dosen berhasil diimpor.');
    }
}