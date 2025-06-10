<?php

namespace Database\Seeders;

use App\Models\MahasiswaKeahlianModel;
use App\Models\MahasiswaModel;
use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;

class MahasiswaKeahlianSeeder extends Seeder
{
    public function run()
    {
        // Contoh array untuk MahasiswaKeahlianSeeder
        $mahasiswaKeahlian = [
            // mahasiswa_id => 1, kompetensi_id => 7 (Manajemen Database)
            ['mahasiswa_id' => 1, 'keahlian_id' => 3], // MySQL
            ['mahasiswa_id' => 1, 'keahlian_id' => 15], // PostgreSQL
            ['mahasiswa_id' => 1, 'keahlian_id' => 14], // MongoDB

            // mahasiswa_id => 2, kompetensi_id => 10 (Business Intelligence)
            ['mahasiswa_id' => 2, 'keahlian_id' => 33], // Python
            ['mahasiswa_id' => 2, 'keahlian_id' => 3], // MySQL
            ['mahasiswa_id' => 2, 'keahlian_id' => 39], // JavaScript

            // mahasiswa_id => 3, kompetensi_id => 5 (Blockchain Development)
            ['mahasiswa_id' => 3, 'keahlian_id' => 6], // Node.js
            ['mahasiswa_id' => 3, 'keahlian_id' => 39], // JavaScript

            // mahasiswa_id => 4, kompetensi_id => 13 (Mobile App Development)
            ['mahasiswa_id' => 4, 'keahlian_id' => 13], // Flutter
            ['mahasiswa_id' => 4, 'keahlian_id' => 11], // Kotlin
            ['mahasiswa_id' => 4, 'keahlian_id' => 12], // Swift

            // mahasiswa_id => 5, kompetensi_id => 8 (Cloud Computing)
            ['mahasiswa_id' => 5, 'keahlian_id' => 24], // AWS
            ['mahasiswa_id' => 5, 'keahlian_id' => 25], // Azure
            ['mahasiswa_id' => 5, 'keahlian_id' => 27], // Docker

            // mahasiswa_id => 6, kompetensi_id => 1 (Web Development)
            ['mahasiswa_id' => 6, 'keahlian_id' => 1], // Laravel
            ['mahasiswa_id' => 6, 'keahlian_id' => 3], // MySQL
            ['mahasiswa_id' => 6, 'keahlian_id' => 39], // JavaScript
            ['mahasiswa_id' => 6, 'keahlian_id' => 37], // HTML
            ['mahasiswa_id' => 6, 'keahlian_id' => 38], // CSS

            // mahasiswa_id => 7, kompetensi_id => 3 (Machine Learning)
            ['mahasiswa_id' => 7, 'keahlian_id' => 2], // TensorFlow
            ['mahasiswa_id' => 7, 'keahlian_id' => 33], // Python

            // mahasiswa_id => 8, kompetensi_id => 2 (Back End Development)
            ['mahasiswa_id' => 8, 'keahlian_id' => 6], // Node.js
            ['mahasiswa_id' => 8, 'keahlian_id' => 7], // Express.js
            ['mahasiswa_id' => 8, 'keahlian_id' => 3], // MySQL
            ['mahasiswa_id' => 8, 'keahlian_id' => 32], // PHP

            // mahasiswa_id => 9, kompetensi_id => 15 (Internet of Things)
            ['mahasiswa_id' => 9, 'keahlian_id' => 33], // Python
            ['mahasiswa_id' => 9, 'keahlian_id' => 36], // C++
            ['mahasiswa_id' => 9, 'keahlian_id' => 34], // Java

            // mahasiswa_id => 10, kompetensi_id => 11 (Front End Development)
            ['mahasiswa_id' => 10, 'keahlian_id' => 4], // React
            ['mahasiswa_id' => 10, 'keahlian_id' => 5], // Vue.js
            ['mahasiswa_id' => 10, 'keahlian_id' => 39], // JavaScript
            ['mahasiswa_id' => 10, 'keahlian_id' => 37], // HTML
            ['mahasiswa_id' => 10, 'keahlian_id' => 38], // CSS

            // mahasiswa_id => 11, kompetensi_id => 6 (Data Science)
            ['mahasiswa_id' => 11, 'keahlian_id' => 33], // Python
            ['mahasiswa_id' => 11, 'keahlian_id' => 3], // MySQL
            ['mahasiswa_id' => 11, 'keahlian_id' => 2], // TensorFlow

            // mahasiswa_id => 12, kompetensi_id => 14 (DevOps)
            ['mahasiswa_id' => 12, 'keahlian_id' => 27], // Docker
            ['mahasiswa_id' => 12, 'keahlian_id' => 28], // Kubernetes
            ['mahasiswa_id' => 12, 'keahlian_id' => 24], // AWS

            // mahasiswa_id => 13, kompetensi_id => 12 (UI/UX Design)
            ['mahasiswa_id' => 13, 'keahlian_id' => 37], // HTML
            ['mahasiswa_id' => 13, 'keahlian_id' => 38], // CSS
            ['mahasiswa_id' => 13, 'keahlian_id' => 39], // JavaScript

            // mahasiswa_id => 14, kompetensi_id => 9 (Enterprise System Integration)
            ['mahasiswa_id' => 14, 'keahlian_id' => 34], // Java
            ['mahasiswa_id' => 14, 'keahlian_id' => 10], // Spring Boot
            ['mahasiswa_id' => 14, 'keahlian_id' => 3], // MySQL

            // mahasiswa_id => 15, kompetensi_id => 4 (Cyber Security)
            ['mahasiswa_id' => 15, 'keahlian_id' => 33], // Python
            ['mahasiswa_id' => 15, 'keahlian_id' => 34], // Java
        ];

        foreach ($mahasiswaKeahlian as $item) {
            \App\Models\MahasiswaKeahlianModel::create($item);
        }
    }
}