@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div>
                <button onclick="modalAction('{{ url('/admin/management-prodi/import_ajax') }}')"
                    class="btn btn-success btn-sm">
                    <i class="fa fa-file-import"></i>
                    Import Program Studi
                </button>
                <a href="{{ url('/admin/management-prodi/export_excel') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/admin/management-prodi/export_pdf') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/admin/management-prodi/create_ajax') }}')" class="btn btn-success">
                    <i class="fa fa-plus"></i>
                    Tambah Program Studi
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="table_programstudi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Prodi</th>
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
            window.tableProdi = $('#table_programstudi').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('admin/management-prodi/list') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                },
                columns: [
                    { data: "prodi_id", className: "text-center" },
                    { data: "nama", className: "" },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush