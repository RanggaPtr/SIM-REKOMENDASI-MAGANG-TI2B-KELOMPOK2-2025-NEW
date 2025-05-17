<?php

namespace Database\Seeders;

use App\Models\LogAktivitasModel;
use App\Models\UsersModel;
use Illuminate\Database\Seeder;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'mahasiswa1@simmagang.com')->first();

        if (!$user) {
            $this->command->error('Pengguna dengan email admin@simmagang.com tidak ditemukan. Pastikan UsersSeeder membuat data ini.');
            return;
        }

        LogAktivitasModel::create([
            'user_id' => $user->user_id, // Gunakan primary key yang benar
            'aktivitas' => 'Mengubah status pengajuan magang'
        ]);

        $this->command->info('Data log aktivitas berhasil diimpor.');
    }
}
