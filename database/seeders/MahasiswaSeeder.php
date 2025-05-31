<?php

namespace Database\Seeders;

use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use App\Models\SkemaModel;
use App\Models\WilayahModel;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $user = UsersModel::where('email', 'mahasiswa1@simmagang.com')->first();
        $programStudi = ProgramStudiModel::where('nama', 'D-IV Teknik Informatika')->first();
        $wilayah = WilayahModel::where('nama', 'KOTA MALANG')->first();
        $skema = SkemaModel::where('nama', 'Magang Reguler')->first();

        if (!$user) {
            $this->command->error('Pengguna dengan email mahasiswa1@simmagang.com tidak ditemukan. Pastikan UsersSeeder membuat pengguna ini.');
            return;
        }

        if (!$programStudi) {
            $this->command->error('Program studi D-IV Teknik Informatika tidak ditemukan. Pastikan ProgramStudiSeeder membuat data ini.');
            return;
        }

        if (!$wilayah) {
            $this->command->error('Wilayah KOTA MALANG tidak ditemukan. Pastikan WilayahSeeder membuat data ini.');
            return;
        }

        if (!$skema) {
            $this->command->error('Skema Magang Reguler tidak ditemukan. Pastikan SkemaSeeder membuat data ini.');
            return;
        }

        MahasiswaModel::create([
            'user_id' => $user->user_id,
            'nim' => '123456789',
            'program_studi_id' => $programStudi->prodi_id,
            'wilayah_id' => $wilayah->wilayah_id,
            'skema_id' => $skema->skema_id,
            'periode_id' => 1,
            'ipk' => 3.75
        ]);

        $this->command->info('Data mahasiswa berhasil diimpor.');
    }
}