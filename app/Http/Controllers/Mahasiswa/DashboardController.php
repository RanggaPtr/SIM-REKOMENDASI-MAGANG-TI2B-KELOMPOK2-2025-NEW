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

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'dashboard';

        $kompetensis = KompetensiModel::all();
        $keahlians = KeahlianModel::all();
        $periodes = PeriodeMagangModel::where('tanggal_selesai', '>=', Carbon::today())->get();
        $skemas = SkemaModel::all();

        return view('roles.mahasiswa.dashboard', compact('activeMenu', 'kompetensis', 'keahlians', 'periodes', 'skemas'));
    }

    /**
     * Ambil data lowongan berdasarkan filter dari request.
     */
    public function getLowongan(Request $request)
    {
        $lowongans = $this->getFilteredLowongan($request);
        $recomendedLowongans = $this->rekomendasiKNN($lowongans);

        return response()->json([
            'lowongans' => view('component.intern-cards', ['lowongans' => $recomendedLowongans])->render()
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
            'wilayah' => $request->input('wilayah', []),
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
            })
            ->get();
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
        //         'similarity' => $item['similarity'],
        //         // Atribut dari lowongan
        //         'keahlian_lowongan' => $lowongan->lowonganKeahlian->pluck('keahlian_id')->toArray(),
        //         'kompetensi_lowongan' => $lowongan->lowonganKompetensi->pluck('kompetensi_id')->toArray(),
        //         'skema_lowongan' => $lowongan->skema_id,
        //         'periode_lowongan' => $lowongan->periode_id,
        //         'prodi_lowongan' => $lowongan->lowonganKompetensi->pluck('program_studi_id')->unique()->toArray(),
        //         // Atribut dari mahasiswa (di akhir)
        //         'keahlian_mahasiswa' => $mahasiswa->mahasiswaKeahlian->pluck('keahlian_id')->toArray(),
        //         'kompetensi_mahasiswa' => $mahasiswa->mahasiswaKompetensi->pluck('kompetensi_id')->toArray(),
        //         'skema_mahasiswa' => $mahasiswa->skema_id,
        //         'periode_mahasiswa' => $mahasiswa->periode_id,
        //         'prodi_mahasiswa' => $mahasiswa->programStudi->prodi_id,
        //     ];
        // }
        // echo json_encode($check, JSON_PRETTY_PRINT);

        // Ambil K teratas (atau semua)
        return collect($result)->pluck('lowongan');
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
}
