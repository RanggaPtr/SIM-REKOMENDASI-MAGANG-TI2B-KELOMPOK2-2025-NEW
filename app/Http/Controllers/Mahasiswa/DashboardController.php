<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LowonganMagangModel;

class DashboardController extends Controller
{
    public function index()
    {
        $activeMenu = 'dashboard';
        $lowongans = LowonganMagangModel::with('perusahaan')->latest()->get();
        return view('roles.mahasiswa.dashboard', compact('activeMenu', 'lowongans'));
    }
}