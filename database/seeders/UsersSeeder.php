<?php

namespace Database\Seeders;

use App\Models\UsersModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        UsersModel::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@simmagang.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Contoh No. 1'
        ]);

        UsersModel::create([
            'nama' => 'Dosen Satu',
            'email' => 'dosen1@simmagang.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'no_telepon' => '081234567891',
            'alamat' => 'Jl. Contoh No. 2'
        ]);

        UsersModel::create([
            'nama' => 'Mahasiswa Satu',
            'email' => 'mahasiswa1@simmagang.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'no_telepon' => '081234567892',
            'alamat' => 'Jl. Contoh No. 3'
        ]);
    }
}
