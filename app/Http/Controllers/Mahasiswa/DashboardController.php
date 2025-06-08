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
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Http;
use App\Models\BookmarkModel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'dashboard';

        $kompetensis = KompetensiModel::all();
        $keahlians = KeahlianModel::all();
        $periodes = PeriodeMagangModel::where('tanggal_selesai', '>=', Carbon::today())->get();
        $skemas = SkemaModel::all();

        $mahasiswa = MahasiswaModel::where('user_id', auth()->id())->first();

        return view('roles.mahasiswa.dashboard', compact('activeMenu', 'kompetensis', 'keahlians', 'periodes', 'skemas'));
    }

    /**
     * Ambil data lowongan berdasarkan filter dari request.
     */
    public function getLowongan(Request $request)
    {
        $lowongans = $this->getFilteredLowongan($request);

        // Filter only bookmarked if requested
        $mahasiswa = MahasiswaModel::where('user_id', auth()->id())->first();
        $bookmarks = $mahasiswa ? $mahasiswa->bookmark()->pluck('lowongan_id')->toArray() : [];

        if ($request->input('only_bookmarked')) {
            $lowongans = $lowongans->whereIn('lowongan_id', $bookmarks);
        }

        $recomendedLowongans = $this->rekomendasiKNN($lowongans);

        return response()->json([
            'lowongans' => view('component.intern-cards', [
                'lowongans' => $recomendedLowongans,
                'bookmarks' => $bookmarks
            ])->render()
        ]);
    }

    private function getFilteredLowongan(Request $request)
    {
        $params = [
            'keyword' => $request->input('keyword', ''), // <-- ubah default ke string kosong
            'keahlian' => $request->input('keahlian', []),
            'kompetensi' => $request->input('kompetensi', []),
            'skema' => $request->input('skema', []),
            'periode' => $request->input('periode', []),
            'tunjangan' => $request->input('tunjangan', []),
            'wilayah' => $request->input('wilayah', []),
            'rating' => $request->input('rating', []),
            'sort' => $request->input('sort', 'asc'),
        ];

        $query = LowonganMagangModel::with(['perusahaan', 'periode', 'skema', 'lowonganKeahlian.keahlian', 'lowonganKompetensi.kompetensi'])
            // ->where('tanggal_tutup', '>=', Carbon::today())
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = trim($params['keyword']);
                $query->where(function ($q) use ($keyword) {
                    $q->where('judul', 'like', "%{$keyword}%")
                        ->orWhereHas('perusahaan', function ($q2) use ($keyword) {
                            $q2->where('nama', 'like', "%{$keyword}%");
                        });
                });
            })
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
            ->when(!empty($params['wilayah']), function ($query) use ($params) {
                $query->whereHas('perusahaan', function ($q) use ($params) {
                    $q->whereIn('wilayah_id', $params['wilayah']);
                });
            })
            ->when(!empty($params['skema']), function ($query) use ($params) {
                $query->whereIn('skema_id', $params['skema']);
            })
            ->when(!empty($params['periode']), function ($query) use ($params) {
                $query->whereIn('periode_id', $params['periode']);
            })
            ->when(!empty($params['tunjangan']), function ($query) use ($params) {
                $query->where(function ($q) use ($params) {
                    foreach ($params['tunjangan'] as $tunjangan) {
                        if ($tunjangan == 'berbayar') {
                            $q->orWhere('tunjangan', true);
                        } elseif ($tunjangan == 'tidak') {
                            $q->orWhere('tunjangan', false);
                        }
                    }
                });
            })
            ->when(!empty($params['rating']), function ($query) use ($params) {
                $query->whereHas('perusahaan', function ($q) use ($params) {
                    $q->where(function ($subQ) use ($params) {
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
            });

        // Sorting by judul (or you can change to another field)
        $query->orderBy('judul', $params['sort'] == 'desc' ? 'desc' : 'asc');

        return $query->get();
    }

    public function rekomendasiKNN($lowongans)
    {
        // 1. Ambil data mahasiswa
        $mahasiswa = MahasiswaModel::with(['mahasiswaKeahlian', 'mahasiswaKompetensi', 'skema', 'periode', 'programStudi'])
            ->where('user_id', auth()->id())
            ->first();
        if (!$mahasiswa) {
            // Handle jika mahasiswa tidak ditemukan
            return [];
        }

        // Gunakan parameter $lowongans yang sudah difilter
        $result = [];

        foreach ($lowongans as $lowongan) {
            // Hitung kemiripan keahlian
            $mahasiswaKeahlian = $mahasiswa->mahasiswaKeahlian->pluck('keahlian_id')->toArray();
            $lowonganKeahlian = $lowongan->lowonganKeahlian->pluck('keahlian_id')->toArray();
            $keahlian_sama = count(array_intersect($mahasiswaKeahlian, $lowonganKeahlian));
            $keahlian_total = count(array_unique(array_merge($mahasiswaKeahlian, $lowonganKeahlian)));
            $sim_keahlian = $keahlian_total ? $keahlian_sama / $keahlian_total : 0;

            // Hitung kemiripan kompetensi
            $mahasiswaKompetensi = $mahasiswa->mahasiswaKompetensi->pluck('kompetensi_id')->toArray();
            $lowonganKompetensi = $lowongan->lowonganKompetensi->pluck('kompetensi_id')->toArray();
            $kompetensi_sama = count(array_intersect($mahasiswaKompetensi, $lowonganKompetensi));
            $kompetensi_total = count(array_unique(array_merge($mahasiswaKompetensi, $lowonganKompetensi)));
            $sim_kompetensi = $kompetensi_total ? $kompetensi_sama / $kompetensi_total : 0;

            // Skema, Periode, Prodi (dari kompetensi)
            $sim_skema = $mahasiswa->skema_id == $lowongan->skema_id ? 1 : 0;
            $sim_periode = $mahasiswa->periode_id == $lowongan->periode_id ? 1 : 0;

            // Prodi dari kompetensi
            $prodi_mahasiswa = [$mahasiswa->programStudi->prodi_id]; // ubah jadi array
            $prodi_lowongan = $lowongan->lowonganKompetensi->pluck('program_studi_id')->unique()->toArray();
            $prodi_sama = count(array_intersect($prodi_mahasiswa, $prodi_lowongan));
            $prodi_total = count(array_unique(array_merge($prodi_mahasiswa, $prodi_lowongan)));
            $sim_prodi = $prodi_total ? $prodi_sama / $prodi_total : 0;

            // Bobot bisa disesuaikan
            $similarity = (
                0.3 * $sim_keahlian +
                0.3 * $sim_kompetensi +
                0.15 * $sim_skema +
                0.15 * $sim_periode +
                0.1 * $sim_prodi
            );

            $result[] = [
                'lowongan' => $lowongan,
                'similarity' => $similarity
            ];
        }

        // Urutkan berdasarkan similarity tertinggi
        usort($result, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        // Debugging: tampilkan hasil rekomendasi
        // $check = [];
        // foreach ($result as $item) {
        //     $lowongan = $item['lowongan'];

        //     $check[] = [
        //         'id' => $lowongan->lowongan_id,
        //         'judul' => $lowongan->judul,
        //         'similarity' => $item['similarity'],
        //         // Atribut dari lowongan (pakai nama)
        //         'keahlian_lowongan' => $lowongan->lowonganKeahlian->pluck('keahlian.nama')->toArray(),
        //         'kompetensi_lowongan' => $lowongan->lowonganKompetensi->pluck('kompetensi.nama')->toArray(),
        //         'skema_lowongan' => $lowongan->skema ? $lowongan->skema->nama : null,
        //         'periode_lowongan' => $lowongan->periode ? $lowongan->periode->nama : null,
        //         'prodi_lowongan' => $lowongan->lowonganKompetensi->pluck('kompetensi.programStudi.nama')->unique()->toArray(),
        //         // Atribut dari mahasiswa (pakai nama)
        //         'keahlian_mahasiswa' => $mahasiswa->mahasiswaKeahlian->pluck('keahlian.nama')->toArray(),
        //         'kompetensi_mahasiswa' => $mahasiswa->mahasiswaKompetensi->pluck('kompetensi.nama')->toArray(),
        //         'skema_mahasiswa' => $mahasiswa->skema ? $mahasiswa->skema->nama : null,
        //         'periode_mahasiswa' => $mahasiswa->periode ? $mahasiswa->periode->nama : null,
        //         'prodi_mahasiswa' => $mahasiswa->programStudi ? $mahasiswa->programStudi->nama : null,
        //     ];
        // }
        // echo json_encode($check, JSON_PRETTY_PRINT);

        // Ambil K teratas (atau semua)
        return collect($result)->pluck('lowongan');
    }

    private function haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371) // km
    {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));
        return $earthRadius * $angle;
    }


    public function getLowonganDetail(Request $request)
    {
        $id = $request->input('lowongan_id');
        $lowongan = LowonganMagangModel::with(['perusahaan', 'periode', 'skema', 'lowonganKeahlian.keahlian', 'lowonganKompetensi.kompetensi'])
            ->findOrFail($id);

        // Tambahkan selisih hari ke dalam variabel lowongan
        $lowongan->selisih_hari = $lowongan->getSelisihHari();

        $html = view('component.detail-lowongan-overlay', compact('lowongan'))->render();

        return response()->json(['html' => $html]);
    }

    public function addBookmark(Request $request)
    {
        $mahasiswa = MahasiswaModel::where('user_id', auth()->id())->first();
        if (!$mahasiswa) return response()->json(['success' => false, 'message' => 'Mahasiswa not found'], 404);

        $bookmark = BookmarkModel::firstOrCreate([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => $request->input('lowongan_id'),
        ]);
        return response()->json(['success' => true]);
    }

    public function removeBookmark(Request $request)
    {
        $mahasiswa = MahasiswaModel::where('user_id', auth()->id())->first();
        if (!$mahasiswa) return response()->json(['success' => false, 'message' => 'Mahasiswa not found'], 404);

        BookmarkModel::where([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lowongan_id' => $request->input('lowongan_id'),
        ])->delete();
        return response()->json(['success' => true]);
    }
}
