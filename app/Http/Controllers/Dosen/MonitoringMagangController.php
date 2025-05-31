<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\LogAktivitasModel;
use App\Models\FeedbackLogAktivitasModel;
use Illuminate\Http\Request;

class MonitoringMagangController extends Controller
{
    // Tampilkan daftar mahasiswa bimbingan dosen
    public function index()
    {
        $dosenId = auth()->user()->dosen->dosen_id; // asumsi user sudah login dosen

        $pengajuan = PengajuanMagangModel::with('mahasiswa.user')
            ->where('dosen_id', $dosenId)
            ->get();

        return view('roles.dosen.monitoring-magang.index', compact('pengajuan'));
    }

    // Detail mahasiswa dan log aktivitas beserta feedback
    public function show($pengajuanId)
    {
        $pengajuan = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan',
            'periode',
            'logAktivitas.feedbacks.dosen'
        ])->findOrFail($pengajuanId);

        $logs = LogAktivitasModel::with('feedbacks.dosen')
            ->where('pengajuan_id', $pengajuanId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('roles.dosen.monitoring-magang.show', compact('pengajuan', 'logs'));
    }

    // Simpan feedback dosen ke log aktivitas
    public function storeFeedback(Request $request, $logId)
    {
        $request->validate([
            'komentar' => 'required|string',
            'nilai' => 'nullable|integer|min:0|max:100',
        ]);

        FeedbackLogAktivitasModel::create([
            'log_aktivitas_id' => $logId,
            'dosen_id' => auth()->user()->dosen->dosen_id,
            'komentar' => $request->komentar,
            'nilai' => $request->nilai,
        ]);

        return back()->with('success', 'Feedback berhasil ditambahkan.');
    }
}
