@extends('layouts.template')

@section('content')
<h3>Daftar Mahasiswa Bimbingan</h3>

<table id="mahasiswaTable" class="table table-bordered table-striped">
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
