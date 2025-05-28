@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div>
                <button onclick="modalAction('{{ url('/admin/management-mitra/import') }}')" class="btn btn-success btn-sm">
                    <i class="fa fa-file-import"></i> Import Perusahaan Mitra
                </button>
                <a href="{{ url('/admin/management-mitra/export_excel') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ url('/admin/management-mitra/export_pdf') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-pdf"></i> Export PDF
                </a>
                <button onclick="modalAction('{{ url('/admin/management-mitra/create_ajax') }}')"
                    class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Tambah Perusahaan Mitra
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="table_perusahaan">
                <thead>
                    <tr>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Ringkasan</th>
                        <th>Deskripsi</th>
                        <th>Bidang Industri</th>
                        <th>Alamat</th>
                        <th>Wilayah</th>
                        <th>Kontak</th>
                        <th>Rating</th>
                        <th>Deskripsi Rating</th>
                        <th>Aksi</th>
                    </tr>
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
            $('#table_perusahaan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/admin/management-mitra/list') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "nama" },
                    { data: "ringkasan" },
                    { data: "deskripsi" },
                    { data: "bidang_industri" },
                    { data: "alamat" },
                    { data: "wilayah" },
                    { data: "kontak" },
                    { data: "rating" },
                    { data: "deskripsi_rating" },
                    { data: "aksi", orderable: false, searchable: false, className: "text-center" }
                ]
            });
        });
    </script>
@endpush