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
use Illuminate\Support\Facades\Log;
use Exception;

class PengajuanMagangController extends Controller
{
    /**
     * Menampilkan halaman index pengajuan magang
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengajuan Magang',
            'list' => ['Home', 'Management Pengajuan Magang']
        ];

        $page = (object) [
            'title' => 'Daftar Pengajuan Magang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pengajuan-magang';

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
        try {
            // Debug: Cek apakah ada data di tabel
            $totalCount = PengajuanMagangModel::count();
            Log::info('Total pengajuan magang di database: ' . $totalCount);

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
                ->with([
                    'mahasiswa' => function($query) {
                        $query->with('user');
                    },
                    'lowongan' => function($query) {
                        $query->with('perusahaan');
                    },
                    'dosen' => function($query) {
                        $query->with('user');
                    },
                    'periode'
                ]);

            // Filter berdasarkan status jika ada
            if ($request->has('status') && !empty($request->status)) {
                $pengajuanMagang->where('status', $request->status);
                Log::info('Filtering by status: ' . $request->status);
            }

            // Debug query
            Log::info('SQL Query: ' . $pengajuanMagang->toSql());
            Log::info('Query bindings: ', $pengajuanMagang->getBindings());

            $results = $pengajuanMagang->get();
            Log::info('Results count: ' . $results->count());

            // Debug hasil query
            if ($results->isEmpty()) {
                Log::warning('No pengajuan magang found');
                
                // Cek apakah relasi berfungsi
                $sampleData = PengajuanMagangModel::with(['mahasiswa.user', 'lowongan.perusahaan', 'dosen.user', 'periode'])->first();
                if ($sampleData) {
                    Log::info('Sample data found:', [
                        'pengajuan_id' => $sampleData->pengajuan_id,
                        'mahasiswa' => $sampleData->mahasiswa ? $sampleData->mahasiswa->user->nama ?? 'No name' : 'No mahasiswa',
                        'lowongan' => $sampleData->lowongan ? $sampleData->lowongan->posisi ?? 'No posisi' : 'No lowongan',
                        'dosen' => $sampleData->dosen ? $sampleData->dosen->user->nama ?? 'No name' : 'No dosen',
                        'periode' => $sampleData->periode ? $sampleData->periode->nama ?? 'No name' : 'No periode'
                    ]);
                }
            }

            return DataTables::of($pengajuanMagang)
                ->addIndexColumn()
                ->addColumn('mahasiswa_nama', function ($pengajuan) {
                    try {
                        return $pengajuan->mahasiswa && $pengajuan->mahasiswa->user 
                            ? $pengajuan->mahasiswa->user->nama 
                            : 'Data tidak tersedia';
                    } catch (Exception $e) {
                        Log::error('Error getting mahasiswa nama: ' . $e->getMessage());
                        return 'Error loading data';
                    }
                })
                ->addColumn('perusahaan_nama', function ($pengajuan) {
                    try {
                        return $pengajuan->lowongan && $pengajuan->lowongan->perusahaan 
                            ? $pengajuan->lowongan->perusahaan->nama_perusahaan 
                            : 'Data tidak tersedia';
                    } catch (Exception $e) {
                        Log::error('Error getting perusahaan nama: ' . $e->getMessage());
                        return 'Error loading data';
                    }
                })
                ->addColumn('lowongan_posisi', function ($pengajuan) {
                    try {
                        return $pengajuan->lowongan 
                            ? $pengajuan->lowongan->posisi 
                            : 'Data tidak tersedia';
                    } catch (Exception $e) {
                        Log::error('Error getting lowongan posisi: ' . $e->getMessage());
                        return 'Error loading data';
                    }
                })
                ->addColumn('dosen_nama', function ($pengajuan) {
                    try {
                        return $pengajuan->dosen && $pengajuan->dosen->user 
                            ? $pengajuan->dosen->user->nama 
                            : 'Data tidak tersedia';
                    } catch (Exception $e) {
                        Log::error('Error getting dosen nama: ' . $e->getMessage());
                        return 'Error loading data';
                    }
                })
                ->addColumn('periode_nama', function ($pengajuan) {
                    try {
                        return $pengajuan->periode 
                            ? $pengajuan->periode->nama 
                            : 'Data tidak tersedia';
                    } catch (Exception $e) {
                        Log::error('Error getting periode nama: ' . $e->getMessage());
                        return 'Error loading data';
                    }
                })
                ->addColumn('status_badge', function ($pengajuan) {
                    $statusLabels = [
                        'pending' => ['class' => 'badge-warning', 'text' => 'Diajukan'],
                        'approved' => ['class' => 'badge-success', 'text' => 'Diterima'],
                        'rejected' => ['class' => 'badge-danger', 'text' => 'Ditolak'],
                        'completed' => ['class' => 'badge-info', 'text' => 'Selesai']
                    ];
                    
                    $status = $statusLabels[$pengajuan->status] ?? ['class' => 'badge-secondary', 'text' => ucfirst($pengajuan->status)];
                    return '<span class="badge ' . $status['class'] . '">' . $status['text'] . '</span>';
                })
                ->editColumn('feedback_rating', function ($pengajuan) {
                    if ($pengajuan->feedback_rating) {
                        $stars = str_repeat('⭐', $pengajuan->feedback_rating);
                        return $stars . ' (' . $pengajuan->feedback_rating . ')';
                    }
                    return '-';
                })
                ->editColumn('created_at', function ($pengajuan) {
                    return \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y H:i');
                })
                ->addColumn('aksi', function ($pengajuan) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button onclick="modalAction(\'' . url('/admin/management-pengajuan-magang/' . $pengajuan->pengajuan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button onclick="modalAction(\'' . url('/admin/management-pengajuan-magang/' . $pengajuan->pengajuan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></button>';
                    $btn .= '<button onclick="modalAction(\'' . url('/admin/management-pengajuan-magang/' . $pengajuan->pengajuan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'aksi'])
                ->make(true);
                
        } catch (Exception $e) {
            Log::error('Error in PengajuanMagangController@list: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 200); // Gunakan status 200 agar DataTables tidak error
        }
    }

    // Tambahkan method untuk debugging
    public function debug()
    {
        try {
            // Cek total data
            $total = PengajuanMagangModel::count();
            echo "Total pengajuan: " . $total . "<br>";

            // Cek sample data
            $sample = PengajuanMagangModel::with(['mahasiswa.user', 'lowongan.perusahaan', 'dosen.user', 'periode'])->first();
            if ($sample) {
                echo "Sample data found:<br>";
                echo "ID: " . $sample->pengajuan_id . "<br>";
                echo "Status: " . $sample->status . "<br>";
                echo "Mahasiswa: " . ($sample->mahasiswa ? ($sample->mahasiswa->user ? $sample->mahasiswa->user->nama : 'No user') : 'No mahasiswa') . "<br>";
                echo "Lowongan: " . ($sample->lowongan ? $sample->lowongan->posisi : 'No lowongan') . "<br>";
                echo "Dosen: " . ($sample->dosen ? ($sample->dosen->user ? $sample->dosen->user->nama : 'No user') : 'No dosen') . "<br>";
                echo "Periode: " . ($sample->periode ? $sample->periode->nama : 'No periode') . "<br>";
            } else {
                echo "No sample data found<br>";
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}