@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/admin/management-pengguna/import') }}')" class="btn btn-info">Import User</button>
            <a href="{{ url('/admin/management-pengguna/export_excel') }}" class="btn btn-warning"><i class="fa fa-file-excel"></i> Export User</a>
            <a href="{{ url('/admin/management-pengguna/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export User</a>
            <button onclick="modalAction('{{ url('/admin/management-pengguna/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>


        </div>
    </div>
    <div class="card-body">
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        {{-- Filtering --}}
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="role" name="role" required>
                            <option value="">- Semua -</option>
                            <option value="admin">Admin</option>
                            <option value="dosen">Dosen</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                        <small class="form-text text-muted">Role Pengguna</small>
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm table-responsive" id="table_user">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
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
            $('#myModal').load(url, function() {
                var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                    keyboard: false,
                    backdrop: 'static'
                });
                myModal.show();
            });
        }

      $(document).ready(function() {
    var dataUser = $('#table_user').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('admin/management-pengguna/list') }}",
            type: 'POST',
            data: function(d) {
                d.role = $('#role').val();
                d._token = '{{ csrf_token() }}';
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', xhr.responseText);
            }
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "username", className: "", orderable: true, searchable: true },
            { data: "nama", className: "", orderable: true, searchable: true },
            { data: "email", className: "", orderable: true, searchable: true },
            { data: "role", className: "", orderable: true, searchable: true },
            { data: "no_telepon", className: "", orderable: false, searchable: false },
            { data: "aksi", className: "", orderable: false, searchable: false }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari..."
        }
    });

    $('#role').on('change', function() {
        dataUser.ajax.reload();
    });

    $('#table_user_filter input').unbind().bind('keyup', function(e) {
        if (e.keyCode == 13) {
            dataUser.search(this.value).draw();
        }
    });
});
            
    </script>
@endpush
