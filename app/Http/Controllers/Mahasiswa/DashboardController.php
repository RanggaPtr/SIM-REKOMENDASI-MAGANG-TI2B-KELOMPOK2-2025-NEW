<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use App\Models\SkemaModel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $activeMenu = 'dashboard';
        $lowongans = LowonganMagangModel::with('perusahaan')->latest()->get();

        // Ambil periode yang belum lewat
        $periodes = PeriodeMagangModel::where('tanggal_selesai', '>=', Carbon::today())->get();

        // Ambil semua skema (jika ingin tampilkan juga)
        $skemas = SkemaModel::all();

        return view('roles.mahasiswa.dashboard', compact('activeMenu', 'lowongans', 'periodes', 'skemas'));
    }
}