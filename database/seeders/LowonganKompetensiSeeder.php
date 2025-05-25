<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LowonganMagangModel;
use App\Models\KompetensiModel;

class LowonganKompetensiSeeder extends Seeder
{
    public function run()
    {
        // Format langsung pakai id urutan sesuai seeder
        $data = [
            // Magang Web Developer (id 1)
            ['lowongan_id' => 1, 'kompetensi_id' => 1], // Web Development
            ['lowongan_id' => 1, 'kompetensi_id' => 2], // Front End Development
            ['lowongan_id' => 1, 'kompetensi_id' => 3], // Back End Development

            // Magang Data Analyst (id 2)   
            ['lowongan_id' => 2, 'kompetensi_id' => 8], // Data Science

            // Magang Content Creator (id 3)
            ['lowongan_id' => 3, 'kompetensi_id' => 5], // UI/UX Design

            // Magang Mobile Developer (id 4)
            ['lowongan_id' => 4, 'kompetensi_id' => 4], // Mobile App Development

            // Magang Backend Developer (id 6)
            ['lowongan_id' => 6, 'kompetensi_id' => 3], // Back End Development

            // Magang UI/UX Designer (id 5)
            ['lowongan_id' => 5, 'kompetensi_id' => 5], // UI/UX Design
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('m_lowongan_kompetensi')->insert($data);
        $this->command->info('Data lowongan kompetensi berhasil diimpor.');
    }
}
