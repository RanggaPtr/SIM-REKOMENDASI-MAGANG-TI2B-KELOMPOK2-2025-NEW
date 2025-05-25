<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitasModel;
use Illuminate\Support\Facades\Auth;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitasModel::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('roles.mahasiswa.log-harian.index', compact('logs'));
    }

    public function edit($id)
    {
        $logHarian = LogAktivitasModel::where('user_id', Auth::id())->findOrFail($id);

        return view('roles.mahasiswa.log-harian.edit', compact('logHarian'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'aktivitas' => 'required|string|max:1000',
        ]);

        $log = LogAktivitasModel::where('user_id', Auth::id())->findOrFail($id);
        $log->update([
            'aktivitas' => $request->aktivitas,
        ]);

        return redirect()->route('mahasiswa.log-harian.index')
            ->with('success', 'Log aktivitas berhasil diperbarui.');
    }

    public function create()
{
    return view('roles.mahasiswa.log-harian.create');
}

public function store(Request $request)
{
    $request->validate([
        'aktivitas' => 'required|string|max:1000',
    ]);

    \App\Models\LogAktivitasModel::create([
        'user_id' => auth()->id(),
        'aktivitas' => $request->aktivitas,
    ]);

    return redirect()->route('mahasiswa.log-harian.index')
        ->with('success', 'Log aktivitas berhasil ditambahkan.');
}

}
