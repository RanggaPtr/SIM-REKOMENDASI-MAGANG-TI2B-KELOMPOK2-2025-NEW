<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluasiMagang; // Sesuaikan dengan model yang Anda gunakan
use Illuminate\Support\Facades\DB; // Jika menggunakan query builder

class DosenController extends Controller
{
    public function evaluasiMagang()
    {
        // Ambil data evaluasi magang
        // Pilih salah satu metode di bawah sesuai dengan struktur aplikasi Anda
        
        // Menggunakan Eloquent Model (disarankan)
        // $evaluasiMagangList = EvaluasiMagang::all();
        
        // Atau menggunakan Query Builder
        $evaluasiMagangList = DB::table('evaluasi_magang')->get();
        
        // Jika tabel belum ada, gunakan array kosong untuk sementara
        // $evaluasiMagangList = [];
        
        return view('roles.dosen.evaluasi-magang', [
            'activeMenu' => 'evaluasiMagang',
            'evaluasiMagangList' => $evaluasiMagangList
        ]);
    }
}