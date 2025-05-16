@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/admin/management-pengguna/import') }}')" class="btn btn-info">Import Program Studi</button>
                <a href="{{ url('/admin/management-prodi/export_excel') }}" class="btn btn-warning">
                    <i class="fa fa-file-excel"></i> Export Program Studi
                </a>
                <a href="{{ url('/admin/management-prodi/export_pdf') }}" class="btn btn-danger">
                    <i class="fa fa-file-pdf"></i> Export Program Studi
                </a>
                <button onclick="modalAction('{{ url('/admin/management-prodi/create_ajax') }}')" class="btn btn-success">
                    Tambah Program Studi
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm table-responsive" id="table_programstudi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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
            var dataProgramStudi = $('#table_programstudi').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('admin/management-prodi/list') }}",
                    type: 'POST', // ubah dari GET ke POST
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    error: function (xhr, error, thrown) {
                        console.error('DataTables error:', xhr.responseText);
                    }
                },
                columns: [
                    { data: "prodi_id", className: "text-center" },
                    { data: "nama", className: "", orderable: true, searchable: true },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari program studi..."
                }
            });

            $('#table_programstudi_filter input').unbind().bind('keyup', function (e) {
                if (e.keyCode == 13) {
                    dataProgramStudi.search(this.value).draw();
                }
            });
        });
    </script>
@endpush