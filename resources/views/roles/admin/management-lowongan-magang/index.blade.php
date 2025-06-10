@extends('layouts.template')

@section('title', 'Manajemen Lowongan Magang')

@section('content')
<div class="card card-outline card-primary mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Manajemen Lowongan Magang</h3>
        <div>
            <a href="{{ route('admin.lowongan.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Tambah Lowongan
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped" id="table_lowongan">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Perusahaan</th>
                    <th>Periode</th>
                    <th>Skema</th>
                    <th>Kuota</th>
                    <th>Tunjangan</th>
                    <th>Silabus</th>
                    <th>Tanggal Buka</th>
                    <th>Tanggal Tutup</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lowongans as $lowongan)
                    <tr>
                        <td>{{ $lowongan->judul }}</td>
                        <td>{{ $lowongan->perusahaan->nama }}</td>
                        <td>{{ $lowongan->periode->nama }}</td>
                        <td>{{ $lowongan->skema->nama }}</td>
                        <td>{{ $lowongan->kuota }}</td>
                        <td>{{ $lowongan->tunjangan ? 'Berbayar' : 'Tidak Berbayar' }}</td>
                        <td>
                            @if($lowongan->silabus_path)
                                <a href="{{ Storage::url($lowongan->silabus_path) }}" target="_blank">Lihat Silabus</a>
                            @else
                                Belum Tersedia
                            @endif
                        </td>
                        <td>{{ $lowongan->tanggal_buka->format('d M Y') }}</td>
                        <td>{{ $lowongan->tanggal_tutup->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.lowongan.show', $lowongan->lowongan_id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin.lowongan.edit', $lowongan->lowongan_id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.lowongan.destroy', $lowongan->lowongan_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')"
                                >
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada lowongan magang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>

@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#table_lowongan').DataTable({
                "ordering": true,
                "paging": true,
                "searching": true,
                "info": true
            });
        });

        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                    keyboard: false,
                    backdrop: 'static'
                });
                myModal.show();
            });
        }
    </script>
@endpush