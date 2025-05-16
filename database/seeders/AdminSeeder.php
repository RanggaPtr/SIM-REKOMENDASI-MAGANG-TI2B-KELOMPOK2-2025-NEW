<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use App\Models\UsersModel;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'admin1@univ.ac.id')->first();

        if (!$user) {
            $this->command->error('Pengguna dengan email admin1@univ.ac.id tidak ditemukan. Pastikan UsersSeeder membuat pengguna ini.');
            return;
        }

        AdminModel::create([
            'user_id' => $user->user_id, // Gunakan user_id sesuai dengan primary key di model
            'nik' => '1234567890123456',
            'jabatan' => 'Administrator Sistem'
        ]);

        $this->command->info('Data admin berhasil diimpor.');
    }
}