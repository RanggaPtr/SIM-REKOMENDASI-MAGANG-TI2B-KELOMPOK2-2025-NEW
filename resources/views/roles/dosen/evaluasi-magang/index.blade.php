@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Evaluasi Magang') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('dosen.evaluasi-magang.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Evaluasi Baru
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pengajuan ID</th>
                                    <th>Nilai</th>
                                    <th>Komentar</th>
                                    <th>Dibuat Pada</th>
                                    <th>Diperbarui</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($evaluasiMagangList as $index => $evaluasi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $evaluasi->pengajuan_id }}</td>
                                        <td>{{ $evaluasi->nilai }}</td>
                                        <td>{{ $evaluasi->komentar }}</td>
                                        <td>{{ $evaluasi->created_at ?? '-' }}</td>
                                        <td>{{ $evaluasi->updated_at ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('dosen.evaluasi-magang.show', $evaluasi->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('dosen.evaluasi-magang.edit', $evaluasi->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('dosen.evaluasi-magang.destroy', $evaluasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data evaluasi magang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection