<?php

namespace Database\Seeders;

use App\Models\MahasiswaKompetensiModel;
use App\Models\MahasiswaModel;
use App\Models\KompetensiModel;
use Illuminate\Database\Seeder;

class MahasiswaKompetensiSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua mahasiswa
        $mahasiswas = MahasiswaModel::all();

        // Daftar kompetensi untuk dummy (bisa disesuaikan)
        $kompetensiSets = [
            ['Web Development', 'Back End Development'],
            ['Data Science', 'Business Intelligence'],
            ['Front End Development', 'UI/UX Design'],
            ['Machine Learning', 'Cyber Security'],
            ['Mobile App Development', 'DevOps'],
        ];

        foreach ($mahasiswas as $idx => $mahasiswa) {
            // Pilih kompetensi set berdasarkan urutan mahasiswa
            $set = $kompetensiSets[$idx % count($kompetensiSets)];
            foreach ($set as $namaKompetensi) {
                $kompetensi = KompetensiModel::where('nama', $namaKompetensi)->first();
                if ($kompetensi) {
                    MahasiswaKompetensiModel::create([
                        'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                        'kompetensi_id' => $kompetensi->kompetensi_id
                    ]);
                }
            }
        }

        $this->command->info('Data mahasiswa kompetensi berhasil diimpor.');
    }
}
