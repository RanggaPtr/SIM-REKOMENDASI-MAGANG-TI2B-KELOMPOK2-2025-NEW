<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\DosenModel;
use App\Models\LowonganMagangModel;
use App\Models\KompetensiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PengajuanMagangController extends Controller
{
    public function index()
    {
        return view('roles.admin.pengajuan.index', ['activeMenu' => 'pengajuan']);
    }

 public function list(Request $request)
{
    $pengajuan = PengajuanMagangModel::with(['mahasiswa.user', 'lowongan.perusahaan', 'dosen.user', 'periode'])
        ->select('t_pengajuan_magang.*');

    // Debugging: Tampilkan data untuk memeriksa relasi
    $data = $pengajuan->get();
    foreach ($data as $row) {
        \Log::info('Pengajuan ID: ' . $row->pengajuan_id);
        \Log::info('Mahasiswa: ' . json_encode($row->mahasiswa));
        \Log::info('User: ' . json_encode($row->mahasiswa ? $row->mahasiswa->user : null));
    }

    return DataTables::of($pengajuan)
        ->addColumn('mahasiswa_name', fn($row) => $row->mahasiswa && $row->mahasiswa->user ? $row->mahasiswa->user->name : '-')
        ->addColumn('perusahaan_name', fn($row) => $row->lowongan && $row->lowongan->perusahaan ? $row->lowongan->perusahaan->nama : '-')
        ->addColumn('lowongan_judul', fn($row) => $row->lowongan ? $row->lowongan->judul : '-')
        ->addColumn('dosen_name', fn($row) => $row->dosen && $row->dosen->user ? $row->dosen->user->name : '-')
        ->addColumn('periode_name', fn($row) => $row->periode ? $row->periode->nama : '-')
        ->addColumn('action', fn($row) => view('roles.admin.pengajuan.action', compact('row')))
        ->make(true);
}

    public function show_ajax($id)
    {
        $pengajuan = PengajuanMagangModel::with(['mahasiswa.user', 'lowongan.perusahaan', 'dosen.user', 'periode'])->findOrFail($id);
        return view('roles.admin.pengajuan.show_ajax', compact('pengajuan'));
    }

    public function edit_ajax($id)
    {
        $pengajuan = PengajuanMagangModel::with(['mahasiswa.user', 'lowongan.perusahaan', 'periode'])->findOrFail($id);
        $dosens = DosenModel::with(['user', 'kompetensi'])->get();
        $kompetensis = KompetensiModel::all();
        return view('roles.admin.pengajuan.edit_ajax', compact('pengajuan', 'dosens', 'kompetensis'));
    }

    public function update_ajax(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,diterima,ditolak,selesai',
            'dosen_id' => 'nullable|exists:m_dosen,dosen_id',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanMagangModel::findOrFail($id);
            $pengajuan->status = $request->status;
            if ($request->status === 'diterima' && $request->dosen_id) {
                $pengajuan->dosen_id = $request->dosen_id;
                $dosen = DosenModel::find($request->dosen_id);
                if ($dosen) {
                    $dosen->jumlah_bimbingan += 1;
                    $dosen->save();
                }
            } elseif ($request->status === 'selesai' && $pengajuan->dosen_id) {
                $dosen = DosenModel::find($pengajuan->dosen_id);
                if ($dosen && $dosen->jumlah_bimbingan > 0) {
                    $dosen->jumlah_bimbingan -= 1;
                    $dosen->save();
                }
            }
            $pengajuan->save();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function confirm_ajax($id)
    {
        $pengajuan = PengajuanMagangModel::findOrFail($id);
        return view('roles.admin.pengajuan.confirm_ajax', compact('pengajuan'));
    }

    public function delete_ajax($id)
    {
        DB::beginTransaction();
        try {
            $pengajuan = PengajuanMagangModel::findOrFail($id);
            if ($pengajuan->dosen_id) {
                $dosen = DosenModel::find($pengajuan->dosen_id);
                if ($dosen && $dosen->jumlah_bimbingan > 0) {
                    $dosen->jumlah_bimbingan -= 1;
                    $dosen->save();
                }
            }
            $pengajuan->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}