<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganKeahlianSeeder extends Seeder
{
    public function run()
    {
        // Data mapping antara lowongan dan keahlian
        $data = [
            ['lowongan_id' => 1, 'keahlian_id' => 1],  // Laravel
            ['lowongan_id' => 1, 'keahlian_id' => 3],  // MySQL
            ['lowongan_id' => 1, 'keahlian_id' => 37], // JavaScript
            ['lowongan_id' => 2, 'keahlian_id' => 2],  // TensorFlow
            ['lowongan_id' => 2, 'keahlian_id' => 8],  // Django
            ['lowongan_id' => 2, 'keahlian_id' => 33], // Python
            ['lowongan_id' => 3, 'keahlian_id' => 5],  // Vue.js
            ['lowongan_id' => 3, 'keahlian_id' => 4],  // React
            ['lowongan_id' => 4, 'keahlian_id' => 13], // Flutter
            ['lowongan_id' => 4, 'keahlian_id' => 11], // Kotlin
            ['lowongan_id' => 4, 'keahlian_id' => 12], // Swift
            ['lowongan_id' => 5, 'keahlian_id' => 20], // Bootstrap
            ['lowongan_id' => 5, 'keahlian_id' => 21], // Tailwind CSS
            ['lowongan_id' => 6, 'keahlian_id' => 6],  // Node.js
            ['lowongan_id' => 6, 'keahlian_id' => 7],  // Express.js
            ['lowongan_id' => 6, 'keahlian_id' => 27], // Docker
        ];

        // Tambah timestamp
        foreach ($data as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        // Insert ke tabel pivot 'm_lowongan_keahlian'
        DB::table('m_lowongan_keahlian')->insert($data);

        $this->command->info('Data lowongan keahlian berhasil diimpor.');
    }
}
