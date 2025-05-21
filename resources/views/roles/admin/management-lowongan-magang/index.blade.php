@extends('layouts.template')

@section('title', 'Manajemen Lowongan Magang')

@section('content')
<div class="container mt-4">
    <h2>Manajemen Lowongan Magang</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.lowongan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Lowongan
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Perusahaan</th>
                <th>Periode</th>
                <th>Skema</th>
                <th>Tanggal Buka</th>
                <th>Tanggal Tutup</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lowongans as $lowongan)
                <tr>
                    <td>{{ $lowongan->judul }}</td>
                    <td>{{ $lowongan->perusahaan->nama }}</td>
                    <td>{{ $lowongan->periode->nama }}</td>
                    <td>{{ $lowongan->skema->nama }}</td>
                    <td>{{ $lowongan->tanggal_buka->format('d M Y') }}</td>
                    <td>{{ $lowongan->tanggal_tutup->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.lowongan.edit', $lowongan->lowongan_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.lowongan.destroy', $lowongan->lowongan_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada lowongan magang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection