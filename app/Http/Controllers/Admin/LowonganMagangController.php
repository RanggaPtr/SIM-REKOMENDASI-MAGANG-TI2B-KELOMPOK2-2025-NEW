<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use App\Models\PerusahaanModel;
use App\Models\SkemaModel;
use Illuminate\Http\Request;

class LowonganMagangController extends Controller
{
    public function index()
    {
        // Admin dapat melihat semua lowongan, bukan hanya milik perusahaan tertentu
        $lowongans = LowonganMagangModel::with(['perusahaan', 'periode', 'skema'])->get();
        return view('roles.admin.management-lowongan-magang.index', compact('lowongans'));
    }

    public function create()
    {
        $periodes = PeriodeMagangModel::all();
        $skemas = SkemaModel::all();
        $perusahaans = PerusahaanModel::all(); // Admin perlu memilih perusahaan
        return view('roles.admin.management-lowongan-magang.create', compact('periodes', 'skemas', 'perusahaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perusahaan_id' => 'required|exists:m_perusahaan,perusahaan_id', // Admin harus memilih perusahaan
            'periode_id' => 'required|exists:m_periode_magang,periode_id',
            'skema_id' => 'required|exists:m_skema,skema_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'bidang_keahlian' => 'required|string|max:255',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
        ]);

        LowonganMagangModel::create([
            'perusahaan_id' => $request->perusahaan_id,
            'periode_id' => $request->periode_id,
            'skema_id' => $request->skema_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'persyaratan' => $request->persyaratan,
            'bidang_keahlian' => $request->bidang_keahlian,
            'tanggal_buka' => $request->tanggal_buka,
            'tanggal_tutup' => $request->tanggal_tutup,
        ]);

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan magang berhasil dibuat.');
    }

    public function edit($id)
    {
        $lowongan = LowonganMagangModel::where('lowongan_id', $id)->firstOrFail();
        $periodes = PeriodeMagangModel::all();
        $skemas = SkemaModel::all();
        $perusahaans = PerusahaanModel::all(); // Admin perlu memilih perusahaan
        return view('roles.admin.management-lowongan-magang.edit', compact('lowongan', 'periodes', 'skemas', 'perusahaans'));
    }

    public function update(Request $request, $id)
    {
        $lowongan = LowonganMagangModel::where('lowongan_id', $id)->firstOrFail();

        $request->validate([
            'perusahaan_id' => 'required|exists:m_perusahaan,perusahaan_id',
            'periode_id' => 'required|exists:m_periode_magang,periode_id',
            'skema_id' => 'required|exists:m_skema,skema_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'bidang_keahlian' => 'required|string|max:255',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
        ]);

        $lowongan->update([
            'perusahaan_id' => $request->perusahaan_id,
            'periode_id' => $request->periode_id,
            'skema_id' => $request->skema_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'persyaratan' => $request->persyaratan,
            'bidang_keahlian' => $request->bidang_keahlian,
            'tanggal_buka' => $request->tanggal_buka,
            'tanggal_tutup' => $request->tanggal_tutup,
        ]);

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan magang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lowongan = LowonganMagangModel::where('lowongan_id', $id)->firstOrFail();
        $lowongan->delete();

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan magang berhasil dihapus.');
    }
}