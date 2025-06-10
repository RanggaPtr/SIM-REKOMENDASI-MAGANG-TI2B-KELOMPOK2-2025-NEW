<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitasModel;
use App\Models\PengajuanMagangModel;
use App\Models\FeedbackLogAktivitasModel; // Impor model feedback
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LogAktivitasController extends Controller
{
    /**
     * Format tanggal dengan penanganan berbagai tipe data
     */
    private function formatDate($date)
    {
        if (!$date) {
            return 'Tidak tersedia';
        }
        
        // Jika sudah berupa string, return langsung
        if (is_string($date)) {
            return $date;
        }
        
        // Jika berupa objek Carbon/DateTime, format
        try {
            return $date->format('d/m/Y H:i');
        } catch (\Exception $e) {
            return 'Tidak tersedia';
        }
    }

    /**
     * Mendapatkan data pengajuan magang yang diterima untuk mahasiswa saat ini
     */
    private function getPengajuanDiterima()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswaId = $mahasiswa ? $mahasiswa->mahasiswa_id : null;

        if (!$mahasiswaId) {
            return null;
        }

        return PengajuanMagangModel::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'diterima')
            ->first();
    }

    /**
     * Menampilkan halaman index log harian
     */
    public function index()
    {
        $pengajuan = $this->getPengajuanDiterima();

        return view('roles.mahasiswa.log-harian.index', [
            'activeMenu' => 'logHarian',
            'hasPengajuanDiterima' => $pengajuan !== null,
        ]);
    }

    /**
     * Mengambil daftar log aktivitas untuk DataTables
     */
    public function list(Request $request)
    {
        try {
            $pengajuan = $this->getPengajuanDiterima();

            if (!$pengajuan) {
                return response()->json([
                    'draw' => (int)$request->input('draw', 0),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }

            $query = LogAktivitasModel::where('pengajuan_id', $pengajuan->pengajuan_id)
                ->select(['log_id', 'aktivitas', 'created_at'])
                ->with('feedback'); // Memuat relasi feedback dari dosen

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '
                        <button class="btn btn-sm btn-warning btn-edit" data-id="'.$row->log_id.'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->log_id.'" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-info btn-feedback" data-id="'.$row->log_id.'" title="Lihat Feedback">
                            <i class="fas fa-comment"></i>
                        </button>';
                })
                ->editColumn('created_at', function($row) {
                    if (!$row->created_at) {
                        return 'Tidak tersedia';
                    }
                    
                    // Jika sudah berupa string, return langsung
                    if (is_string($row->created_at)) {
                        return $row->created_at;
                    }
                    
                    // Jika berupa objek Carbon/DateTime, format
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->addColumn('feedback', function($row) {
                    // Tampilkan feedback dari dosen jika ada
                    return $row->feedback ? $row->feedback->feedback : 'Belum ada feedback dari dosen';
                })
                ->rawColumns(['action', 'feedback'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Log Activity List Error: ' . $e->getMessage());
            return response()->json([
                'draw' => (int)$request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Gagal memuat data log aktivitas'
            ], 500);
        }
    }

    /**
     * Menghasilkan form tambah log via AJAX
     */
    public function create_ajax()
    {
        try {
            $pengajuan = $this->getPengajuanDiterima();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum memiliki pengajuan magang yang diterima.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'pengajuan_id' => $pengajuan->pengajuan_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Create AJAX Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat form tambah log'
            ], 500);
        }
    }

    /**
     * Menyimpan log aktivitas baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aktivitas' => 'required|string|max:1000'
        ], [
            'aktivitas.required' => 'Aktivitas harus diisi',
            'aktivitas.string' => 'Aktivitas harus berupa teks',
            'aktivitas.max' => 'Aktivitas maksimal 1000 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pengajuan = $this->getPengajuanDiterima();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan magang tidak ditemukan atau belum diterima'
                ], 403);
            }

            $log = LogAktivitasModel::create([
                'pengajuan_id' => $pengajuan->pengajuan_id,
                'aktivitas' => $request->input('aktivitas')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil ditambahkan',
                'data' => [
                    'log_id' => $log->log_id,
                    'aktivitas' => $log->aktivitas,
                    'created_at' => $this->formatDate($log->created_at)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating log: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan log aktivitas'
            ], 500);
        }
    }

    /**
     * Mengambil data log untuk edit via AJAX
     */
    public function edit($id)
    {
        try {
            $mahasiswa = Auth::user()->mahasiswa;
            $mahasiswaId = $mahasiswa ? $mahasiswa->mahasiswa_id : null;

            if (!$mahasiswaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 403);
            }

            $log = LogAktivitasModel::where('log_id', $id)
                ->whereHas('pengajuan', function($query) use ($mahasiswaId) {
                    $query->where('mahasiswa_id', $mahasiswaId)
                          ->where('status', 'diterima');
                })
                ->with('feedback') // Memuat feedback dari dosen
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'log_id' => $log->log_id,
                    'aktivitas' => $log->aktivitas,
                    'created_at' => $this->formatDate($log->created_at),
                    'feedback' => $log->feedback ? [
                        'feedback_id' => $log->feedback->id, // Primary key dari FeedbackLogAktivitasModel
                        'feedback' => $log->feedback->feedback,
                        'feedback_created_at' => $this->formatDate($log->feedback->created_at)
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing log: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Log aktivitas tidak ditemukan atau tidak dapat diakses'
            ], 404);
        }
    }

    /**
     * Memperbarui log aktivitas
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'aktivitas' => 'required|string|max:1000'
        ], [
            'aktivitas.required' => 'Aktivitas harus diisi',
            'aktivitas.string' => 'Aktivitas harus berupa teks',
            'aktivitas.max' => 'Aktivitas maksimal 1000 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mahasiswa = Auth::user()->mahasiswa;
            $mahasiswaId = $mahasiswa ? $mahasiswa->mahasiswa_id : null;

            if (!$mahasiswaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 403);
            }

            $log = LogAktivitasModel::where('log_id', $id)
                ->whereHas('pengajuan', function($query) use ($mahasiswaId) {
                    $query->where('mahasiswa_id', $mahasiswaId)
                          ->where('status', 'diterima');
                })
                ->firstOrFail();

            $log->update([
                'aktivitas' => $request->input('aktivitas')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil diperbarui',
                'data' => [
                    'log_id' => $log->log_id,
                    'aktivitas' => $log->aktivitas,
                    'created_at' => $this->formatDate($log->created_at)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating log: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui log aktivitas'
            ], 500);
        }
    }

    /**
     * Menghapus log aktivitas
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Auth::user()->mahasiswa;
            $mahasiswaId = $mahasiswa ? $mahasiswa->mahasiswa_id : null;

            if (!$mahasiswaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 403);
            }

            $log = LogAktivitasModel::where('log_id', $id)
                ->whereHas('pengajuan', function($query) use ($mahasiswaId) {
                    $query->where('mahasiswa_id', $mahasiswaId)
                          ->where('status', 'diterima');
                })
                ->firstOrFail();

            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting log: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas'
            ], 500);
        }
    }

    /**
     * Menampilkan feedback dari dosen untuk log aktivitas tertentu via AJAX
     */
    public function showFeedback($id)
    {
        try {
            $mahasiswa = Auth::user()->mahasiswa;
            $mahasiswaId = $mahasiswa ? $mahasiswa->mahasiswa_id : null;

            if (!$mahasiswaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 403);
            }

            $log = LogAktivitasModel::where('log_id', $id)
                ->whereHas('pengajuan', function($query) use ($mahasiswaId) {
                    $query->where('mahasiswa_id', $mahasiswaId)
                          ->where('status', 'diterima');
                })
                ->with('feedback')
                ->firstOrFail();

            if (!$log->feedback) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada feedback dari dosen untuk log aktivitas ini'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'log_id' => $log->log_id,
                    'aktivitas' => $log->aktivitas,
                    'feedback' => $log->feedback->feedback,
                    'feedback_created_at' => $this->formatDate($log->feedback->created_at)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing feedback: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat feedback dari dosen'
            ], 500);
        }
    }
}