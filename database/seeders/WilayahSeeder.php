<?php

namespace Database\Seeders;

use App\Models\WilayahModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $jsonFile = Storage::path('data/regencies.json');

        if (file_exists($jsonFile)) {
            $json = file_get_contents($jsonFile);
            $data = json_decode($json, true);

            if (!$data || !is_array($data)) {
                $this->command->error('File JSON kosong atau tidak valid.');
                $this->importFallback();
                return;
            }

            foreach ($data as $row) {
                try {
                    // Pastikan field yang dibutuhkan ada
                    if (isset($row['id'], $row['name'], $row['latitude'], $row['longitude'])) {
                        WilayahModel::firstOrCreate(
                            ['kode_wilayah' => $row['id']],
                            [
                                'nama' => $row['name'],
                                'latitude' => $row['latitude'],
                                'longitude' => $row['longitude'],
                            ]
                        );
                    }
                } catch (\Exception $e) {
                    $this->command->warn('Gagal memproses data: ' . json_encode($row) . '. Error: ' . $e->getMessage());
                    continue;
                }
            }

            $this->command->info('Data wilayah berhasil diimpor dari file JSON.');
        } else {
            $this->command->error('File regencies.json tidak ditemukan di storage/app/data/.');
            $this->importFallback();
        }
    }

    private function importFallback(): void
    {
        WilayahModel::firstOrCreate(
            ['kode_wilayah' => '3171'],
            ['nama' => 'KOTA JAKARTA PUSAT', 'latitude' => -6.1777, 'longitude' => 106.8403]
        );
        WilayahModel::firstOrCreate(
            ['kode_wilayah' => '3273'],
            ['nama' => 'KOTA BANDUNG', 'latitude' => -6.9175, 'longitude' => 107.62444]
        );
        WilayahModel::firstOrCreate(
            ['kode_wilayah' => '3578'],
            ['nama' => 'KOTA SURABAYA', 'latitude' => -7.26667, 'longitude' => 112.71667]
        );
        $this->command->info('Data wilayah fallback berhasil diimpor.');
    }
}