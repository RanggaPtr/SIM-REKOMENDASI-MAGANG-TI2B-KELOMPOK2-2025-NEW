<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KeahlianModel;
use App\Models\KompetensiModel;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use App\Models\PerusahaanModel;
use App\Models\SkemaModel;
use Illuminate\Http\Request;

class LowonganMagangController extends Controller
{
    public function index()
    {
        $lowongans = LowonganMagangModel::with(['perusahaan', 'periode', 'skema'])->get();
        return view('roles.admin.management-lowongan-magang.index', compact('lowongans'));
    }

    public function show($id)
    {
        $lowongan = LowonganMagangModel::with(['perusahaan', 'periode', 'skema', 'keahlian', 'kompetensi'])
            ->findOrFail($id);
        return view('roles.admin.management-lowongan-magang.show', compact('lowongan'));
    }

    public function create()
    {
        $periodes = PeriodeMagangModel::all();
        $skemas = SkemaModel::all();
        $perusahaans = PerusahaanModel::all();
        $keahlians = KeahlianModel::all();
        $kompetensis = KompetensiModel::all();

        return view('roles.admin.management-lowongan-magang.create', compact('periodes', 'skemas', 'perusahaans', 'keahlians', 'kompetensis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perusahaan_id' => 'required|exists:m_perusahaan,perusahaan_id',
            'periode_id' => 'required|exists:m_periode_magang,periode_id',
            'skema_id' => 'required|exists:m_skema,skema_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'minimal_ipk' => 'required|numeric|min:0|max:4',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'bidang_keahlian' => 'required|string|max:255',
            'keahlian' => 'array|required',
            'keahlian.*' => 'exists:m_keahlian,keahlian_id',
            'kompetensi' => 'array|required|size:1', // Exactly one kompetensi
            'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id',
        ]);

        $lowongan = LowonganMagangModel::create($request->only([
            'perusahaan_id', 'periode_id', 'skema_id', 'judul', 'deskripsi', 'persyaratan',
            'minimal_ipk', 'tanggal_buka', 'tanggal_tutup', 'bidang_keahlian', 'tunjangan'
        ]));

        $lowongan->keahlian()->sync($request->keahlian);
        $lowongan->kompetensi()->sync($request->kompetensi);

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan magang berhasil dibuat.');
    }

    public function edit($id)
    {
        $lowongan = LowonganMagangModel::with(['keahlian', 'kompetensi'])->findOrFail($id);
        $periodes = PeriodeMagangModel::all();
        $skemas = SkemaModel::all();
        $perusahaans = PerusahaanModel::all();
        $keahlians = KeahlianModel::all();
        $kompetensis = KompetensiModel::all();

        return view('roles.admin.management-lowongan-magang.edit', compact('lowongan', 'periodes', 'skemas', 'perusahaans', 'keahlians', 'kompetensis'));
    }

    public function update(Request $request, $id)
    {
        $lowongan = LowonganMagangModel::findOrFail($id);

        $request->validate([
            'perusahaan_id' => 'required|exists:m_perusahaan,perusahaan_id',
            'periode_id' => 'required|exists:m_periode_magang,periode_id',
            'skema_id' => 'required|exists:m_skema,skema_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'minimal_ipk' => 'required|numeric|min:0|max:4',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'bidang_keahlian' => 'required|string|max:255',
            'keahlian' => 'array|required',
            'keahlian.*' => 'exists:m_keahlian,keahlian_id',
            'kompetensi' => 'array|required|size:1', // Exactly one kompetensi
            'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id',
        ]);

        $lowongan->update($request->only([
            'perusahaan_id', 'periode_id', 'skema_id', 'judul', 'deskripsi', 'persyaratan',
            'minimal_ipk', 'tanggal_buka', 'tanggal_tutup', 'bidang_keahlian', 'tunjangan'
        ]));

        $lowongan->keahlian()->sync($request->keahlian);
        $lowongan->kompetensi()->sync($request->kompetensi);

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan magang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lowongan = LowonganMagangModel::findOrFail($id);
        $lowongan->delete();

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan magang berhasil dihapus.');
    }
}