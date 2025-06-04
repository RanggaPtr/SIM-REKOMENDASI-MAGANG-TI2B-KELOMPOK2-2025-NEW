<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaNotifikasiModel;

class MahasiswaNotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['ditolak', 'diterima'];
        $deskripsiSamples = [
            'Pengajuan magang telah diterima.',
            'Pengajuan magang ditolak karena dokumen tidak lengkap.',
            'Selamat, Anda lolos seleksi magang.',
            'Mohon lengkapi data untuk proses selanjutnya.',
            'Pengajuan magang Anda sedang diproses.',
            'Pengajuan magang ditolak karena IPK kurang.',
            'Pengajuan magang diterima di perusahaan mitra.',
            'Pengajuan magang ditolak karena kuota penuh.',
        ];

        $mahasiswas = MahasiswaModel::all();

        foreach ($mahasiswas as $mahasiswa) {
            // Setiap mahasiswa dapat 3-5 notifikasi acak
            $count = rand(3, 5);
            for ($i = 0; $i < $count; $i++) {
                MahasiswaNotifikasiModel::create([
                    'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                    'status' => $statuses[array_rand($statuses)],
                    'deskripsi' => $deskripsiSamples[array_rand($deskripsiSamples)],
                ]);
            }
        }
    }
}
