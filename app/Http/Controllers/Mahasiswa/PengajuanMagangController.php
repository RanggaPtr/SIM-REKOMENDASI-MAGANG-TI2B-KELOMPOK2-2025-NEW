<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\LowonganMagangModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PengajuanMagangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengajuan Magang',
            'list' => ['Home', 'Pengajuan Magang']
        ];

        $page = (object) [
            'title' => 'Daftar pengajuan magang yang telah dibuat'
        ];

        $activeMenu = 'pengajuan_magang';

        // Get available options for dropdowns
        $lowongan = LowonganMagangModel::all();
        $dosen = DosenModel::all();
        $periode = PeriodeMagangModel::all();

        return view('roles.mahasiswa.pengajuan-magang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'lowongan' => $lowongan,
            'dosen' => $dosen,
            'periode' => $periode
        ]);
    }

    public function list(Request $request)
    {
        // Only show applications for the logged in student
        $user_id = auth()->user()->user_id;
        $mahasiswa_id = MahasiswaModel::find($user_id);
        $pengajuan = PengajuanMagangModel::with(['lowongan', 'dosen', 'periode'])
            ->where('mahasiswa_id', $mahasiswa_id)
            ->select('pengajuan_id', 'mahasiswa_id', 'lowongan_id', 'dosen_id', 'periode_id', 'status', 'created_at');

        // Filter by status if provided
        if ($request->status) {
            $pengajuan->where('status', $request->status);
        }

        return DataTables::of($pengajuan)
            ->addIndexColumn()
            ->addColumn('lowongan', function ($pengajuan) {
                return $pengajuan->lowongan->judul;
            })
            ->addColumn('dosen', function ($pengajuan) {
                return $pengajuan->dosen->user->nama;
            })
            ->addColumn('periode', function ($pengajuan) {
                return $pengajuan->periode->nama;
            })
            ->addColumn('status', function ($pengajuan) {
                $badgeClass = [
                    'pending' => 'bg-warning',
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger'
                ][$pengajuan->status] ?? 'bg-secondary';
                
                return '<span class="badge '.$badgeClass.'">'.ucfirst($pengajuan->status).'</span>';
            })
            ->addColumn('aksi', function ($pengajuan) {
                $btn = '<button onclick="modalAction(\''.url('/mahasiswa/pengajuan-magang/'.$pengajuan->pengajuan_id.'/show_ajax').'\')" class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i> Detail
                </button> ';
                
                // Only show edit button for pending applications
                if ($pengajuan->status == 'pending') {
                    $btn .= '<button onclick="modalAction(\''.url('/mahasiswa/pengajuan-magang/'.$pengajuan->pengajuan_id.'/edit_ajax').'\')" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/mahasiswa/pengajuan-magang/'.$pengajuan->pengajuan_id.'/delete_ajax').'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i> Hapus
                    </button>';
                }
                
                return $btn;
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        // Get available options for dropdowns
        $lowongan = LowonganMagangModel::where('tanggal_tutup', '>=', now())->get();
        $dosen = DosenModel::all();
        $periode = PeriodeMagangModel::where('tanggal_selesai', '>=', now())->get();

        return view('roles.mahasiswa.pengajuan-magang.create_ajax', [
            'lowongan' => $lowongan,
            'dosen' => $dosen,
            'periode' => $periode
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
                'msgField' => $validator->errors()
            ]);
        }

        try {
            // Get the logged in student
            $mahasiswa_id = auth()->user()->mahasiswa->mahasiswa_id;

            // Check if student already applied to this internship
            $existing = PengajuanMagangModel::where('mahasiswa_id', $mahasiswa_id)
                ->where('lowongan_id', $request->lowongan_id)
                ->exists();

            if ($existing) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda sudah mengajukan magang di lowongan ini'
                ]);
            }

            $pengajuan = PengajuanMagangModel::create([
                'mahasiswa_id' => $mahasiswa_id,
                'lowongan_id' => $request->lowongan_id,
                'dosen_id' => $request->dosen_id,
                'periode_id' => $request->periode_id,
                'status' => 'pending'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pengajuan berhasil dibuat',
                'id' => $pengajuan->pengajuan_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat pengajuan: ' . $e->getMessage()
            ]);
        }
    }

    public function show_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::with(['lowongan', 'dosen.user', 'periode'])
            ->findOrFail($pengajuan_id);

        // Verify that the application belongs to the logged in student
        if ($pengajuan->mahasiswa_id != auth()->user()->mahasiswa->mahasiswa_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('roles.mahasiswa.pengajuan-magang.show_ajax', compact('pengajuan'));
    }

    public function edit_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::findOrFail($pengajuan_id);

        // Verify that the application belongs to the logged in student and is pending
        if ($pengajuan->mahasiswa_id != auth()->user()->mahasiswa->mahasiswa_id || $pengajuan->status != 'pending') {
            abort(403, 'Unauthorized action.');
        }

        // Get available options for dropdowns
        $lowongan = LowonganMagangModel::where('tanggal_tutup', '>=', now())->get();
        $dosen = DosenModel::all();
        $periode = PeriodeMagangModel::where('tanggal_selesai', '>=', now())->get();

        return view('roles.mahasiswa.pengajuan-magang.edit_ajax', [
            'pengajuan' => $pengajuan,
            'lowongan' => $lowongan,
            'dosen' => $dosen,
            'periode' => $periode
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
                'msgField' => $validator->errors()
            ]);
        }

        $pengajuan = PengajuanMagangModel::find($pengajuan_id);

        // Verify that the application belongs to the logged in student and is pending
        if (!$pengajuan || 
            $pengajuan->mahasiswa_id != auth()->user()->mahasiswa->mahasiswa_id || 
            $pengajuan->status != 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Pengajuan tidak ditemukan atau tidak dapat diubah'
            ]);
        }

        $pengajuan->update([
            'lowongan_id' => $request->lowongan_id,
            'dosen_id' => $request->dosen_id,
            'periode_id' => $request->periode_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pengajuan berhasil diperbarui',
            'id' => $pengajuan->pengajuan_id
        ]);
    }

    public function confirm_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::findOrFail($pengajuan_id);

        // Verify that the application belongs to the logged in student
        if ($pengajuan->mahasiswa_id != auth()->user()->mahasiswa->mahasiswa_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('roles.mahasiswa.pengajuan-magang.confirm_ajax', compact('pengajuan'));
    }

    public function delete_ajax($pengajuan_id)
    {
        $pengajuan = PengajuanMagangModel::find($pengajuan_id);

        // Verify that the application belongs to the logged in student and is pending
        if (!$pengajuan || 
            $pengajuan->mahasiswa_id != auth()->user()->mahasiswa->mahasiswa_id || 
            $pengajuan->status != 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Pengajuan tidak ditemukan atau tidak dapat dihapus'
            ]);
        }

        $pengajuan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Pengajuan berhasil dihapus'
        ]);
    }
}