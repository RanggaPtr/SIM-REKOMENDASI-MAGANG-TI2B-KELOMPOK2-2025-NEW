<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\LowonganMagangModel;
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
        $pengajuan = PengajuanMagangModel::with(['lowongan', 'lowongan.perusahaan'])
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id);

        return view('roles.mahasiswa.pengajuan-magang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan

        ]);
    }

    public function list(Request $request)
    {
        // Ambil data mahasiswa yang login
        $mahasiswa = MahasiswaModel::where('user_id', Auth::id())->first();

        $pengajuan = PengajuanMagangModel::with(['lowongan', 'lowongan.perusahaan'])
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id);

        return DataTables::of($pengajuan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengajuan) {
                $btn = '
                    <button onclick="modalAction(\'' . url('/mahasiswa/pengajuan-magang/' . $pengajuan->pengajuan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">
                        <i class="fa fa-eye"></i> Detail
                    </button>
                ';

                // Hanya bisa edit jika status belum diterima
                if ($pengajuan->status == 'diajukan') {
                    $btn .= '
                        <button onclick="modalAction(\'' . url('/mahasiswa/pengajuan-magang/' . $pengajuan->pengajuan_id . '/confirm_ajax') . '\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    ';
                }

                return $btn;
            })
            ->addColumn('status', function ($pengajuan) {
                $badge = '';
                switch ($pengajuan->status) {
                    case 'diterima':
                        $badge = '<span class="badge bg-success">Diterima</span>';
                        break;
                    case 'ditolak':
                        $badge = '<span class="badge bg-danger">Ditolak</span>';
                        break;
                    case 'selesai':
                        $badge = '<span class="badge bg-secondary">Selesai</span>';
                        break;
                    default:
                        $badge = '<span class="badge bg-warning">Diajukan</span>';
                }
                return $badge;
            })

            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lowongan_id' => 'required|exists:m_lowongan_magang,lowongan_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $mahasiswa = MahasiswaModel::where('user_id', Auth::id())->first();

            // Cek apakah ada pengajuan untuk lowongan yang sama
            $existing = PengajuanMagangModel::where([
                'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                'lowongan_id' => $request->lowongan_id
            ])->exists();

            if ($existing) {
                return redirect()->route('mahasiswa.dashboard')
                    ->with('error', 'Anda sudah mengajukan magang ini sebelumnya.');
            }

            // Cek apakah sudah ada magang yang diterima atau selesai
            $sudahMagang = PengajuanMagangModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->whereIn('status', ['diterima', 'selesai'])
                ->exists();

            if ($sudahMagang) {
                return redirect()->route('mahasiswa.dashboard')
                    ->with('error', 'Anda tidak dapat mengajukan magang karena sudah memiliki magang yang diterima atau selesai.');
            }

            // Jika lolos semua validasi, simpan pengajuan
            $pengajuan = PengajuanMagangModel::create([
                'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                'lowongan_id' => $request->lowongan_id,
                'status' => 'diajukan'
            ]);

            return redirect()->route('mahasiswa.pengajuan-magang.index')
                ->with('success', 'Pengajuan magang berhasil diajukan.');
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::with(['lowongan', 'mahasiswa'])
            ->find($pengajuan_id);

        if (!$pengajuan) {
            return view('roles.mahasiswa.pengajuan-magang.error_ajax', [
                'message' => 'Data pengajuan tidak ditemukan'
            ]);
        }

        return view('roles.mahasiswa.pengajuan-magang.show_ajax', compact('pengajuan'));
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
