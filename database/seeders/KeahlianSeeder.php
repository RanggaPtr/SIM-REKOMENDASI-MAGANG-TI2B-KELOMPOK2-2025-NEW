<?php

namespace Database\Seeders;

use App\Models\KeahlianModel;
use Illuminate\Database\Seeder;

class KeahlianSeeder extends Seeder
{
    public function run()
    {
        $keahlian = [
            ['nama' => 'Laravel', 'deskripsi' => 'Framework PHP untuk pengembangan web'],
            ['nama' => 'TensorFlow', 'deskripsi' => 'Library untuk machine learning'],
            ['nama' => 'MySQL', 'deskripsi' => 'Sistem manajemen database relasional'],
            ['nama' => 'React', 'deskripsi' => 'Library JavaScript untuk membangun antarmuka pengguna'],
            ['nama' => 'Vue.js', 'deskripsi' => 'Framework JavaScript progresif untuk membangun UI'],
            ['nama' => 'Node.js', 'deskripsi' => 'JavaScript runtime untuk pengembangan backend'],
            ['nama' => 'Express.js', 'deskripsi' => 'Framework backend untuk Node.js'],
            ['nama' => 'Django', 'deskripsi' => 'Framework Python untuk pengembangan web'],
            ['nama' => 'Flask', 'deskripsi' => 'Micro-framework Python untuk web'],
            ['nama' => 'Spring Boot', 'deskripsi' => 'Framework Java untuk aplikasi enterprise'],
            ['nama' => 'Kotlin', 'deskripsi' => 'Bahasa pemrograman modern untuk Android dan backend'],
            ['nama' => 'Swift', 'deskripsi' => 'Bahasa pemrograman utama untuk iOS'],
            ['nama' => 'Flutter', 'deskripsi' => 'Framework UI untuk aplikasi mobile multiplatform'],
            ['nama' => 'MongoDB', 'deskripsi' => 'Database NoSQL dokumen'],
            ['nama' => 'PostgreSQL', 'deskripsi' => 'Sistem manajemen database relasional open source'],
            ['nama' => 'Redis', 'deskripsi' => 'Database key-value in-memory'],
            ['nama' => 'Golang', 'deskripsi' => 'Bahasa pemrograman efisien untuk backend dan cloud'],
            ['nama' => 'Rust', 'deskripsi' => 'Bahasa pemrograman sistem yang aman dan cepat'],
            ['nama' => 'TypeScript', 'deskripsi' => 'Superset JavaScript dengan tipe statis'],
            ['nama' => 'Bootstrap', 'deskripsi' => 'Framework CSS untuk desain responsif'],
            ['nama' => 'Tailwind CSS', 'deskripsi' => 'Framework utility-first CSS'],
            ['nama' => 'Next.js', 'deskripsi' => 'Framework React untuk aplikasi web modern'],
            ['nama' => 'Nuxt.js', 'deskripsi' => 'Framework Vue untuk SSR dan SPA'],
            ['nama' => 'AWS', 'deskripsi' => 'Layanan cloud dari Amazon'],
            ['nama' => 'Azure', 'deskripsi' => 'Layanan cloud dari Microsoft'],
            ['nama' => 'Firebase', 'deskripsi' => 'Platform backend dan hosting dari Google'],
            ['nama' => 'Docker', 'deskripsi' => 'Platform containerisasi aplikasi'],
            ['nama' => 'Kubernetes', 'deskripsi' => 'Orkestrasi container untuk deployment skala besar'],
            ['nama' => 'GraphQL', 'deskripsi' => 'Query language untuk API'],
            ['nama' => 'SASS', 'deskripsi' => 'Preprocessor CSS'],
            ['nama' => 'jQuery', 'deskripsi' => 'Library JavaScript untuk manipulasi DOM'],
            ['nama' => 'PHP', 'deskripsi' => 'Bahasa pemrograman untuk web development'],
            ['nama' => 'Python', 'deskripsi' => 'Bahasa pemrograman serbaguna'],
            ['nama' => 'Java', 'deskripsi' => 'Bahasa pemrograman untuk aplikasi enterprise dan Android'],
            ['nama' => 'C#', 'deskripsi' => 'Bahasa pemrograman untuk .NET dan aplikasi Windows'],
            ['nama' => 'C++', 'deskripsi' => 'Bahasa pemrograman untuk aplikasi performa tinggi'],
            ['nama' => 'HTML', 'deskripsi' => 'Bahasa markup untuk struktur halaman web'],
            ['nama' => 'CSS', 'deskripsi' => 'Bahasa stylesheet untuk desain halaman web'],
            ['nama' => 'JavaScript', 'deskripsi' => 'Bahasa pemrograman utama untuk web'],
        ];

        foreach ($keahlian as $item) {
            KeahlianModel::create($item);
        }
    }
}