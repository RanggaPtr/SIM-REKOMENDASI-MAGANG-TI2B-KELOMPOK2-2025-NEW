<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use App\Models\UsersModel;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'admin@simmagang.com')->first();

        AdminModel::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'jabatan' => 'Administrator Sistem'
        ]);
    }
}