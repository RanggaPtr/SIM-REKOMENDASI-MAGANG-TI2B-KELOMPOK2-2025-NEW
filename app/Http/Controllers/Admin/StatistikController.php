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

        // Tren per bidang industri 
        $tren_industri = DB::table('t_pengajuan_magang')
            ->join('m_lowongan_magang', 't_pengajuan_magang.lowongan_id', '=', 'm_lowongan_magang.lowongan_id')
            ->join('m_perusahaan', 'm_lowongan_magang.perusahaan_id', '=', 'm_perusahaan.perusahaan_id')
            ->select('m_perusahaan.bidang_industri', DB::raw('COUNT(*) as total'))
            ->where('t_pengajuan_magang.status', 'diterima')
            ->groupBy('m_perusahaan.bidang_industri')
            ->orderBy('total', 'desc') // opsional: urutkan dari yang terbanyak
            ->get();

        $jumlah_dosen = UsersModel::where('role', 'dosen')->count();

        // Rasio peserta per dosen
        $jumlah_peserta = $jumlah_mahasiswa_magang;
        $rasio = $jumlah_dosen > 0
            ? round($jumlah_peserta / $jumlah_dosen, 2)
            : 0;

        return view('roles.admin.statistik-data-tren.index', compact(
            'jumlah_mahasiswa_magang',
            'tren_industri',
            'jumlah_dosen',
            'rasio'
        ));
    }
}