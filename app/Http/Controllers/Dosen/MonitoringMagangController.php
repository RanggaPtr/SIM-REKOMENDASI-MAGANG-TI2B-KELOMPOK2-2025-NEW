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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonitoringMagangController extends Controller
{
    public function index(Request $request)
    {
        try {
            $activeMenu = 'Monitoring Magang';

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
                  ->orderBy('m_mahasiswa.nim', $order)
                  ->select('t_pengajuan_magang.*');

            $pengajuan = $query->paginate(10);
            $periodeMagang = PeriodeMagangModel::all();
            $dosen = Auth::user()->dosen;

            // Pengecekan data untuk mencegah error
            foreach ($pengajuan as $p) {
                if (!$p->mahasiswa || !$p->mahasiswa->user) {
                    $p->mahasiswa = (object)[
                        'user' => (object)['name' => 'Nama Tidak Tersedia']
                    ];
                }
                if (!$p->periode || !isset($p->periode->nama_periode)) {
                    $p->periode = (object)['nama_periode' => 'Tidak Tersedia'];
                }
            }

            return view('roles.dosen.monitoring-magang.index', compact('pengajuan', 'periodeMagang', 'dosen', 'activeMenu'));
            
        } catch (\Exception $e) {
            Log::error('Error in MonitoringMagangController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data monitoring magang.');
        }
    }

    public function show($pengajuanId)
    {
        try {
            $dosenId = Auth::user()->dosen->dosen_id;
            
            // Ambil data pengajuan
            $pengajuan = PengajuanMagangModel::with([
                'mahasiswa.user',
                'lowongan',
                'periode'
            ])->where('dosen_id', $dosenId)
                ->whereIn('status', ['diterima', 'ongoing'])
                ->findOrFail($pengajuanId);

            // Ambil log aktivitas
            $logs = LogAktivitasModel::where('pengajuan_id', $pengajuanId)
                ->orderBy('log_id', 'desc')
                ->get();

            // Ambil feedback untuk setiap log
            foreach ($logs as $log) {
                try {
                    $log->feedback = FeedbackLogAktivitasModel::where('log_aktivitas_id', $log->log_id)
                        ->with('dosen.user')
                        ->first();
                } catch (\Exception $e) {
                    Log::warning('Error loading feedback for log: ' . $e->getMessage());
                    $log->feedback = null;
                }
            }

            return view('roles.dosen.monitoring-magang.show', compact('pengajuan', 'logs'));
            
        } catch (\Exception $e) {
            Log::error('Error in MonitoringMagangController@show: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat detail monitoring magang.');
        }
    }

    public function storeFeedback(Request $request, $logId)
    {
        try {
            $dosenId = Auth::user()->dosen->dosen_id;
            
            // Cari log aktivitas
            $log = LogAktivitasModel::where('log_id', $logId)
                ->whereHas('pengajuan', function ($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId)
                        ->whereIn('status', ['diterima', 'ongoing']);
                })->first();

            if (!$log) {
                return back()->with('error', 'Log aktivitas tidak ditemukan.');
            }

            // Cek apakah feedback sudah ada
            $existingFeedback = FeedbackLogAktivitasModel::where('log_aktivitas_id', $log->log_id)->exists();

            if ($existingFeedback) {
                return back()->with('error', 'Feedback sudah diberikan untuk log ini.');
            }

            // Validasi input
            $request->validate([
                'komentar' => 'required|string|max:1000',
                'nilai' => 'nullable|integer|min:0|max:100',
            ]);

            // Buat feedback baru
            $feedback = FeedbackLogAktivitasModel::create([
                'log_aktivitas_id' => $log->log_id,
                'dosen_id' => $dosenId,
                'komentar' => $request->komentar,
                'nilai' => $request->nilai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kirim notifikasi ke mahasiswa
            try {
                $mahasiswa = $log->pengajuan->mahasiswa->user ?? null;
                if ($mahasiswa) {
                    Notification::send($mahasiswa, new FeedbackGiven($feedback));
                }
            } catch (\Exception $e) {
                Log::warning('Failed to send notification: ' . $e->getMessage());
            }

            return back()->with('success', 'Feedback berhasil ditambahkan.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error in MonitoringMagangController@storeFeedback: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan feedback.');
        }
    }

    /**
     * Helper method untuk cek struktur tabel
     */
    private function getTableStructure($tableName)
    {
        try {
            return DB::select("DESCRIBE {$tableName}");
        } catch (\Exception $e) {
            Log::error("Error checking table structure for {$tableName}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Method untuk debug - bisa dihapus di production
     */
    public function debugDatabase()
    {
        if (app()->environment('local')) {
            $logTable = $this->getTableStructure('t_log_aktivitas');
            $feedbackTable = $this->getTableStructure('feedback_log_aktivitas');
            
            return response()->json([
                'log_aktivitas_structure' => $logTable,
                'feedback_structure' => $feedbackTable
            ]);
        }
        
        abort(404);
    }
}