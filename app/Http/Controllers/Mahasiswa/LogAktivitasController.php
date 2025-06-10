<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitasModel;
use App\Models\PengajuanMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogAktivitasController extends Controller
{
    /**
     * Mendapatkan pengajuan magang diterima mahasiswa saat login
     */
    private function getPengajuanDiterima()
    {
        $mahasiswaId = Auth::user()->mahasiswa_id ?? null;

        if (!$mahasiswaId) {
            return null;
        }

        return PengajuanMagangModel::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'diterima')
            ->first();
    }

    /**
     * Tampilkan halaman list log aktivitas
     */
  public function index()
{
    $pengajuan = $this->getPengajuanDiterima();
    
    // TAMBAHKAN DEBUG
    \Log::info('Debug Log Harian Index:', [
        'user_id' => Auth::id(),
        'mahasiswa_id' => Auth::user()->mahasiswa_id ?? null,
        'pengajuan' => $pengajuan ? $pengajuan->toArray() : null,
        'hasPengajuanDiterima' => $pengajuan !== null
    ]);
    
    return view('roles.mahasiswa.log-harian.index', [
        'activeMenu' => 'Log Harian',
        'hasPengajuanDiterima' => $pengajuan !== null,
    ]);
}

// TAMBAHKAN METHOD DEBUG
public function debug()
{
    $mahasiswaId = Auth::user()->mahasiswa_id ?? null;
    
    $pengajuan = PengajuanMagangModel::where('mahasiswa_id', $mahasiswaId)
        ->where('status', 'diterima')
        ->with(['mahasiswa', 'lowongan'])
        ->first();
    
    $logCount = 0;
    if ($pengajuan) {
        $logCount = LogAktivitasModel::where('pengajuan_id', $pengajuan->pengajuan_id)->count();
    }
    
    return response()->json([
        'user' => Auth::user(),
        'mahasiswa_id' => $mahasiswaId,
        'pengajuan' => $pengajuan,
        'log_count' => $logCount,
        'all_pengajuan' => PengajuanMagangModel::where('mahasiswa_id', $mahasiswaId)->get()
    ]);
}

    /**
     * Tampilkan form tambah log aktivitas
     */
    public function create()
    {
        $pengajuan = $this->getPengajuanDiterima();

        if (!$pengajuan) {
            return redirect()->route('mahasiswa.log-harian.index')
                ->with('error', 'Anda belum memiliki pengajuan magang yang diterima.');
        }

        return view('roles.mahasiswa.log-harian.create', [
            'pengajuan' => $pengajuan,
            'activeMenu' => 'logHarian'
        ]);
    }

    /**
     * Simpan log aktivitas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'aktivitas' => 'required|string|max:1000'
        ], [
            'aktivitas.required' => 'Aktivitas harus diisi',
            'aktivitas.string' => 'Aktivitas harus berupa teks',
            'aktivitas.max' => 'Aktivitas maksimal 1000 karakter'
        ]);

        $pengajuan = $this->getPengajuanDiterima();

        if (!$pengajuan) {
            return $this->responseError(
                $request,
                'Pengajuan magang tidak ditemukan atau belum diterima',
                400
            );
        }

        try {
            $log = LogAktivitasModel::create([
                'pengajuan_id' => $pengajuan->pengajuan_id,
                'aktivitas' => $request->aktivitas,
                'created_at' => now(), // TAMBAHKAN BARIS INI

            ]);

            return $this->responseSuccess(
                $request,
                'Log aktivitas berhasil ditambahkan',
                [
                    'id' => $log->id,
                    'aktivitas' => $log->aktivitas,
                    'created_at' => $log->created_at->format('d/m/Y H:i')
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Error creating log aktivitas: ' . $e->getMessage());

            return $this->responseError(
                $request,
                'Gagal menambahkan log aktivitas: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Tampilkan form edit log aktivitas
     */
    public function edit($id)
    {
        $mahasiswaId = Auth::user()->mahasiswa_id ?? 0;

        $log = LogAktivitasModel::where('id', $id)
            ->whereHas('pengajuan', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)
                  ->where('status', 'diterima');
            })->first();

        if (!$log) {
            abort(404, 'Log aktivitas tidak ditemukan atau Anda tidak memiliki akses');
        }

        return view('roles.mahasiswa.log-harian.edit', [
            'log' => $log,
            'activeMenu' => 'logHarian'
        ]);
    }

    /**
     * Update log aktivitas
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'aktivitas' => 'required|string|max:1000'
        ], [
            'aktivitas.required' => 'Aktivitas harus diisi',
            'aktivitas.string' => 'Aktivitas harus berupa teks',
            'aktivitas.max' => 'Aktivitas maksimal 1000 karakter'
        ]);

        $mahasiswaId = Auth::user()->mahasiswa_id ?? 0;

        try {
            $log = LogAktivitasModel::where('id', $id)
                ->whereHas('pengajuan', function ($q) use ($mahasiswaId) {
                    $q->where('mahasiswa_id', $mahasiswaId)
                      ->where('status', 'diterima');
                })->firstOrFail();

            $log->update(['aktivitas' => $request->aktivitas]);

            return $this->responseSuccess(
                $request,
                'Log aktivitas berhasil diperbarui',
                [
                    'id' => $log->id,
                    'aktivitas' => $log->aktivitas,
                    'updated_at' => $log->updated_at->format('d/m/Y H:i')
                ]
            );
        } catch (ModelNotFoundException $e) {
            return $this->responseError(
                $request,
                'Log aktivitas tidak ditemukan atau Anda tidak memiliki akses',
                404
            );
        } catch (\Exception $e) {
            \Log::error('Error updating log aktivitas: ' . $e->getMessage());

            return $this->responseError(
                $request,
                'Gagal memperbarui log aktivitas: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Hapus log aktivitas
     */
    public function destroy($id)
{
    $mahasiswaId = Auth::user()->mahasiswa_id ?? 0;

    try {
        $log = LogAktivitasModel::where('id', $id)
            ->whereHas('pengajuan', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)
                  ->where('status', 'diterima');
            })->firstOrFail();

        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Log aktivitas berhasil dihapus'
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Log aktivitas tidak ditemukan atau Anda tidak memiliki akses'
        ], 404);
    } catch (\Exception $e) {
        \Log::error('Error deleting log aktivitas: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus log aktivitas: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * List data log aktivitas untuk datatables AJAX
     */
  public function list(Request $request)
{
    $pengajuan = $this->getPengajuanDiterima();

    if (!$pengajuan) {
        return response()->json(['data' => []]);
    }

    $logs = LogAktivitasModel::where('pengajuan_id', $pengajuan->pengajuan_id)->get();

    return response()->json([
        'pengajuan' => $pengajuan,
        'logs' => $logs,
    ]);
}


    /**
     * API untuk cek status pengajuan magang diterima
     */
    public function checkStatus()
    {
        $pengajuan = $this->getPengajuanDiterima();

        return response()->json([
            'hasPengajuanDiterima' => $pengajuan !== null,
            'pengajuan' => $pengajuan ? [
                'id' => $pengajuan->pengajuan_id,
                'status' => $pengajuan->status,
                'lowongan' => $pengajuan->lowongan->judul ?? 'N/A'
            ] : null
        ]);
    }


    /**
     * Helper untuk response sukses, menyesuaikan ajax / redirect
     */
    private function responseSuccess(Request $request, string $message, array $data = [])
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ]);
        }

        return redirect()->route('mahasiswa.log-harian.index')
            ->with('success', $message);
    }

    /**
     * Helper untuk response error, menyesuaikan ajax / redirect
     */
    private function responseError(Request $request, string $message, int $statusCode = 400)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $statusCode);
        }

        return redirect()->back()->with('error', $message)->withInput();
    }
}
