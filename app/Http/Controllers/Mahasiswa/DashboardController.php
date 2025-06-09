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

        $mahasiswa = MahasiswaModel::where('user_id', auth()->id())->first();
        $bookmarks = $mahasiswa ? $mahasiswa->bookmark()->pluck('lowongan_id')->toArray() : [];

        if ($request->input('only_bookmarked')) {
            $lowongans = $lowongans->whereIn('lowongan_id', $bookmarks);
        }

        // Rekomendasi (KNN/AHP)
        $recomendedLowongans = $this->rekomendasiKNN($lowongans);

        // Sorting setelah rekomendasi
        $sort = $request->input('sort', 'asc');
        $sortedLowongans = $recomendedLowongans->sortBy(function ($lowongan) {
            return $lowongan->judul;
        }, SORT_REGULAR, $sort === 'desc')->values();

        return response()->json([
            'lowongans' => view('component.intern-cards', [
                'lowongans' => $sortedLowongans,
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

    /**
     * Rekomendasi menggunakan metode KNN (existing)
     */
    public function rekomendasiKNN($lowongans)
    {
        // 1. Ambil data mahasiswa
        $mahasiswa = MahasiswaModel::with([
            'mahasiswaKeahlian',
            'mahasiswaKompetensi',
            'skema',
            'periode',
            'programStudi',
            'lokasi'
        ])->where('user_id', auth()->id())->first();
        if (!$mahasiswa) {
            // Handle jika mahasiswa tidak ditemukan
            return [];
        }

        // Ambil koordinat wilayah mahasiswa
        $mahasiswaWilayah = $mahasiswa->lokasi;
        $mlat = $mahasiswaWilayah ? $mahasiswaWilayah->latitude : null;
        $mlon = $mahasiswaWilayah ? $mahasiswaWilayah->longitude : null;

        // Siapkan array untuk menyimpan semua jarak (untuk normalisasi)
        $allDistances = [];

        // Hitung semua jarak dulu
        foreach ($lowongans as $lowongan) {
            // Ambil wilayah perusahaan
            $perusahaanWilayah = $lowongan->perusahaan && $lowongan->perusahaan->lokasi
                ? $lowongan->perusahaan->lokasi
                : null;
            $plat = $perusahaanWilayah ? $perusahaanWilayah->latitude : null;
            $plon = $perusahaanWilayah ? $perusahaanWilayah->longitude : null;

            if ($mlat !== null && $mlon !== null && $plat !== null && $plon !== null) {
                $distance = self::haversineDistance($mlat, $mlon, $plat, $plon);
            } else {
                $distance = null; // Tidak bisa dihitung
            }
            $allDistances[] = $distance;
        }

        // Normalisasi jarak: semakin dekat semakin tinggi similarity
        $maxDistance = max(array_filter($allDistances, fn($d) => $d !== null)) ?: 1;
        $minDistance = min(array_filter($allDistances, fn($d) => $d !== null)) ?: 0;

        $result = [];
        $i = 0;
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

            // Distance similarity (semakin dekat semakin tinggi)
            $distance = $allDistances[$i];
            if ($distance === null) {
                $sim_distance = 0; // Tidak diketahui, treat as lowest similarity
            } elseif ($maxDistance == $minDistance) {
                $sim_distance = 1; // Semua jarak sama
            } else {
                // Normalisasi: 1 untuk jarak terdekat, 0 untuk terjauh
                $sim_distance = 1 - (($distance - $minDistance) / ($maxDistance - $minDistance));
            }

            // Bobot bisa disesuaikan, contoh: total 1.0
            $similarity = (
                0.25 * $sim_keahlian +
                0.25 * $sim_kompetensi +
                0.2 * $sim_distance +
                0.1 * $sim_skema +
                0.1 * $sim_periode +
                0.1 * $sim_prodi
            );

            $result[] = [
                'lowongan' => $lowongan,
                'similarity' => $similarity
            ];
            $i++;
        }

        // Urutkan berdasarkan similarity tertinggi
        usort($result, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        // Debugging: Uncomment to see the raw result
        // $check = [];
        // $i = 0;
        // foreach ($result as $item) {
        //     $lowongan = $item['lowongan'];
        //     $distance = $allDistances[$i];
        //     // Hitung ulang sim_distance untuk ditampilkan
        //     if ($distance === null) {
        //         $sim_distance = 0;
        //     } elseif ($maxDistance == $minDistance) {
        //         $sim_distance = 1;
        //     } else {
        //         $sim_distance = 1 - (($distance - $minDistance) / ($maxDistance - $minDistance));
        //     }
        //     $check[] = [
        //         'id' => $lowongan->lowongan_id,
        //         'judul' => $lowongan->judul,
        //         'similarity' => round($item['similarity'], 4),
        //         'jarak_km' => $distance,
        //         'sim_distance' => round($sim_distance, 4), // Tambahkan similarity distance
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
        //     $i++;
        // }
        // echo response()->json($check, 200, [], JSON_PRETTY_PRINT);

        // Kirim data lowongan yang sudah diurutkan
        return collect($result)->pluck('lowongan');
    }

    //Rekomendasi menggunakan metode AHP (Analytical Hierarchy Process)

    public function rekomendasiAHP($lowongans)
    {
        $mahasiswa = MahasiswaModel::with([
            'mahasiswaKeahlian',
            'mahasiswaKompetensi',
            'skema',
            'periode',
            'programStudi',
            'lokasi'
        ])->where('user_id', auth()->id())->first();

        if (!$mahasiswa) return [];

        $mlat = $mahasiswa->lokasi->latitude ?? null;
        $mlon = $mahasiswa->lokasi->longitude ?? null;

        // --- 1. Matriks Perbandingan Berpasangan antar Kriteria ---
        $criteria = ['keahlian', 'kompetensi', 'distance', 'skema', 'periode', 'prodi'];
        $criteriaMatrix = [
            [1,   2,   4,   3,   5,   4],
            [0.5, 1,   2,   1.5, 3,   2],
            [0.25, 0.5, 1,   0.75, 2,   1.5],
            [0.33, 0.67, 1.33, 1,   2.5, 2],
            [0.2, 0.33, 0.5, 0.4, 1,   0.67],
            [0.25, 0.5, 0.67, 0.5, 1.5, 1],
        ];

        // --- 2. Hitung Eigenvector Bobot Kriteria ---
        $n = count($criteria);
        // a. Hitung jumlah kolom
        $colSums = array_fill(0, $n, 0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $colSums[$j] += $criteriaMatrix[$i][$j];
            }
        }
        // b. Normalisasi matriks
        $normMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normMatrix[$i][$j] = $criteriaMatrix[$i][$j] / $colSums[$j];
            }
        }
        // c. Hitung eigenvector (rata-rata baris normalisasi)
        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $weights[$i] = array_sum($normMatrix[$i]) / $n;
        }
        // d. Cek Konsistensi (CI & CR)
        // Lambda max
        $lambdaMax = 0;
        // Perhitungan lambdaMax lebih akurat
        $Aw = [];
        for ($i = 0; $i < $n; $i++) {
            $Aw[$i] = 0;
            for ($j = 0; $j < $n; $j++) {
                $Aw[$i] += $criteriaMatrix[$i][$j] * $weights[$j];
            }
        }
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $lambdaMax += $Aw[$i] / $weights[$i];
        }
        $lambdaMax /= $n;

        $CI = ($lambdaMax - $n) / ($n - 1);
        $CR = $CI / (1.24); // Indeks Konsistensi untuk matriks 6x6
        if ($CR > 0.1) {
            // Konsistensi buruk, bisa return error atau warning
            // return []; // Atau lanjutkan dengan warning
        }

        // --- 3. Matriks Perbandingan Berpasangan Antar Alternatif untuk Setiap Kriteria ---
        $altCount = count($lowongans);
        $altMatrix = [];
        foreach ($criteria as $cIdx => $cName) {
            // a. Hitung nilai untuk setiap alternatif pada kriteria ini
            $values = [];
            foreach ($lowongans as $lowongan) {
                switch ($cName) {
                    case 'keahlian':
                        $a = $mahasiswa->mahasiswaKeahlian->pluck('keahlian_id')->toArray();
                        $b = $lowongan->lowonganKeahlian->pluck('keahlian_id')->toArray();
                        $values[] = self::jaccardSimilarity($a, $b);
                        break;
                    case 'kompetensi':
                        $a = $mahasiswa->mahasiswaKompetensi->pluck('kompetensi_id')->toArray();
                        $b = $lowongan->lowonganKompetensi->pluck('kompetensi_id')->toArray();
                        $values[] = self::jaccardSimilarity($a, $b);
                        break;
                    case 'distance':
                        $plat = $lowongan->perusahaan->lokasi->latitude ?? null;
                        $plon = $lowongan->perusahaan->lokasi->longitude ?? null;
                        $dist = ($mlat && $mlon && $plat && $plon) ? self::haversineDistance($mlat, $mlon, $plat, $plon) : null;
                        $values[] = $dist === null ? 0 : $dist;
                        break;
                    case 'skema':
                        $values[] = $mahasiswa->skema_id === $lowongan->skema_id ? 1 : 0;
                        break;
                    case 'periode':
                        $values[] = $mahasiswa->periode_id === $lowongan->periode_id ? 1 : 0;
                        break;
                    case 'prodi':
                        $a = [$mahasiswa->programStudi->prodi_id];
                        $b = $lowongan->lowonganKompetensi->pluck('program_studi_id')->unique()->toArray();
                        $values[] = self::jaccardSimilarity($a, $b);
                        break;
                }
            }
            // b. Untuk distance, ubah ke similarity (semakin kecil semakin baik)
            if ($cName == 'distance') {
                $max = max($values) ?: 1;
                $min = min($values) ?: 0;
                foreach ($values as &$v) {
                    $v = $max == $min ? 1 : 1 - (($v - $min) / ($max - $min));
                }
            }
            // c. Matriks perbandingan berpasangan antar alternatif
            $pairwise = [];
            for ($i = 0; $i < $altCount; $i++) {
                $pairwise[$i] = [];
                for ($j = 0; $j < $altCount; $j++) {
                    // Hindari pembagian nol
                    if ($values[$j] == 0) {
                        $pairwise[$i][$j] = $values[$i] == 0 ? 1 : 9; // 9 = sangat lebih baik
                    } else {
                        $pairwise[$i][$j] = $values[$i] / $values[$j];
                    }
                }
            }
            // d. Normalisasi matriks & eigenvector alternatif
            $colSum = array_fill(0, $altCount, 0);
            for ($j = 0; $j < $altCount; $j++) {
                for ($i = 0; $i < $altCount; $i++) {
                    $colSum[$j] += $pairwise[$i][$j];
                }
            }
            $norm = [];
            for ($i = 0; $i < $altCount; $i++) {
                for ($j = 0; $j < $altCount; $j++) {
                    $norm[$i][$j] = $pairwise[$i][$j] / $colSum[$j];
                }
            }
            $ev = [];
            for ($i = 0; $i < $altCount; $i++) {
                $ev[$i] = array_sum($norm[$i]) / $altCount;
            }
            $altMatrix[$cName] = $ev;
        }

        // --- 4. Hitung skor akhir setiap alternatif ---
        $scores = [];
        for ($i = 0; $i < $altCount; $i++) {
            $score = 0;
            $detail = [];
            foreach ($criteria as $cIdx => $cName) {
                $detail[$cName] = [
                    'bobot_kriteria' => round($weights[$cIdx], 4),
                    'bobot_alternatif' => round($altMatrix[$cName][$i], 4),
                    'kontribusi' => round($weights[$cIdx] * $altMatrix[$cName][$i], 4)
                ];
                $score += $weights[$cIdx] * $altMatrix[$cName][$i];
            }
            $scores[] = [
                'lowongan' => $lowongans[$i],
                'score' => $score,
                'detail' => $detail
            ];
        }
        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);

        // DEBUG: tampilkan detail perhitungan
        // Uncomment untuk melihat hasil debug di browser/response
        // $debug = [];
        // $debug = [
        //     'CR' => $CR,
        //     'Score Lowongan' => [],
        // ];

        // foreach ($scores as $item) {
        //     $lowongan = $item['lowongan'];
        //     $debug['Score Lowongan'][] = [
        //         'id' => $lowongan->lowongan_id,
        //         'judul' => $lowongan->judul,
        //         'score' => round($item['score'], 4),
        //         'detail' => $item['detail'],
        //     ];
        // }

        // echo response()->json($debug, 200, [], JSON_PRETTY_PRINT);
        // exit;

        return collect($scores)->pluck('lowongan');
    }

    private static function jaccardSimilarity(array $a, array $b): float
    {
        $intersect = count(array_intersect($a, $b));
        $union = count(array_unique(array_merge($a, $b)));
        return $union > 0 ? $intersect / $union : 0;
    }

    public function getLowonganDetail(Request $request)
    {
        $id = $request->input('lowongan_id');
        $lowongan = LowonganMagangModel::with(['perusahaan', 'periode', 'skema', 'lowonganKeahlian.keahlian', 'lowonganKompetensi.kompetensi'])
            ->findOrFail($id);


        $reviews = \App\Models\PengajuanMagangModel::with(['mahasiswa'])
            ->whereHas('lowongan', function ($q) use ($lowongan) {
                $q->where('perusahaan_id', $lowongan->perusahaan_id);
            })
            ->whereNotNull('feedback_rating')
            ->orderByDesc('pengajuan_id')
            ->get();
        
        // Tambahkan selisih hari ke dalam variabel lowongan
        $lowongan->selisih_hari = $lowongan->getSelisihHari();

        $html = view('component.detail-lowongan-overlay', compact('lowongan', 'reviews'))->render();

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

    // Haversine formula to calculate distance in kilometers
    public static function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
