@extends('layouts.template')

@section('content')
<div class="container">
    <h2 class="mb-4">Log Harian Mahasiswa</h2>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol tambah log --}}
    <a href="{{ route('mahasiswa.log-harian.create') }}" class="btn btn-primary mb-3">+ Tambah Log</a>

    {{-- Tabel log --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $log->kegiatan }}</td>
                    <td>{{ $log->keterangan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('mahasiswa.log-harian.edit', $log->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('mahasiswa.log-harian.destroy', $log->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus log ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada log harian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>
@endsection
