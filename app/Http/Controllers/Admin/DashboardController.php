<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\KompetensiModel;
use App\Models\PengajuanMagangModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Card Data
        $jumlah_dosen = UsersModel::where('role', 'dosen')->count();
        $jumlah_mahasiswa = UsersModel::where('role', 'mahasiswa')->count();
        $jumlah_magang = PengajuanMagangModel::where('status', 'diterima')->distinct('mahasiswa_id')->count('mahasiswa_id');

        // Grafik Penyebaran Penerimaan Magang
        $kompetensi = KompetensiModel::all();
        $data_kompetensi_diterima = [];
        foreach ($kompetensi as $k) {
            $jumlah = DB::table('t_pengajuan_magang')
                ->join('m_lowongan_magang', 't_pengajuan_magang.lowongan_id', '=', 'm_lowongan_magang.lowongan_id')
                ->join('m_lowongan_kompetensi', 'm_lowongan_magang.lowongan_id', '=', 'm_lowongan_kompetensi.lowongan_id')
                ->where('m_lowongan_kompetensi.kompetensi_id', $k->kompetensi_id)
                ->where('t_pengajuan_magang.status', 'diterima')
                ->count();
            $data_kompetensi_diterima[] = [
                'nama' => $k->nama,
                'total' => $jumlah
            ];
        }

        // Grafik Peminatan Bidang Industri Berdasarkan Kompetensi
        $data_kompetensi_pengajuan = [];
        foreach ($kompetensi as $k) {
            $jumlah = DB::table('t_pengajuan_magang')
                ->join('m_lowongan_magang', 't_pengajuan_magang.lowongan_id', '=', 'm_lowongan_magang.lowongan_id')
                ->join('m_lowongan_kompetensi', 'm_lowongan_magang.lowongan_id', '=', 'm_lowongan_kompetensi.lowongan_id')
                ->where('m_lowongan_kompetensi.kompetensi_id', $k->kompetensi_id)
                ->count();
            $data_kompetensi_pengajuan[] = [
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

        return view('roles.admin.dashboard', compact(
            'jumlah_dosen',
            'jumlah_mahasiswa',
            'jumlah_magang',
            'data_kompetensi_diterima',  
            'data_kompetensi_pengajuan',   
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