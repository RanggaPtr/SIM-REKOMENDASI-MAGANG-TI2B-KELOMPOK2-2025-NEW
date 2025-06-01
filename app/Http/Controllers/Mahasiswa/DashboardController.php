<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LowonganMagangModel;
use Illuminate\Support\Facades\Storage;
use App\Models\KompetensiModel;
use App\Models\KeahlianModel;
use App\Models\PeriodeMagangModel;
use App\Models\SkemaModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'dashboard';

        $kompetensis = KompetensiModel::all();
        $keahlians = KeahlianModel::all();
        $periodes = PeriodeMagangModel::where('tanggal_selesai', '>=', Carbon::today())->get();
        $skemas = SkemaModel::all();

        // Ambil semua lowongan untuk load awal
        $lowongans = $this->getFilteredLowongan($request);

        return view('roles.mahasiswa.dashboard', compact('activeMenu', 'kompetensis', 'keahlians', 'periodes', 'skemas', 'lowongans'));
    }

    /**
     * Ambil data lowongan berdasarkan filter dari request.
     */
    public function getLowongan(Request $request)
    {
        $lowongans = $this->getFilteredLowongan($request);
        return response()->json([
            'lowongans' => view('component.intern-cards', ['lowongans' => $lowongans])->render()
        ]);
    }

    private function getFilteredLowongan(Request $request)
    {
        $params = [
            'keyword' => $request->input('keyword', []),
            'keahlian' => $request->input('keahlian', []),
            'kompetensi' => $request->input('kompetensi', []),
            'skema' => $request->input('skema', []),
            'periode' => $request->input('periode', []),
            'tunjangan' => $request->input('tunjangan', []),
            'rating' => $request->input('rating', []), // Tambahkan ini
        ];

        return LowonganMagangModel::with(['perusahaan', 'periode', 'skema', 'lowonganKeahlian.keahlian', 'lowonganKompetensi.kompetensi'])
            // ->where('tanggal_tutup', '>=', Carbon::today())
            ->when(!empty($params['keahlian']), function ($query) use ($params) {
                $query->whereHas('keahlians', function ($q) use ($params) {
                    $q->whereIn('m_lowongan_keahlian.keahlian_id', $params['keahlian']);
                });
            })
            ->when(!empty($params['kompetensi']), function ($query) use ($params) {
                $query->whereHas('kompetensis', function ($q) use ($params) {
                    $q->whereIn('m_lowongan_kompetensi.kompetensi_id', $params['kompetensi']);
                });
            })
            ->when(!empty($params['skema']), function ($query) use ($params) {
                $query->whereIn('skema_id', $params['skema']);
            })
            ->when(!empty($params['periode']), function ($query) use ($params) {
                $query->whereIn('periode_id', $params['periode']);
            })
            ->when(!empty($params['tunjangan']), function ($query) use ($params) {
                $query->where(function($q) use ($params) {
                    foreach ($params['tunjangan'] as $tunjangan) {
                        if ($tunjangan == 1) {
                            $q->orWhereBetween('tunjangan', [0, 500000]);
                        } elseif ($tunjangan == 2) {
                            $q->orWhereBetween('tunjangan', [500001, 1000000]);
                        } elseif ($tunjangan == 3) {
                            $q->orWhereBetween('tunjangan', [1000001, 1500000]);
                        } elseif ($tunjangan == 4) {
                            $q->orWhere('tunjangan', '>', 1500000);
                        }
                    }
                });
            })
            ->when(!empty($params['rating']), function ($query) use ($params) {
                $query->whereHas('perusahaan', function ($q) use ($params) {
                    $q->where(function($subQ) use ($params) {
                        foreach ($params['rating'] as $rating) {
                            if ($rating == 1) {
                                $subQ->orWhere('rating', '>=', 0);
                            } elseif ($rating == 2) {
                                $subQ->orWhere('rating', '>=', 2);
                            } elseif ($rating == 3) {
                                $subQ->orWhere('rating', '>=', 3);
                            } elseif ($rating == 4) {
                                $subQ->orWhere('rating', '>=', 4);
                            } elseif ($rating == 5) {
                                $subQ->orWhere('rating', '>=', 5);
                            }
                        }
                    });
                });
            })
            ->get();
    }
}
