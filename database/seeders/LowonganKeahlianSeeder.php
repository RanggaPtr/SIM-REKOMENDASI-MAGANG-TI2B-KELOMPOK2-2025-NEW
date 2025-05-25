<?php

namespace Database\Seeders;

use App\Models\LowonganKeahlianModel;
use App\Models\LowonganMagangModel;
use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganKeahlianSeeder extends Seeder
{
    public function run()
    {
        // Format langsung pakai id urutan sesuai seeder
        $data = [
            // Magang Web Developer (id 1)
            ['lowongan_id' => 1, 'keahlian_id' => 1], // Laravel
            ['lowongan_id' => 1, 'keahlian_id' => 3], // MySQL
            ['lowongan_id' => 1, 'keahlian_id' => 37], // JavaScript

            // Magang Data Analyst (id 2)
            ['lowongan_id' => 2, 'keahlian_id' => 2], // TensorFlow
            ['lowongan_id' => 2, 'keahlian_id' => 8], // Django
            ['lowongan_id' => 2, 'keahlian_id' => 33], // Python

            // Magang Content Creator (id 3)
            ['lowongan_id' => 3, 'keahlian_id' => 5], // Vue.js
            ['lowongan_id' => 3, 'keahlian_id' => 4], // React

            // Magang Mobile Developer (id 4)
            ['lowongan_id' => 4, 'keahlian_id' => 13], // Flutter
            ['lowongan_id' => 4, 'keahlian_id' => 11], // Kotlin
            ['lowongan_id' => 4, 'keahlian_id' => 12], // Swift

            // Magang UI/UX Designer (id 5)
            ['lowongan_id' => 5, 'keahlian_id' => 20], // Bootstrap
            ['lowongan_id' => 5, 'keahlian_id' => 21], // Tailwind CSS

            // Magang Backend Developer (id 6)
            ['lowongan_id' => 6, 'keahlian_id' => 6], // Node.js
            ['lowongan_id' => 6, 'keahlian_id' => 7], // Express.js
            ['lowongan_id' => 6, 'keahlian_id' => 27], // Docker
        ];

        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('m_lowongan_keahlian')->insert($data);
        $this->command->info('Data lowongan keahlian berhasil diimpor.');
    }
}
