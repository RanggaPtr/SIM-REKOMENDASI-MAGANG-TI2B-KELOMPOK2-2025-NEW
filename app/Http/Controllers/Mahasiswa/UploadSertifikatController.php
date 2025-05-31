<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\SertifikatDosenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadSertifikatController extends Controller
{
    public function create()
    {
        return view('roles.dosen.upload-sertifikat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $dosenId = Auth::user()->dosen->dosen_id;

        // Simpan file sertifikat di folder public/sertifikat_dosen
        $filePath = $request->file('file_sertifikat')->store('sertifikat_dosen', 'public');

        SertifikatDosenModel::create([
            'dosen_id' => $dosenId,
            'nama_sertifikat' => $request->nama_sertifikat,
            'penerbit' => $request->penerbit,
            'tanggal_terbit' => $request->tanggal_terbit,
            'file_sertifikat' => $filePath,
        ]);

        return redirect()->route('dosen.upload.sertifikat')->with('success', 'Sertifikat berhasil diupload.');
    }
}
