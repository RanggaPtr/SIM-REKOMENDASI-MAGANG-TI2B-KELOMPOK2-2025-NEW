<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PengajuanMagangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pengajuan Magang',
            'list' => ['Home', 'Pengajuan Magang']
        ];

        $page = (object) [
            'title' => 'Daftar Pengajuan Magang'
        ];

        $activeMenu = 'pengajuan-magang';

        $mahasiswa = MahasiswaModel::where('user_id', Auth::id())->first();


        return view('roles.mahasiswa.pengajuan-magang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function list(Request $request)
    {
        // Ambil data mahasiswa yang login
        $mahasiswa = MahasiswaModel::where('user_id', Auth::id())->first();

        $pengajuan = PengajuanMagangModel::with(['lowongan', 'dosen', 'periode','lowongan.perusahaan','dosen.user'])
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id);

        return DataTables::of($pengajuan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengajuan) {
                $btn = '
                    <button onclick="modalAction(\''.url('/mahasiswa/pengajuan-magang/'.$pengajuan->pengajuan_id.'/show_ajax').'\')" class="btn btn-info btn-sm">
                        <i class="fa fa-eye"></i> Detail
                    </button>
                ';

                // Hanya bisa edit jika status belum disetujui
                if ($pengajuan->status == 'diajukan') {
                    $btn .= '
                        <button onclick="modalAction(\''.url('/mahasiswa/pengajuan-magang/'.$pengajuan->pengajuan_id.'/edit_ajax').'\')" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button onclick="modalAction(\''.url('/mahasiswa/pengajuan-magang/'.$pengajuan->pengajuan_id.'/confirm_ajax').'\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    ';
                }

                return $btn;
            })
            ->addColumn('status', function ($pengajuan) {
                $badge = '';
                switch ($pengajuan->status) {
                    case 'disetujui':
                        $badge = '<span class="badge bg-success">Disetujui</span>';
                        break;
                    case 'ditolak':
                        $badge = '<span class="badge bg-danger">Ditolak</span>';
                        break;
                    default:
                        $badge = '<span class="badge bg-warning">Diajukan</span>';
                }
                return $badge;
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function create_ajax()
    {
        // Ambil data untuk dropdown
        $lowongan = LowonganMagangModel::where('tanggal_tutup', '>=', now())->get();
        $periode = PeriodeMagangModel::where('tanggal_selesai', '>=', now())->get();
        $dosen = DosenModel::all();

        return view('roles.mahasiswa.pengajuan-magang.create_ajax', [
            'lowongan' => $lowongan,
            'periode' => $periode,
            'dosen' => $dosen
        ]);
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lowongan_id' => 'required|exists:m_lowongan_magang,lowongan_id',
            'dosen_id' => 'required|exists:m_dosen,dosen_id',
            'periode_id' => 'required|exists:m_periode_magang,periode_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data mahasiswa yang login
        $mahasiswa = MahasiswaModel::where('user_id', Auth::id())->first();

        try {
            $pengajuan = PengajuanMagangModel::create([
                'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                'lowongan_id' => $request->lowongan_id,
                'dosen_id' => $request->dosen_id,
                'periode_id' => $request->periode_id,
                'status' => 'diajukan'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pengajuan magang berhasil dibuat',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::with(['lowongan', 'dosen', 'periode', 'mahasiswa'])
            ->find($pengajuan_id);

        if (!$pengajuan) {
            return view('roles.mahasiswa.pengajuan-magang.error_ajax', [
                'message' => 'Data pengajuan tidak ditemukan'
            ]);
        }

        return view('roles.mahasiswa.pengajuan-magang.show_ajax', compact('pengajuan'));
    }

    public function edit_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::find($pengajuan_id);

        if (!$pengajuan || $pengajuan->status != 'diajukan') {
            return view('roles.mahasiswa.pengajuan-magang.error_ajax', [
                'message' => 'Pengajuan tidak dapat diubah'
            ]);
        }

        // Ambil data untuk dropdown
        $lowongan = LowonganMagangModel::where('tanggal_tutup', '>=', now())->get();
        $periode = PeriodeMagangModel::where('tanggal_selesai', '>=', now())->get();
        $dosen = DosenModel::all();

        return view('roles.mahasiswa.pengajuan-magang.edit_ajax', [
            'pengajuan' => $pengajuan,
            'lowongan' => $lowongan,
            'periode' => $periode,
            'dosen' => $dosen
        ]);
    }

    public function update_ajax(Request $request, $pengajuan_id)
    {
        $validator = Validator::make($request->all(), [
            'lowongan_id' => 'required|exists:m_lowongan_magang,lowongan_id',
            'dosen_id' => 'required|exists:m_dosen,dosen_id',
            'periode_id' => 'required|exists:m_periode_magang,periode_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengajuan = PengajuanMagangModel::find($pengajuan_id);

        if (!$pengajuan || $pengajuan->status != 'diajukan') {
            return response()->json([
                'status' => false,
                'message' => 'Pengajuan tidak dapat diubah'
            ], 403);
        }

        try {
            $pengajuan->update([
                'lowongan_id' => $request->lowongan_id,
                'dosen_id' => $request->dosen_id,
                'periode_id' => $request->periode_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pengajuan magang berhasil diperbarui',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirm_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::find($pengajuan_id);

        if (!$pengajuan || $pengajuan->status != 'diajukan') {
            return view('roles.mahasiswa.pengajuan-magang.error_ajax', [
                'message' => 'Pengajuan tidak dapat dihapus'
            ]);
        }

        return view('roles.mahasiswa.pengajuan-magang.confirm_ajax', compact('pengajuan'));
    }

    public function destroy_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::find($pengajuan_id);

        if (!$pengajuan || $pengajuan->status != 'diajukan') {
            return response()->json([
                'status' => false,
                'message' => 'Pengajuan tidak dapat dihapus'
            ], 403);
        }

        try {
            $pengajuan->delete();
            return response()->json([
                'status' => true,
                'message' => 'Pengajuan magang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }
}