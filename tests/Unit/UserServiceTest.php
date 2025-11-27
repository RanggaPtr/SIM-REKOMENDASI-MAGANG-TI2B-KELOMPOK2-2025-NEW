<?php

namespace Tests\Unit;

use App\Models\User;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Contoh unit test dengan pola AAA (Arrange, Act, Assert)
 * serta penggunaan Test Double (Mock & Stub) menggunakan PHPUnit + Mockery.
 */

// Interface repository (ilustrasi; dalam kasus nyata bisa berada di folder sendiri)
interface UserRepository
{
    public function find(int $id): User;
}

// Service sebagai SUT (System Under Test)
class UserService
{
    public function __construct(private UserRepository $repository) {}

    public function getUser(int $id): User
    {
        return $this->repository->find($id);
    }
}

class UserServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetUserReturnsCorrectDataWithMock()
    {
        // Arrange: siapkan Mock (verifikasi interaksi) dan instance service (SUT)
        $userRepoMock = Mockery::mock(UserRepository::class);
        // Laravel model baru tidak otomatis mengisi primary key; set manual agar dapat diuji
        $expectedUser = new User(['name' => 'Rizqi']);
        $expectedUser->id = 1;
        $userRepoMock
            ->shouldReceive('find')     // method yang diharapkan dipanggil
            ->once()                    // pastikan hanya sekali pemanggilan
            ->with(1)                   // verifikasi argumen
            ->andReturn($expectedUser); // nilai kembali (stub behavior dalam mock)

        $service = new UserService($userRepoMock);

        // Act: panggil method yang diuji
        $result = $service->getUser(1);

        // Assert: verifikasi tipe, nilai properti
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Rizqi', $result->name);
        $this->assertEquals(1, $result->id);
        // Interaksi sudah diverifikasi oleh shouldReceive->once()->with(...)
    }

    public function testGetUserReturnsCorrectDataWithStub()
    {
        // Arrange: gunakan Stub (hanya menyediakan return value, tanpa verifikasi interaksi)
        $userRepoStub = new class implements UserRepository {
            public function find(int $id): User
            {
                $user = new User(['name' => 'NamaStub']);
                $user->id = $id; // Set manual id karena belum persist ke DB
                return $user;
            }
        };
        $service = new UserService($userRepoStub);

        // Act: panggil method yang diuji
        $result = $service->getUser(7);

        // Assert: verifikasi hasil
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(7, $result->id);
        $this->assertEquals('NamaStub', $result->name);
    }
}

/*
Penjelasan Singkat:
- Arrange: Menyiapkan dependency (Mock atau Stub) dan SUT.
- Act: Menjalankan satu aksi utama (method yang diuji).
- Assert: Memastikan hasil & (untuk mock) interaksi terverifikasi.
Stub dipakai ketika hanya membutuhkan nilai kembali sederhana tanpa peduli jumlah/argumen pemanggilan.
Mock dipakai saat juga ingin memastikan kolaborasi (method dipanggil dengan argumen tertentu & jumlah panggilan tepat).
*/
