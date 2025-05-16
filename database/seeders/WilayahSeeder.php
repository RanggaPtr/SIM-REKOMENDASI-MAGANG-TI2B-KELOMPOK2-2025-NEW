<?php

namespace Database\Seeders;

use App\Models\WilayahModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file SQL
        $sqlFilePath = database_path('seeders/wilayah.sql');

        if (File::exists($sqlFilePath)) {
            // Baca file SQL
            $sql = File::get($sqlFilePath);
            $lines = explode("\n", $sql);

            // Proses setiap baris untuk mengekstrak data
            $data = [];
            foreach ($lines as $line) {
                // Lewati baris kosong atau baris yang tidak relevan
                $line = trim($line);
                if (empty($line) || strpos($line, 'INSERT INTO') === false) {
                    continue;
                }

                // Ekstrak nilai dari perintah INSERT menggunakan regex
                preg_match_all("/\('(.*?)','(.*?)'\)/", $line, $matches);
                for ($i = 0; $i < count($matches[1]); $i++) {
                    $data[] = [
                        'kode_wilayah' => $matches[1][$i],
                        'nama' => $matches[2][$i],
                    ];
                }
            }

            // Masukkan data menggunakan Eloquent
            foreach ($data as $item) {
                WilayahModel::create([
                    'kode_wilayah' => $item['kode_wilayah'],
                    'nama' => $item['nama'],
                ]);
            }

            $this->command->info('Data wilayah berhasil dimasukkan menggunakan Eloquent.');
        } else {
            $this->command->info('File wilayah.sql tidak ditemukan. Menambahkan data manual sebagai contoh.');
            WilayahModel::create(['nama' => 'Aceh', 'kode_wilayah' => '11']);
            WilayahModel::create(['nama' => 'Sumatera Utara', 'kode_wilayah' => '12']);
        }
    }
}