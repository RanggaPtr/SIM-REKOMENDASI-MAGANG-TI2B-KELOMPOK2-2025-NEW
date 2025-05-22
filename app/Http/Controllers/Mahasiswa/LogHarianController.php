<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogHarian;

class LogHarianController extends Controller
{
  public function index()
{
    $logs = LogHarian::latest()->paginate(10);
return view('roles.mahasiswa.log-harian.index', compact('logs'));
}    public function create()
    {
        return view('roles.mahasiswa.log-harian.create'); // Buat view ini nanti
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        LogHarian::create($request->all());

        return redirect()->route('mahasiswa.log-harian.index')->with('success', 'Log harian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $log = LogHarian::findOrFail($id);
        return view('mahasiswa.log-harian-edit', compact('log')); // Buat view ini juga
    }

    public function update(Request $request, $id)
    {
        $log = LogHarian::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $log->update($request->all());

        return redirect()->route('mahasiswa.log-harian.index')->with('success', 'Log harian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $log = LogHarian::findOrFail($id);
        $log->delete();

        return redirect()->route('mahasiswa.log-harian.index')->with('success', 'Log harian berhasil dihapus.');
    }
}
