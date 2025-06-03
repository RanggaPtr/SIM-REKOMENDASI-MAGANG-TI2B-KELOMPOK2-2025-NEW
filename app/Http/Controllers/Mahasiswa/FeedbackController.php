<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function index()
    {
        $mahasiswa_id = Auth::user()->mahasiswa->mahasiswa_id;
        
        $pengajuan = PengajuanMagangModel::where('mahasiswa_id', $mahasiswa_id)
            ->where('status', 'selesai')
            ->first();
            
        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Anda tidak memiliki magang yang sudah selesai untuk diberikan feedback');
        }
        
        return view('roles.mahasiswa.feedback', [
       
            'pengajuan' => $pengajuan
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pengajuan_id' => 'required|exists:t_pengajuan_magang,pengajuan_id',
            'rating' => 'required|integer|between:1,5',
            'deskripsi' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mahasiswa_id = Auth::user()->mahasiswa->mahasiswa_id;
        
        $pengajuan = PengajuanMagangModel::where('pengajuan_id', $request->pengajuan_id)
            ->where('mahasiswa_id', $mahasiswa_id)
            ->where('status', 'selesai')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan magang tidak valid atau belum selesai');
        }

        $pengajuan->update([
            'feedback_rating' => $request->rating,
            'feedback_deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('mahasiswa.feedback')
            ->with('success', 'Feedback berhasil disimpan');
    }
}