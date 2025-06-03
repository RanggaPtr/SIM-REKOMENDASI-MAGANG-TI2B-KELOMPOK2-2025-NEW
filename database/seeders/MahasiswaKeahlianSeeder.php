<?php

namespace Database\Seeders;

use App\Models\MahasiswaKeahlianModel;
use App\Models\MahasiswaModel;
use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;

class MahasiswaKeahlianSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua mahasiswa
        $mahasiswas = MahasiswaModel::all();

        // Daftar keahlian untuk dummy (bisa disesuaikan)
        $keahlianSets = [
            ['Laravel', 'MySQL', 'JavaScript'],
            ['React', 'Node.js', 'MongoDB'],
            ['Python', 'Django', 'TensorFlow'],
            ['Java', 'Spring Boot', 'Kotlin'],
            ['Flutter', 'Firebase', 'Swift'],
        ];

        foreach ($mahasiswas as $idx => $mahasiswa) {
            // Pilih keahlian set berdasarkan urutan mahasiswa
            $set = $keahlianSets[$idx % count($keahlianSets)];
            foreach ($set as $namaKeahlian) {
                $keahlian = KeahlianModel::where('nama', $namaKeahlian)->first();
                if ($keahlian) {
                    MahasiswaKeahlianModel::create([
                        'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                        'keahlian_id' => $keahlian->keahlian_id
                    ]);
                }
            }
        }

        $this->command->info('Data mahasiswa keahlian berhasil diimpor.');
    }
}