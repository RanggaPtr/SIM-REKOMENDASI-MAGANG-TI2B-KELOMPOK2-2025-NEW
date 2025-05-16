<?php

namespace Database\Seeders;

use App\Models\WilayahModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class WilayahSeeder extends Seeder
{
   public function run(): void
    {
        $csvFile = Storage::path('data/regencies.csv');

        if (file_exists($csvFile)) {
            $file = fopen($csvFile, 'r');

            // Lewati baris kosong atau komentar jika ada
            $row = fgetcsv($file);
            while ($row && (empty($row[0]) || $row[0][0] === '#')) {
                $row = fgetcsv($file);
            }

            if ($row === false) {
                $this->command->error('File CSV kosong atau tidak valid.');
                $this->importFallback();
                return;
            }

            do {
                try {
                    // Asumsi: kolom 0 = kode_wilayah, kolom 2 = nama (lewati province_id di kolom 1)
                    if (isset($row[0]) && isset($row[2])) {
                        WilayahModel::firstOrCreate(
                            ['kode_wilayah' => $row[0]],
                            ['nama' => $row[2]]
                        );
                    }
                } catch (\Exception $e) {
                    $this->command->warn('Gagal memproses baris: ' . implode(',', $row) . '. Error: ' . $e->getMessage());
                    continue;
                }
            } while (($row = fgetcsv($file)) !== false);

            fclose($file);
            $this->command->info('Data wilayah berhasil diimpor dari file CSV.');
        } else {
            $this->command->error('File regencies.csv tidak ditemukan di storage/app/data/.');
            $this->importFallback();
        }
    }

    private function importFallback(): void
    {
        WilayahModel::firstOrCreate(['kode_wilayah' => '3171'], ['nama' => 'KOTA JAKARTA PUSAT']);
        WilayahModel::firstOrCreate(['kode_wilayah' => '3273'], ['nama' => 'KOTA BANDUNG']);
        WilayahModel::firstOrCreate(['kode_wilayah' => '3578'], ['nama' => 'KOTA MALANG']);
        $this->command->info('Data wilayah fallback berhasil diimpor.');
    }
}