@extends('layouts.template')

@section('content')


<div class="mb-3">
    <a href="{{ route('dosen.sertifikat.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Sertifikat
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<table id="sertifikatTable" class="table table-bordered table-striped">
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
        @foreach($sertifikats as $sertifikat)
        <tr>
            <td>{{ $sertifikat->nama_sertifikat }}</td>
            <td>{{ $sertifikat->penerbit }}</td>
            <td>{{ \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d M Y') }}</td>
            <td>
                @if($sertifikat->file_sertifikat)
                    <a href="{{ asset('storage/' . $sertifikat->file_sertifikat) }}" 
                       target="_blank" 
                       class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>
                <a href="{{ route('dosen.sertifikat.edit', $sertifikat->sertifikat_dosen_id) }}" 
                   class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('dosen.sertifikat.destroy', $sertifikat->sertifikat_dosen_id) }}" 
                      method="POST" 
                      style="display:inline-block"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus sertifikat ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if($sertifikats->count() == 0)
    <div class="text-center py-4">
        <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Belum ada sertifikat</h5>
        <p class="text-muted">Silakan tambahkan sertifikat baru dengan mengklik tombol "Tambah Sertifikat"</p>
    </div>
@endif
@endsection

@push('css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sertifikatTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
                "order": [[2, 'desc']] // Default sort by Tanggal Terbit (newest first)
            });
        });
    </script>
@endpush