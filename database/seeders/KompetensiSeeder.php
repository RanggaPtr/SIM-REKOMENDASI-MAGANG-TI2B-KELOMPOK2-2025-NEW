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
            [
                'nama' => 'Game Development',
                'deskripsi' => 'Kemampuan membangun game menggunakan Unity, Unreal Engine, atau semacamnya',
                'program_studi_id' => $prodiIds[1]
            ],
            [
                'nama' => 'Digital Content Creation',
                'deskripsi' => 'Kemampuan membuat konten digital kreatif untuk berbagai platform.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Quality Assurance',
                'deskripsi' => 'Kemampuan melakukan pengujian perangkat lunak dan memastikan kualitas produk.',
                'program_studi_id' => 1
            ],
            [
                'nama' => 'Network Engineering',
                'deskripsi' => 'Kemampuan merancang dan mengelola jaringan komputer.',
                'program_studi_id' => 1
            ],
            [
                'nama' => 'IT Support',
                'deskripsi' => 'Kemampuan memberikan dukungan teknis dan troubleshooting.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Digital Marketing',
                'deskripsi' => 'Kemampuan menjalankan kampanye pemasaran digital.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'System Analysis',
                'deskripsi' => 'Kemampuan menganalisis kebutuhan sistem dan bisnis.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Audit',
                'deskripsi' => 'Kemampuan melakukan audit sistem informasi.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'SEO Specialist',
                'deskripsi' => 'Kemampuan mengoptimasi website untuk mesin pencari.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Social Media Management',
                'deskripsi' => 'Kemampuan mengelola akun dan kampanye media sosial.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Project Management',
                'deskripsi' => 'Kemampuan mengelola proyek IT dari awal hingga akhir.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Full Stack Development',
                'deskripsi' => 'Kemampuan membangun aplikasi dari sisi frontend dan backend.',
                'program_studi_id' => 1
            ],
            [
                'nama' => 'IT Consulting',
                'deskripsi' => 'Kemampuan memberikan konsultasi solusi IT.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Technical Writing',
                'deskripsi' => 'Kemampuan menulis dokumentasi teknis.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Training',
                'deskripsi' => 'Kemampuan memberikan pelatihan IT.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Video Editing',
                'deskripsi' => 'Kemampuan mengedit dan memproduksi video.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Research',
                'deskripsi' => 'Kemampuan melakukan riset di bidang teknologi informasi.',
                'program_studi_id' => 1
            ],
            [
                'nama' => 'Technical Documentation',
                'deskripsi' => 'Kemampuan membuat dokumentasi teknis.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Procurement',
                'deskripsi' => 'Kemampuan pengadaan barang dan jasa IT.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Business Analysis',
                'deskripsi' => 'Kemampuan menganalisis proses bisnis dan kebutuhan IT.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Infrastructure',
                'deskripsi' => 'Kemampuan membangun dan memelihara infrastruktur IT.',
                'program_studi_id' => 1
            ],
            [
                'nama' => 'Product Management',
                'deskripsi' => 'Kemampuan mengelola siklus hidup produk digital.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'System Engineering',
                'deskripsi' => 'Kemampuan merancang dan mengelola sistem IT.',
                'program_studi_id' => 1
            ],
            [
                'nama' => 'Data Entry',
                'deskripsi' => 'Kemampuan input dan validasi data.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Asset Management',
                'deskripsi' => 'Kemampuan mengelola aset IT.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Compliance',
                'deskripsi' => 'Kemampuan memastikan kepatuhan sistem IT.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'IT Operations',
                'deskripsi' => 'Kemampuan menjalankan operasional harian IT.',
                'program_studi_id' => 1
            ],
            [
                'nama' => 'Event Management',
                'deskripsi' => 'Kemampuan mengelola acara dan event IT.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Customer Support',
                'deskripsi' => 'Kemampuan memberikan dukungan kepada pelanggan.',
                'program_studi_id' => 2
            ],
            [
                'nama' => 'Product Design',
                'deskripsi' => 'Kemampuan mendesain produk digital.',
                'program_studi_id' => 3
            ],
        ];

        // Insert competencies into the database
        foreach ($kompetensi as $item) {
            KompetensiModel::create($item);
        }
    }
}
