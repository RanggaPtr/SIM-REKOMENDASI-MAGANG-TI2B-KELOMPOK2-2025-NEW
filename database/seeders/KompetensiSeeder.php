<?php

namespace Database\Seeders;

use App\Models\KompetensiModel;
use Illuminate\Database\Seeder;

class KompetensiSeeder extends Seeder
{
    public function run()
    {
        $kompetensi = [
            ['nama' => 'Web Development', 'deskripsi' => 'Kemampuan membangun aplikasi web modern'],
            ['nama' => 'Front End Development', 'deskripsi' => 'Kemampuan membangun antarmuka pengguna dengan HTML, CSS, dan JavaScript'],
            ['nama' => 'Back End Development', 'deskripsi' => 'Kemampuan membangun API dan server-side logic'],
            ['nama' => 'Mobile App Development', 'deskripsi' => 'Kemampuan membuat aplikasi mobile Android/iOS'],
            ['nama' => 'UI/UX Design', 'deskripsi' => 'Kemampuan mendesain pengalaman dan antarmuka pengguna'],
            ['nama' => 'DevOps', 'deskripsi' => 'Kemampuan mengelola deployment, CI/CD, dan cloud infrastructure'],
            ['nama' => 'Machine Learning', 'deskripsi' => 'Kemampuan membangun dan menerapkan model machine learning'],
            ['nama' => 'Data Science', 'deskripsi' => 'Kemampuan analisis data dan visualisasi'],
            ['nama' => 'Cyber Security', 'deskripsi' => 'Kemampuan menjaga keamanan sistem dan data'],
            ['nama' => 'Blockchain Development', 'deskripsi' => 'Kemampuan membangun aplikasi berbasis blockchain dan smart contract'],
            ['nama' => 'Cloud Computing', 'deskripsi' => 'Kemampuan menggunakan layanan cloud seperti AWS, Azure, GCP'],
            ['nama' => 'Internet of Things (IoT)', 'deskripsi' => 'Kemampuan membangun solusi perangkat terhubung'],
            ['nama' => 'Manajemen Database', 'deskripsi' => 'Kemampuan mengelola dan merancang database relasional dan non-relasional'],
        ];

        foreach ($kompetensi as $item) {
            KompetensiModel::create($item);
        }
    }
}
