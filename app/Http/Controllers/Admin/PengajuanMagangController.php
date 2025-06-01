<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\MahasiswaModel;
use App\Models\LowonganMagangModel;
use App\Models\DosenModel;
use App\Models\PeriodeMagangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PengajuanMagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengajuan Magang',
            'list' => ['Home', 'Pengajuan Magang']
        ];

        $page = (object) [
            'title' => 'Daftar pengajuan magang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'manajemenMagang';

        return view('roles.admin.management-pengajuan-magang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    /**
     * Get data for DataTables
     */
    public function list(Request $request)
    {
        $pengajuanMagang = PengajuanMagangModel::select(
                'pengajuan_id',
                'mahasiswa_id', 
                'lowongan_id',
                'dosen_id',
                'periode_id',
                'status',
                'feedback_rating',
                'created_at'
            )
            ->with(['mahasiswa.user', 'lowongan.perusahaan', 'dosen.user', 'periode']);

        return DataTables::of($pengajuanMagang)
            ->addIndexColumn()
            ->addColumn('mahasiswa_nama', function ($pengajuan) {
                return $pengajuan->mahasiswa->user->nama ?? '-';
            })
            ->addColumn('perusahaan_nama', function ($pengajuan) {
                return $pengajuan->lowongan->perusahaan->nama_perusahaan ?? '-';
            })
            ->addColumn('lowongan_posisi', function ($pengajuan) {
                return $pengajuan->lowongan->posisi ?? '-';
            })
            ->addColumn('dosen_nama', function ($pengajuan) {
                return $pengajuan->dosen->user->nama ?? '-';
            })
            ->addColumn('periode_nama', function ($pengajuan) {
                return $pengajuan->periode->nama ?? '-';
            })
            ->addColumn('status_badge', function ($pengajuan) {
                $badgeClass = match($pengajuan->status) {
                    'pending' => 'badge-warning',
                    'approved' => 'badge-success', 
                    'rejected' => 'badge-danger',
                    'completed' => 'badge-info',
                    default => 'badge-secondary'
                };
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($pengajuan->status) . '</span>';
            })
            ->addColumn('aksi', function ($pengajuan) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/management-pengajuan-magang/' . $pengajuan->pengajuan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-pengajuan-magang/' . $pengajuan->pengajuan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-pengajuan-magang/' . $pengajuan->pengajuan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_ajax()
    {
        $mahasiswa = MahasiswaModel::with('user')->get();
        $lowongan = LowonganMagangModel::with('perusahaan')->get();
        $dosen = DosenModel::with('user')->get();
        $periode = PeriodeMagangModel::all();

        return view('roles.admin.management-pengajuan-magang.create_ajax', [
            'mahasiswa' => $mahasiswa,
            'lowongan' => $lowongan,
            'dosen' => $dosen,
            'periode' => $periode
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required|integer|exists:m_mahasiswa,mahasiswa_id',
                'lowongan_id' => 'required|integer|exists:m_lowongan_magang,lowongan_id',
                'dosen_id' => 'required|integer|exists:m_dosen,dosen_id',
                'periode_id' => 'required|integer|exists:m_periode_magang,periode_id',
                'status' => 'required|in:pending,approved,rejected,completed'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            PengajuanMagangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data pengajuan magang berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show_ajax(string $id)
    {
        $pengajuan = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan.perusahaan',
            'dosen.user',
            'periode'
        ])->find($id);

        if ($pengajuan) {
            return view('roles.admin.management-pengajuan-magang.show_ajax', ['pengajuan' => $pengajuan]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_ajax(string $id)
    {
        $pengajuan = PengajuanMagangModel::find($id);
        $mahasiswa = MahasiswaModel::with('user')->get();
        $lowongan = LowonganMagangModel::with('perusahaan')->get();
        $dosen = DosenModel::with('user')->get();
        $periode = PeriodeMagangModel::all();

        if ($pengajuan) {
            return view('roles.admin.management-pengajuan-magang.edit_ajax', [
                'pengajuan' => $pengajuan,
                'mahasiswa' => $mahasiswa,
                'lowongan' => $lowongan,
                'dosen' => $dosen,
                'periode' => $periode
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required|integer|exists:m_mahasiswa,mahasiswa_id',
                'lowongan_id' => 'required|integer|exists:m_lowongan_magang,lowongan_id',
                'dosen_id' => 'required|integer|exists:m_dosen,dosen_id',
                'periode_id' => 'required|integer|exists:m_periode_magang,periode_id',
                'status' => 'required|in:pending,approved,rejected,completed'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = PengajuanMagangModel::find($id);

            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    /**
     * Show the form for confirming deletion.
     */
    public function confirm_ajax(string $id)
    {
        $pengajuan = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan.perusahaan'
        ])->find($id);

        if ($pengajuan) {
            return view('roles.admin.management-pengajuan-magang.confirm_ajax', ['pengajuan' => $pengajuan]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pengajuan = PengajuanMagangModel::find($id);

            if ($pengajuan) {
                $pengajuan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
}