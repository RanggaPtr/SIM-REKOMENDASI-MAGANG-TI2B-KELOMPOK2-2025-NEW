<?php

namespace Database\Seeders;

use App\Models\LogAktivitasModel;
use App\Models\UsersModel;
use Illuminate\Database\Seeder;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'admin@simmagang.com')->first();

        LogAktivitasModel::create([
            'user_id' => $user->id,
            'aktivitas' => 'Mengubah status pengajuan magang'
        ]);
    }
}
