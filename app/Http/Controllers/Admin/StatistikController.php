<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KompetensiModel;
use App\Models\PengajuanMagangModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $kompetensi = KompetensiModel::all();

        $data_kompetensi = [];
        foreach ($kompetensi as $k) {
            $jumlah = DB::table('t_pengajuan_magang')
                ->join('m_lowongan_magang', 't_pengajuan_magang.lowongan_id', '=', 'm_lowongan_magang.lowongan_id')
                ->join('m_lowongan_kompetensi', 'm_lowongan_magang.lowongan_id', '=', 'm_lowongan_kompetensi.lowongan_id')
                ->where('m_lowongan_kompetensi.kompetensi_id', $k->kompetensi_id)
                ->where('t_pengajuan_magang.status', 'diterima')
                ->count();

            $data_kompetensi[] = [
                'nama' => $k->nama,
                'total' => $jumlah
            ];
        }

        $total_pengajuan = PengajuanMagangModel::count();
        $total_diterima = PengajuanMagangModel::where('status', 'diterima')->count();
        $total_ditolak = PengajuanMagangModel::where('status', 'ditolak')->count();
        $total_pending = $total_pengajuan - $total_diterima - $total_ditolak;

        $persen_diterima = $total_pengajuan > 0 ? round(($total_diterima / $total_pengajuan) * 100, 2) : 0;
        $persen_ditolak = $total_pengajuan > 0 ? round(($total_ditolak / $total_pengajuan) * 100, 2) : 0;
        $persen_pending = $total_pengajuan > 0 ? round(($total_pending / $total_pengajuan) * 100, 2) : 0;

        return view('roles.admin.statistik-data-tren.index', compact(
            'data_kompetensi',
            'total_pengajuan',
            'total_diterima',
            'total_ditolak',
            'total_pending',
            'persen_diterima',
            'persen_ditolak',
            'persen_pending'
        ));
    }
}