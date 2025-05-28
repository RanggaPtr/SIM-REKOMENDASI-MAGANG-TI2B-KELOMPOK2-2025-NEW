<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitasModel;
use App\Models\PengajuanMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LogAktivitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:mahasiswa');
    }

    public function index()
    {
        return view('roles.mahasiswa.log-harian.index', [
            'activeMenu' => 'logHarian',
        ]);
    }

    public function create()
    {
        // Ambil pengajuan milik user yang login
        $pengajuan = PengajuanMagangModel::where('user_id', Auth::id())
            ->where('status', 'diterima') // hanya yang diterima
            ->first();

        if (!$pengajuan) {
            return redirect()->route('mahasiswa.log-harian.index')
                ->with('error', 'Anda tidak memiliki pengajuan magang yang diterima');
        }

        return view('roles.mahasiswa.log-harian.create', [
            'pengajuan' => $pengajuan
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'aktivitas' => 'required|string|max:1000'
    ]);

    $pengajuan = PengajuanMagangModel::where('user_id', Auth::id())
        ->where('status', 'diterima')
        ->firstOrFail();

    LogAktivitasModel::create([
        'pengajuan_id' => $pengajuan->id,
        'aktivitas' => $request->aktivitas,
    ]);

    return response()->json(['success' => 'Log aktivitas berhasil ditambahkan.']);
}
   public function edit(LogAktivitasModel $log) {
    // Pastikan log milik user yang login
    if ($log->pengajuan->user_id != Auth::id()) {
        abort(403, 'Unauthorized');
    }
    return view('roles.mahasiswa.log-harian.edit', compact('log'));
}
    public function update(Request $request, $id)
    {
        $request->validate([
            'aktivitas' => 'required|string|max:1000'
        ]);

        try {
            $log = LogAktivitasModel::with('pengajuan')
                ->whereHas('pengajuan', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->findOrFail($id);

            $log->update([
                'aktivitas' => $request->aktivitas
            ]);

            return redirect()->route('mahasiswa.log-harian.index')
                ->with('success', 'Log aktivitas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui log: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $log = LogAktivitasModel::with('pengajuan')
                ->whereHas('pengajuan', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->findOrFail($id);

            $log->delete();

            return redirect()->route('mahasiswa.log-harian.index')
                ->with('success', 'Log aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus log: '.$e->getMessage());
        }
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = LogAktivitasModel::with('pengajuan')
                ->whereHas('pengajuan', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->addColumn('aksi', function ($row) {
                    $editUrl = route('mahasiswa.log-harian.edit', $row->id);
                    $deleteUrl = route('mahasiswa.log-harian.destroy', $row->id);
                    
                    return '
                        <div class="btn-group">
                            <a href="'.$editUrl.'" class="btn btn-sm btn-primary">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-url="'.$deleteUrl.'">Hapus</button>
                        </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return abort(404);
    }
}