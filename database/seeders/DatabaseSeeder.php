<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
            ProgramStudiSeeder::class,
            WilayahSeeder::class,
            // MinatSeeder::class,
            SkemaSeeder::class,
            KompetensiSeeder::class,
            KeahlianSeeder::class,
            PeriodeMagangSeeder::class,
            PerusahaanSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            SertifikatDosenSeeder::class,
            LowonganMagangSeeder::class,
            MahasiswaKompetensiSeeder::class,
            MahasiswaKeahlianSeeder::class,
            LowonganKompetensiSeeder::class,
            LowonganKeahlianSeeder::class,
            PengajuanMagangSeeder::class,
            SertifikatMagangSeeder::class,
            EvaluasiMagangSeeder::class,
            BookmarkSeeder::class,
            LogAktivitasSeeder::class,
            SilabusKonversiSksSeeder::class
        ]);
    }
}
