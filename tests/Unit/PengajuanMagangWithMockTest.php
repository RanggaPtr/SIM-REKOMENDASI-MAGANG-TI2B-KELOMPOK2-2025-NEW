<?php
namespace Tests\Unit;

use App\Models\PengajuanMagangModel;
use Mockery;
use PHPUnit\Framework\TestCase;

interface PengajuanMagangRepository
{
    public function create(array $data): PengajuanMagangModel;
}

class PengajuanMagangService
{
    public function __construct(private PengajuanMagangRepository $repository) {}

    public function ajukan(int $mahasiswaId, int $lowonganId): PengajuanMagangModel
    {
        return $this->repository->create([
            'mahasiswa_id' => $mahasiswaId,
            'lowongan_id' => $lowonganId,
            'status' => 'DIAJUKAN'
        ]);
    }
}

class PengajuanMagangWithMockTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testAjukanMengembalikanModelDenganMock()
    {
        // Arrange: siapkan Mock repository & service (SUT)
        $repoMock = Mockery::mock(PengajuanMagangRepository::class);
        $model = new PengajuanMagangModel([
            'mahasiswa_id' => 10,
            'lowongan_id' => 99,
            'status' => 'DIAJUKAN'
        ]);
        $model->pengajuan_id = 1234;

        $repoMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['mahasiswa_id'], $arg['lowongan_id'], $arg['status'])
                    && $arg['mahasiswa_id'] === 10
                    && $arg['lowongan_id'] === 99
                    && $arg['status'] === 'DIAJUKAN';
            }))
            ->andReturn($model);

        $service = new PengajuanMagangService($repoMock);

        // Act
        $result = $service->ajukan(10, 99);

        // Assert
        $this->assertInstanceOf(PengajuanMagangModel::class, $result);
        $this->assertEquals(1234, $result->pengajuan_id);
        $this->assertEquals(10, $result->mahasiswa_id);
        $this->assertEquals(99, $result->lowongan_id);
        $this->assertEquals('DIAJUKAN', $result->status);
    }

    public function testAjukanMengembalikanModelDenganStub()
    {
        // Arrange: Stub repository
        $stubRepo = new class implements PengajuanMagangRepository {
            public function create(array $data): PengajuanMagangModel
            {
                $model = new PengajuanMagangModel($data);
                $model->pengajuan_id = 5678;
                return $model;
            }
        };
        $service = new PengajuanMagangService($stubRepo);

        // Act
        $result = $service->ajukan(20, 77);

        // Assert
        $this->assertInstanceOf(PengajuanMagangModel::class, $result);
        $this->assertEquals(5678, $result->pengajuan_id);
        $this->assertEquals(20, $result->mahasiswa_id);
        $this->assertEquals(77, $result->lowongan_id);
        $this->assertEquals('DIAJUKAN', $result->status);
    }
}

/*
AAA:
- Arrange: siapkan dependency (Mock/Stub) + SUT.
- Act: panggil method ajukan.
- Assert: verifikasi data & (untuk mock) interaksi via shouldReceive.
Stub untuk return sederhana; Mock untuk verifikasi kolaborasi.
*/
