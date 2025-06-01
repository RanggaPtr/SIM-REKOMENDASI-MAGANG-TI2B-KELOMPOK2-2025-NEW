<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\SertifikatDosenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SertifikatDosenController extends Controller
{
    public function index()
    {
        $dosenId = Auth::user()->dosen->dosen_id;

        $sertifikats = SertifikatDosenModel::where('dosen_id', $dosenId)->get();

        return view('roles.dosen.sertifikat.index', compact('sertifikats'));
    }

    public function create()
    {
        return view('roles.dosen.sertifikat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = $request->file('file_sertifikat')->store('sertifikat_dosen', 'public');

        SertifikatDosenModel::create([
            'dosen_id' => Auth::user()->dosen->dosen_id,
            'nama_sertifikat' => $request->nama_sertifikat,
            'penerbit' => $request->penerbit,
            'tanggal_terbit' => $request->tanggal_terbit,
            'file_sertifikat' => $filePath,
        ]);

        return redirect()->route('dosen.sertifikat.index')->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sertifikat = SertifikatDosenModel::findOrFail($id);
        $this->authorize('update', $sertifikat); // opsional jika ada policy

        return view('roles.dosen.sertifikat.edit', compact('sertifikat'));
    }

    public function update(Request $request, $id)
    {
        $sertifikat = SertifikatDosenModel::findOrFail($id);
        $this->authorize('update', $sertifikat); // opsional

        $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nama_sertifikat' => $request->nama_sertifikat,
            'penerbit' => $request->penerbit,
            'tanggal_terbit' => $request->tanggal_terbit,
        ];

        if ($request->hasFile('file_sertifikat')) {
            // Hapus file lama jika ada
            if ($sertifikat->file_sertifikat && Storage::disk('public')->exists($sertifikat->file_sertifikat)) {
                Storage::disk('public')->delete($sertifikat->file_sertifikat);
            }

            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('sertifikat_dosen', 'public');
        }

        $sertifikat->update($data);

        return redirect()->route('dosen.sertifikat.index')->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sertifikat = SertifikatDosenModel::findOrFail($id);
        $this->authorize('delete', $sertifikat); // opsional

        if ($sertifikat->file_sertifikat && Storage::disk('public')->exists($sertifikat->file_sertifikat)) {
            Storage::disk('public')->delete($sertifikat->file_sertifikat);
        }

        $sertifikat->delete();

        return redirect()->route('dosen.sertifikat.index')->with('success', 'Sertifikat berhasil dihapus.');
    }
}
