@extends('layouts.template')

@section('content')
<h3>Daftar Mahasiswa Bimbingan</h3>

<form method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="periode_id" class="form-select">
                <option value="">Semua Periode</option>
                @foreach ($periodeMagang as $periode)
                    <option value="{{ $periode->periode_id }}" {{ request('periode_id') == $periode->periode_id ? 'selected' : '' }}>{{ $periode->nama_periode }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="order" class="form-select">
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Z-A</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>

<table id="mahasiswaTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Status Magang</th>
            <th>Periode</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($pengajuan as $p)
        <tr>
            <td>{{ $p->mahasiswa->user->name }}</td>
            <td>{{ $p->mahasiswa->nim }}</td>
            <td>{{ $p->status }}</td>
            <td>{{ $p->periode->nama_periode ?? '-' }}</td>
            <td>
                <a href="{{ route('dosen.monitoring.show', $p->pengajuan_id) }}" class="btn btn-primary btn-sm">Detail</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="mt-3">
    {{ $pengajuan->links() }}
</div>
@endsection

@push('css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#mahasiswaTable').DataTable({
                // opsi tambahan bisa ditambahkan di sini
            });
        });
    </script>
@endpush