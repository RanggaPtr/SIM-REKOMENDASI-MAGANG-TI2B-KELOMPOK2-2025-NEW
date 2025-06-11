<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KeahlianModel;
use App\Models\KompetensiModel;
use App\Models\LowonganKeahlianModel;
use App\Models\LowonganKompetensiModel;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use App\Models\PerusahaanModel;
use App\Models\SkemaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LowonganMagangController extends Controller
{
    public function index()
    {
        $lowongans = LowonganMagangModel::with(['perusahaan', 'periode', 'skema'])->get();
        $activeMenu = 'Lowongan Magang';
        return view('roles.admin.management-lowongan-magang.index', compact('lowongans', 'activeMenu'));
    }

    public function show($id)
    {
        $lowongan = LowonganMagangModel::with([
            'perusahaan',
            'periode', 
            'skema',
            'lowonganKeahlian.keahlian',
            'lowonganKompetensi.kompetensi'
        ])->findOrFail($id);

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
            'tunjangan' => 'required|boolean',
            'kuota' => 'required|integer|min:1',
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'exists:m_keahlian,keahlian_id',
            'kompetensi' => 'required|array|size:1',
            'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'silabus_path' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $silabusPath = null;
            if ($request->hasFile('silabus_path')) {
                $silabusPath = $request->file('silabus_path')->store('silabus', 'public');
            }

            $lowongan = LowonganMagangModel::create([
                'perusahaan_id' => $request->perusahaan_id,
                'periode_id' => $request->periode_id,
                'skema_id' => $request->skema_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'persyaratan' => $request->persyaratan,
                'tunjangan' => $request->tunjangan,
                'kuota' => $request->kuota,
                'tanggal_buka' => $request->tanggal_buka,
                'tanggal_tutup' => $request->tanggal_tutup,
                'silabus_path' => $silabusPath,
            ]);

            foreach ($request->keahlian as $keahlianId) {
                LowonganKeahlianModel::create([
                    'lowongan_id' => $lowongan->lowongan_id,
                    'keahlian_id' => $keahlianId
                ]);
            }

            LowonganKompetensiModel::create([
                'lowongan_id' => $lowongan->lowongan_id,
                'kompetensi_id' => $request->kompetensi[0]
            ]);

            DB::commit();

            return redirect()->route('admin.lowongan.index')
                ->with('success', 'Lowongan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $lowongan = LowonganMagangModel::with(['lowonganKeahlian', 'lowonganKompetensi'])->findOrFail($id);
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
            'tunjangan' => 'required|boolean',
            'kuota' => 'required|integer|min:1',
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'exists:m_keahlian,keahlian_id',
            'kompetensi' => 'required|array|size:1',
            'kompetensi.*' => 'exists:m_kompetensi,kompetensi_id',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'silabus_path' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'perusahaan_id' => $request->perusahaan_id,
                'periode_id' => $request->periode_id,
                'skema_id' => $request->skema_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'persyaratan' => $request->persyaratan,
                'tunjangan' => $request->tunjangan,
                'kuota' => $request->kuota,
                'tanggal_buka' => $request->tanggal_buka,
                'tanggal_tutup' => $request->tanggal_tutup,
            ];

            if ($request->hasFile('silabus_path')) {
                if ($lowongan->silabus_path) {
                    Storage::disk('public')->delete($lowongan->silabus_path);
                }
                $data['silabus_path'] = $request->file('silabus_path')->store('silabus', 'public');
            }

            $lowongan->update($data);

            LowonganKeahlianModel::where('lowongan_id', $lowongan->lowongan_id)->delete();
            foreach ($request->keahlian as $keahlianId) {
                LowonganKeahlianModel::create([
                    'lowongan_id' => $lowongan->lowongan_id,
                    'keahlian_id' => $keahlianId
                ]);
            }

            LowonganKompetensiModel::where('lowongan_id', $lowongan->lowongan_id)->delete();
            LowonganKompetensiModel::create([
                'lowongan_id' => $lowongan->lowongan_id,
                'kompetensi_id' => $request->kompetensi[0]
            ]);

            DB::commit();

            return redirect()->route('admin.lowongan.index')
                ->with('success', 'Lowongan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $lowongan = LowonganMagangModel::findOrFail($id);
        
        if ($lowongan->silabus_path) {
            Storage::disk('public')->delete($lowongan->silabus_path);
        }
        
        $lowongan->delete();

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan magang berhasil dihapus.');
    }
}