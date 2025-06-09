<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\LogAktivitasModel;
use App\Models\FeedbackLogAktivitasModel;
use App\Models\PeriodeMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FeedbackGiven;

class MonitoringMagangController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = Auth::user()->dosen->dosen_id;
        $query = PengajuanMagangModel::with(['mahasiswa.user', 'periode', 'lowongan'])
            ->where('dosen_id', $dosenId)
            ->whereNotNull('dosen_id')
            ->whereIn('status', ['diterima', 'ongoing']);

        // Filter status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Filter periode
        if ($periodeId = $request->query('periode_id')) {
            $query->where('periode_id', $periodeId);
        }

        // Sorting berdasarkan nama mahasiswa dari relasi
        $sort = $request->query('sort', 'nama');
        $order = $request->query('order', 'asc');
        $query->join('m_mahasiswa', 't_pengajuan_magang.mahasiswa_id', '=', 'm_mahasiswa.mahasiswa_id')
              ->orderBy('m_mahasiswa.nim', $order);

        $pengajuan = $query->paginate(10);
        $periodeMagang = PeriodeMagangModel::all();
        $dosen = Auth::user()->dosen;

        // Pengecekan data untuk mencegah error
        foreach ($pengajuan as $p) {
            if (!$p->mahasiswa || !$p->mahasiswa->user) {
                $p->mahasiswa->user = new \stdClass();
                $p->mahasiswa->user->name = 'Nama Tidak Tersedia';
            }
            if (!$p->periode || !isset($p->periode->nama_periode)) {
                $p->periode = new \stdClass();
                $p->periode->nama_periode = 'Tidak Tersedia';
            }
        }

        return view('roles.dosen.monitoring-magang.index', compact('pengajuan', 'periodeMagang', 'dosen'));
    }

    public function show($pengajuanId)
    {
        $dosenId = Auth::user()->dosen->dosen_id;
        $pengajuan = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan',
            'periode'
        ])->where('dosen_id', $dosenId)
            ->whereIn('status', ['diterima', 'ongoing'])
            ->findOrFail($pengajuanId);

        $logs = LogAktivitasModel::where('pengajuan_id', $pengajuanId)
            ->orderBy('log_id', 'desc') // Gunakan log_id sebagai primary key
            ->get();

        foreach ($logs as $log) {
            $log->feedback = FeedbackLogAktivitasModel::where('log_id', $log->log_id) // Gunakan log_id
                ->with('dosen.user')
                ->first();
        }

        return view('roles.dosen.monitoring-magang.show', compact('pengajuan', 'logs'));
    }

    public function storeFeedback(Request $request, $logId)
    {
        $dosenId = Auth::user()->dosen->dosen_id;
        $log = LogAktivitasModel::where('log_id', $logId) // Gunakan log_id
            ->whereHas('pengajuan', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId)
                    ->whereIn('status', ['diterima', 'ongoing']);
            })->firstOrFail();

        if (FeedbackLogAktivitasModel::where('log_id', $log->log_id)->exists()) {
            return back()->with('error', 'Feedback sudah diberikan untuk log ini.');
        }

        $request->validate([
            'komentar' => 'required|string|max:1000',
            'nilai' => 'nullable|integer|min:0|max:100',
        ]);

        $feedback = FeedbackLogAktivitasModel::create([
            'log_id' => $log->log_id, // Gunakan log_id
            'dosen_id' => $dosenId,
            'komentar' => $request->komentar,
            'nilai' => $request->nilai,
        ]);

        $mahasiswa = $log->pengajuan->mahasiswa->user;
        Notification::send($mahasiswa, new FeedbackGiven($feedback));

        return back()->with('success', 'Feedback berhasil ditambahkan.');
    }
}