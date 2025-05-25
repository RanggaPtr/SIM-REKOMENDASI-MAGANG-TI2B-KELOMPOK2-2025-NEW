@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div>
                <button onclick="modalAction('{{ url('/mahasiswa/pengajuan-magang/create_ajax') }}')" class="btn btn-success">
                    <i class="fa fa-plus"></i>
                    Ajukan Magang
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Filter Status:</label>
                    <select class="form-control" id="status-filter">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </div>
            <table class="table table-bordered table-striped" id="table_pengajuan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Lowongan</th>
                        <th>Dosen Pembimbing</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
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

        $(document).ready(function () {
            window.tablePengajuan = $('#table_pengajuan').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('mahasiswa/pengajuan-magang/list') }}",
                    type: 'POST',
                    data: function(d) {
                        d.status = $('#status-filter').val();
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "lowongan", className: "" },
                    { data: "dosen", className: "" },
                    { data: "periode", className: "" },
                    { data: "status", className: "text-center" },
                    { 
                        data: "created_at", 
                        className: "",
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                        }
                    },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ]
            });

            $('#status-filter').change(function() {
                tablePengajuan.ajax.reload();
            });
        });
    </script>
@endpush