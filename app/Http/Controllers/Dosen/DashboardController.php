<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LowonganMagangModel;
use App\Models\SertifikatDosenModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $activeMenu = 'Dashboard';
        $lowongans = LowonganMagangModel::with('perusahaan')->latest()->get();
        
        // Ambil sertifikat dosen yang login
        $sertifikats = SertifikatDosenModel::where('dosen_id', Auth::user()->dosen->dosen_id)
            ->latest()
            ->get();

        return view('roles.dosen.dashboard', compact('activeMenu', 'lowongans', 'sertifikats'));
    }
}