<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitasModel;
use App\Models\PengajuanMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LogAktivitasController extends Controller
{
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

    public function index()
    {
        $pengajuan = $this->getPengajuanDiterima();

        return view('roles.mahasiswa.log-harian.index', [
            'activeMenu' => 'logHarian',
            'hasPengajuanDiterima' => $pengajuan !== null,
        ]);
    }

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
                ->select(['log_id', 'aktivitas', 'created_at']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '
                        <button class="btn btn-sm btn-warning btn-edit" data-id="'.$row->log_id.'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->log_id.'" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Log Activity List Error: '.$e->getMessage());
            return response()->json([
                'draw' => (int)$request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Server error'
            ], 500);
        }
    }

    public function create_ajax()
    {
        $pengajuan = $this->getPengajuanDiterima();

        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki pengajuan magang yang diterima.'
            ], 400);
        }

        $formHtml = view('roles.mahasiswa.log-harian.partials.form', [
            'action' => route('mahasiswa.log-harian.store'),
            'method' => 'POST',
            'aktivitas' => ''
        ])->render();

        return response()->json([
            'success' => true,
            'data' => [
                'form_html' => $formHtml
            ]
        ]);
    }

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
                ], 400);
            }

            $log = LogAktivitasModel::create([
                'pengajuan_id' => $pengajuan->pengajuan_id,
                'aktivitas' => $request->aktivitas
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil ditambahkan',
                'data' => [
                    'log_id' => $log->log_id,
                    'aktivitas' => $log->aktivitas,
                    'created_at' => $log->created_at->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating log: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan log aktivitas: '.$e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $mahasiswa = Auth::user()->mahasiswa;
            $mahasiswaId = $mahasiswa ? $mahasiswa->mahasiswa_id : null;

            $log = LogAktivitasModel::where('log_id', $id)
                ->whereHas('pengajuan', function($query) use ($mahasiswaId) {
                    $query->where('mahasiswa_id', $mahasiswaId)
                          ->where('status', 'diterima');
                })
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'log_id' => $log->log_id,
                    'aktivitas' => $log->aktivitas,
                    'created_at' => $log->created_at->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error editing log: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Log aktivitas tidak ditemukan'
            ], 404);
        }
    }

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

            $log = LogAktivitasModel::where('log_id', $id)
                ->whereHas('pengajuan', function($query) use ($mahasiswaId) {
                    $query->where('mahasiswa_id', $mahasiswaId)
                          ->where('status', 'diterima');
                })
                ->firstOrFail();

            $log->update([
                'aktivitas' => $request->aktivitas
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log aktivitas berhasil diperbarui',
                'data' => [
                    'log_id' => $log->log_id,
                    'aktivitas' => $log->aktivitas,
                    'created_at' => $log->created_at->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating log: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui log aktivitas'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $mahasiswa = Auth::user()->mahasiswa;
            $mahasiswaId = $mahasiswa ? $mahasiswa->mahasiswa_id : null;

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
            Log::error('Error deleting log: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas'
            ], 500);
        }
    }
}