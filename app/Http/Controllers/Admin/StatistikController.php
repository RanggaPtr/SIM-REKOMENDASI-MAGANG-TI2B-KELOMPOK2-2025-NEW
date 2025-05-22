<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $jumlah_mahasiswa_magang = PengajuanMagangModel::where('status', 'diterima')->count();

    $tren_industri = DB::table('t_pengajuan_magang')
        ->join('m_lowongan_magang', 't_pengajuan_magang.lowongan_id', '=', 'm_lowongan_magang.lowongan_id')
        ->select('m_lowongan_magang.bidang_keahlian', DB::raw('count(*) as total'))
        ->where('t_pengajuan_magang.status', 'diterima')
        ->groupBy('m_lowongan_magang.bidang_keahlian')
        ->get();

    $jumlah_dosen = UsersModel::where('role', 'dosen')->count();

    $jumlah_peserta = $jumlah_mahasiswa_magang;
    $rasio = $jumlah_dosen > 0 ? round($jumlah_peserta / $jumlah_dosen, 2) : 0;

    return view('roles.admin.statistik-data-tren.index', [
        'activeMenu' => 'analitik',
        'jumlah_mahasiswa_magang' => $jumlah_mahasiswa_magang,
        'tren_industri' => $tren_industri,
        'jumlah_dosen' => $jumlah_dosen,
        'rasio' => $rasio
        ]);
    }
}