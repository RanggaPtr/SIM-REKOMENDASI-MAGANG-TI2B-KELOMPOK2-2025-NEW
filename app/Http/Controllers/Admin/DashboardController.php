<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\PengajuanMagangModel;
use App\Models\UsersModel;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlah_dosen = UsersModel::where('role', 'dosen')->count();
        $jumlah_mahasiswa = UsersModel::where('role', 'mahasiswa')->count();
        $jumlah_magang = PengajuanMagangModel::where('status', 'diterima')->distinct('mahasiswa_id')->count('mahasiswa_id');

        return view('roles.admin.dashboard', [
            'activeMenu' => 'dashboard',
            'jumlah_dosen' => $jumlah_dosen,
            'jumlah_mahasiswa' => $jumlah_mahasiswa,
            'jumlah_magang' => $jumlah_magang,
        ]);
    }
}