<?php

namespace Database\Seeders;

use App\Models\UsersModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
  public function run()
    {
        $users = [
            [
                'nama' => 'Admin Utama',
                'username' => 'admin1',
                'email' => 'admin1@univ.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'foto_profile' => 'admin1.jpg',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dr. Budi Santoso',
                'username' => 'budi.santoso',
                'email' => 'dosen1@simmagang.com', // Sesuaikan dengan DosenSeeder
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'foto_profile' => 'budi.jpg',
                'no_telepon' => '081987654321',
                'alamat' => 'Jl. Sudirman No. 10, Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Andi Wijaya',
                'username' => 'andi.wijaya',
                'email' => 'mahasiswa1@simmagang.com', // Sesuaikan dengan MahasiswaSeeder
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'foto_profile' => 'andi.jpg',
                'no_telepon' => '082112345678',
                'alamat' => 'Jl. Gatot Subroto No. 5, Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dr. Siti Aminah',
                'username' => 'siti.aminah',
                'email' => 'siti.aminah@univ.ac.id',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'foto_profile' => 'siti.jpg',
                'no_telepon' => '081567890123',
                'alamat' => 'Jl. Diponegoro No. 20, Yogyakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rina Susanti',
                'username' => 'rina.susanti',
                'email' => 'rina.susanti@univ.ac.id',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'foto_profile' => 'rina.jpg',
                'no_telepon' => '082298765432',
                'alamat' => 'Jl. Ahmad Yani No. 15, Medan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            UsersModel::create($user);
        }

        $this->command->info('Data pengguna berhasil diimpor.');
    }
}