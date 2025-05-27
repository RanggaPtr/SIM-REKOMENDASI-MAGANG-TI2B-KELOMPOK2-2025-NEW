<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganKompetensiSeeder extends Seeder
{
    public function run()
    {
        // Data mapping antara lowongan dan kompetensi
        $data = [
            ['lowongan_id' => 1, 'kompetensi_id' => 1], // Web Development
            ['lowongan_id' => 1, 'kompetensi_id' => 2], // Front End Development
            ['lowongan_id' => 1, 'kompetensi_id' => 3], // Back End Development
            ['lowongan_id' => 2, 'kompetensi_id' => 8], // Data Science
            ['lowongan_id' => 3, 'kompetensi_id' => 5], // UI/UX Design
            ['lowongan_id' => 4, 'kompetensi_id' => 4], // Mobile App Development
            ['lowongan_id' => 6, 'kompetensi_id' => 3], // Back End Development
            ['lowongan_id' => 5, 'kompetensi_id' => 5], // UI/UX Design
        ];

        // Tambah timestamp
        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        // Insert ke tabel pivot 'm_lowongan_kompetensi'
        DB::table('m_lowongan_kompetensi')->insert($data);

        $this->command->info('Data lowongan kompetensi berhasil diimpor.');
    }
}
