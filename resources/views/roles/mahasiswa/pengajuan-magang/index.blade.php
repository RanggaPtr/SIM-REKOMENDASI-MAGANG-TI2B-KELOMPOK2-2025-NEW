@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $page->title }}</h3>
            @if($mahasiswa)
                <button onclick="modalAction('{{ url('/mahasiswa/pengajuan-magang/create_ajax') }}')" class="btn btn-success">
                    <i class="fa fa-plus"></i>
                    Ajukan Magang
                </button>
            @else
                <div class="alert alert-warning">
                    Lengkapi profil mahasiswa terlebih dahulu sebelum mengajukan magang
                </div>
            @endif
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <table class="table table-bordered table-striped table-hover table-sm" id="table_pengajuan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lowongan</th>
                        <th>Perusahaan</th>
                        <th>Dosen Pembimbing</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
    </div>
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
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                    keyboard: false,
                    backdrop: 'static'
                });
                myModal.show();
            });
        }

        $(document).ready(function() {
            var dataPengajuan = $('#table_pengajuan').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('mahasiswa/pengajuan-magang/list') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [
                    { data: 'pengajuan_id', className: 'text-center' },
                    { 
                        data: 'lowongan.judul',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'lowongan.perusahaan.nama',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'dosen.user.nama',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    { 
                        data: 'periode.nama',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    { data: 'status', className: 'text-center' },
                    { data: 'aksi', className: 'text-center', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush