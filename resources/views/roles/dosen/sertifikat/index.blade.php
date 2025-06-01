@extends('layouts.template')

@section('content')
<h3>Daftar Sertifikat Saya</h3>

<a href="{{ route('dosen.sertifikat.create') }}" class="btn btn-primary mb-3">Tambah Sertifikat</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Sertifikat</th>
            <th>Penerbit</th>
            <th>Tanggal Terbit</th>
            <th>File</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sertifikats as $sertifikat)
        <tr>
            <td>{{ $sertifikat->nama_sertifikat }}</td>
            <td>{{ $sertifikat->penerbit }}</td>
            <td>{{ $sertifikat->tanggal_terbit->format('d M Y') }}</td>
            <td><a href="{{ asset('storage/' . $sertifikat->file_sertifikat) }}" target="_blank">Lihat File</a></td>
            <td>
                <a href="{{ route('dosen.sertifikat.edit', $sertifikat->sertifikat_dosen_id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('dosen.sertifikat.destroy', $sertifikat->sertifikat_dosen_id) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">Belum ada sertifikat.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
