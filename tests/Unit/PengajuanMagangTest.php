<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MahasiswaModel;
use App\Models\PengajuanMagangModel;
use App\Models\LowonganMagangModel;
use App\Models\UsersModel;
use App\Models\ProgramStudiModel;
use App\Models\WilayahModel;
use App\Models\SkemaModel;
use App\Models\PeriodeMagangModel;
use App\Models\PerusahaanModel;
use App\Http\Controllers\Mahasiswa\PengajuanMagangController;

class PengajuanMagangTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store_successful_pengajuan()
    {
        // -------------------------------
        // ARRANGE
        // -------------------------------
        // Gunakan data yang sudah ada dari seeder
        $prodi = ProgramStudiModel::first();
        $wilayah = WilayahModel::first();
        $skema = SkemaModel::first();
        $periode = PeriodeMagangModel::first();
        $perusahaan = PerusahaanModel::first();

        // Buat user dan mahasiswa
        $user = UsersModel::create([
            'username' => 'testmahasiswa',
            'nama' => 'Test Mahasiswa',
            'email' => 'testmahasiswa@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa'
        ]);

        $mahasiswa = MahasiswaModel::create([
            'user_id' => $user->user_id,
            'nim' => '2241720001',
            'program_studi_id' => $prodi->prodi_id,
            'wilayah_id' => $wilayah->wilayah_id,
            'skema_id' => $skema->skema_id,
            'ipk' => 3.5,
            'periode_id' => $periode->periode_id
        ]);

        // Buat lowongan
        $lowongan = LowonganMagangModel::create([
            'perusahaan_id' => $perusahaan->perusahaan_id,
            'judul' => 'Backend Developer',
            'deskripsi' => 'Test lowongan',
            'persyaratan' => 'Test persyaratan',
            'tunjangan' => true,
            'kuota' => 5,
            'periode_id' => $periode->periode_id,
            'skema_id' => $skema->skema_id,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30)
        ]);

        // Login sebagai mahasiswa
        Auth::shouldReceive('id')
            ->andReturn($user->user_id);

        // Mock request
        $request = new Request(['lowongan_id' => $lowongan->lowongan_id]);

        // -------------------------------
        // ACT
        // -------------------------------
        $controller = new PengajuanMagangController();
        $response = $controller->store($request);

        // -------------------------------
        // ASSERT
        // -------------------------------
        $this->assertEquals(302, $response->getStatusCode()); // redirect
        
        // Cek apakah data tersimpan di database
        $this->assertDatabaseHas('t_pengajuan_magang', [
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => $lowongan->lowongan_id,
            'status' => 'diajukan'
        ]);
    }

    public function test_store_pengajuan_duplicate()
    {
        // -------------------------------
        // ARRANGE
        // -------------------------------
        $prodi = ProgramStudiModel::first();
        $wilayah = WilayahModel::first();
        $skema = SkemaModel::first();
        $periode = PeriodeMagangModel::first();
        $perusahaan = PerusahaanModel::first();

        $user = UsersModel::create([
            'username' => 'testmahasiswa2',
            'nama' => 'Test Mahasiswa 2',
            'email' => 'testmahasiswa2@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa'
        ]);

        $mahasiswa = MahasiswaModel::create([
            'user_id' => $user->user_id,
            'nim' => '2241720002',
            'program_studi_id' => $prodi->prodi_id,
            'wilayah_id' => $wilayah->wilayah_id,
            'skema_id' => $skema->skema_id,
            'ipk' => 3.5,
            'periode_id' => $periode->periode_id
        ]);

        $lowongan = LowonganMagangModel::create([
            'perusahaan_id' => $perusahaan->perusahaan_id,
            'judul' => 'Frontend Developer',
            'deskripsi' => 'Test lowongan',
            'persyaratan' => 'Test persyaratan',
            'tunjangan' => true,
            'kuota' => 5,
            'periode_id' => $periode->periode_id,
            'skema_id' => $skema->skema_id,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30)
        ]);

        // Buat pengajuan yang sudah ada
        PengajuanMagangModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => $lowongan->lowongan_id,
            'status' => 'diajukan'
        ]);

        Auth::shouldReceive('id')
            ->andReturn($user->user_id);

        $request = new Request(['lowongan_id' => $lowongan->lowongan_id]);

        // -------------------------------
        // ACT
        // -------------------------------
        $controller = new PengajuanMagangController();
        $response = $controller->store($request);

        // -------------------------------
        // ASSERT
        // -------------------------------
        $this->assertTrue(session()->has('error'));
        $this->assertStringContainsString('sudah mengajukan', session('error'));
    }

    public function test_store_pengajuan_sudah_diterima()
    {
        // -------------------------------
        // ARRANGE
        // -------------------------------
        $prodi = ProgramStudiModel::first();
        $wilayah = WilayahModel::first();
        $skema = SkemaModel::first();
        $periode = PeriodeMagangModel::first();
        $perusahaan = PerusahaanModel::first();

        $user = UsersModel::create([
            'username' => 'testmahasiswa3',
            'nama' => 'Test Mahasiswa 3',
            'email' => 'testmahasiswa3@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa'
        ]);

        $mahasiswa = MahasiswaModel::create([
            'user_id' => $user->user_id,
            'nim' => '2241720003',
            'program_studi_id' => $prodi->prodi_id,
            'wilayah_id' => $wilayah->wilayah_id,
            'skema_id' => $skema->skema_id,
            'ipk' => 3.5,
            'periode_id' => $periode->periode_id
        ]);

        $lowongan1 = LowonganMagangModel::create([
            'perusahaan_id' => $perusahaan->perusahaan_id,
            'judul' => 'DevOps Engineer',
            'deskripsi' => 'Test lowongan',
            'persyaratan' => 'Test persyaratan',
            'tunjangan' => true,
            'kuota' => 5,
            'periode_id' => $periode->periode_id,
            'skema_id' => $skema->skema_id,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30)
        ]);

        $lowongan2 = LowonganMagangModel::create([
            'perusahaan_id' => $perusahaan->perusahaan_id,
            'judul' => 'QA Engineer',
            'deskripsi' => 'Test lowongan',
            'persyaratan' => 'Test persyaratan',
            'tunjangan' => false,
            'kuota' => 5,
            'periode_id' => $periode->periode_id,
            'skema_id' => $skema->skema_id,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30)
        ]);

        // Buat pengajuan yang sudah diterima
        PengajuanMagangModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => $lowongan1->lowongan_id,
            'status' => 'diterima'
        ]);

        Auth::shouldReceive('id')
            ->andReturn($user->user_id);

        // Coba ajukan lowongan baru
        $request = new Request(['lowongan_id' => $lowongan2->lowongan_id]);

        // -------------------------------
        // ACT
        // -------------------------------
        $controller = new PengajuanMagangController();
        $response = $controller->store($request);

        // -------------------------------
        // ASSERT
        // -------------------------------
        $this->assertTrue(session()->has('error'));
        $this->assertStringContainsString('sudah memiliki magang', session('error'));
    }
}
