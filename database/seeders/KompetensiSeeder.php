<?php

namespace Database\Seeders;

use App\Models\KompetensiModel;
use App\Models\ProgramStudiModel;
use Illuminate\Database\Seeder;

class KompetensiSeeder extends Seeder
{
    public function run()
    {
        // Get all program studi IDs
        $prodiIds = ProgramStudiModel::pluck('prodi_id')->toArray();

        // Ensure there are enough program studi
        if (count($prodiIds) < 3) {
            throw new \Exception('Not enough Program Studi records found. Please run ProgramStudiSeeder first.');
        }

        // Define competencies with relevant assignments to each program
        $kompetensi = [
            // D-IV Teknik Informatika (prodiIds[0]) - Technical and development-focused
            [
                'nama' => 'Web Development',
                'deskripsi' => 'Kemampuan membangun aplikasi web modern',
                'program_studi_id' => $prodiIds[0]
            ],
            [
                'nama' => 'Back End Development',
                'deskripsi' => 'Kemampuan membangun API dan server-side logic',
                'program_studi_id' => $prodiIds[0]
            ],
            [
                'nama' => 'Machine Learning',
                'deskripsi' => 'Kemampuan membangun dan menerapkan model machine learning',
                'program_studi_id' => $prodiIds[0]
            ],
            [
                'nama' => 'Cyber Security',
                'deskripsi' => 'Kemampuan menjaga keamanan sistem dan data',
                'program_studi_id' => $prodiIds[0]
            ],
            [
                'nama' => 'Blockchain Development',
                'deskripsi' => 'Kemampuan membangun aplikasi berbasis blockchain dan smart contract',
                'program_studi_id' => $prodiIds[0]
            ],
            // D-IV Sistem Informasi Bisnis (prodiIds[1]) - Business-oriented IT and data-focused
            [
                'nama' => 'Data Science',
                'deskripsi' => 'Kemampuan analisis data dan visualisasi',
                'program_studi_id' => $prodiIds[1]
            ],
            [
                'nama' => 'Manajemen Database',
                'deskripsi' => 'Kemampuan mengelola dan merancang database relasional dan non-relasional',
                'program_studi_id' => $prodiIds[1]
            ],
            [
                'nama' => 'Cloud Computing',
                'deskripsi' => 'Kemampuan menggunakan layanan cloud seperti AWS, Azure, GCP',
                'program_studi_id' => $prodiIds[1]
            ],
            [
                'nama' => 'Enterprise System Integration',
                'deskripsi' => 'Kemampuan mengintegrasikan sistem perusahaan seperti ERP dan CRM',
                'program_studi_id' => $prodiIds[1]
            ],
            [
                'nama' => 'Business Intelligence',
                'deskripsi' => 'Kemampuan mengembangkan solusi untuk analisis dan pelaporan bisnis',
                'program_studi_id' => $prodiIds[1]
            ],
            // D-II Perangkat Piranti Lunak Situs (prodiIds[2]) - Web and software development-focused
            [
                'nama' => 'Front End Development',
                'deskripsi' => 'Kemampuan membangun antarmuka pengguna dengan HTML, CSS, dan JavaScript',
                'program_studi_id' => $prodiIds[2]
            ],
            [
                'nama' => 'UI/UX Design',
                'deskripsi' => 'Kemampuan mendesain pengalaman dan antarmuka pengguna',
                'program_studi_id' => $prodiIds[2]
            ],
            [
                'nama' => 'Mobile App Development',
                'deskripsi' => 'Kemampuan membuat aplikasi mobile Android/iOS',
                'program_studi_id' => $prodiIds[2]
            ],
            [
                'nama' => 'DevOps',
                'deskripsi' => 'Kemampuan mengelola deployment, CI/CD, dan cloud infrastructure',
                'program_studi_id' => $prodiIds[2]
            ],
            [
                'nama' => 'Internet of Things (IoT)',
                'deskripsi' => 'Kemampuan membangun solusi perangkat terhubung',
                'program_studi_id' => $prodiIds[2]
            ],
        ];

        // Insert competencies into the database
        foreach ($kompetensi as $item) {
            KompetensiModel::create($item);
        }
    }
}