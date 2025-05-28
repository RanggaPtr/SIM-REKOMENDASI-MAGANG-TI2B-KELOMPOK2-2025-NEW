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

class DashboardController extends Controller
{
    public function index()
    {
        $activeMenu = 'dashboard';
        $lowongans = LowonganMagangModel::with(['perusahaan', 'kompetensi', 'keahlian', 'lowonganKompetensi', 'lowonganKeahlian'])->latest()->get();
        $kompetensis = KompetensiModel::all();
        $keahlians = KeahlianModel::all();

        // Ambil periode yang tanggal_selesai >= hari ini
        $periodes = PeriodeMagangModel::where('tanggal_selesai', '>=', Carbon::today())->get();

        // Ambil semua skema
        $skemas = SkemaModel::all();

        return view('roles.mahasiswa.dashboard', compact('activeMenu', 'lowongans', 'kompetensis', 'keahlians', 'periodes', 'skemas'));
    }
}
