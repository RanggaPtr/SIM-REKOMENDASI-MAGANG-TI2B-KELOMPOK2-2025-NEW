@extends('layouts.template')

@section('content')
<h3>Daftar Mahasiswa Bimbingan</h3>

<table class="table">
    <thead>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Status Magang</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($pengajuan as $p)
        <tr>
            <td>{{ $p->mahasiswa->user->nama }}</td>
            <td>{{ $p->mahasiswa->nim }}</td>
            <td>{{ $p->status }}</td>
            <td><a href="{{ route('dosen.monitoring.show', $p->pengajuan_id) }}" class="btn btn-primary btn-sm">Detail</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
