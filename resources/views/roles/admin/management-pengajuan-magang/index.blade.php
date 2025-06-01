@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/admin/management-pengajuan-magang/create_ajax') }}')" class="btn btn-success">Tambah</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="status" name="status">
                            <option value="">- Semua Status -</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                        <small class="form-text text-muted">Status Pengajuan</small>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped table-hover table-sm" id="table_pengajuan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mahasiswa</th>
                    <th>Perusahaan</th>
                    <th>Posisi</th>
                    <th>Dosen Pembimbing</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Rating</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
function modalAction(url = '') {
    $('#myModal').load(url, function() {
        $('#myModal').modal('show');
    });
}

var dataTable;

$(document).ready(function() {
    dataTable = $('#table_pengajuan').DataTable({
        serverSide: true,
        ajax: {
            "url": "{{ url('admin/management-pengajuan-magang/list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function(d) {
                d.status = $('#status').val();
            }
        },
        columns: [{
                data: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false
            },
            {
                data: "mahasiswa_nama",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "perusahaan_nama",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "lowongan_posisi",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "dosen_nama",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "periode_nama",
                className: "",
                orderable: true,
                searchable: true
            },
            {
                data: "status_badge",
                className: "text-center",
                orderable: true,
                searchable: false
            },
            {
                data: "feedback_rating",
                className: "text-center",
                orderable: true,
                searchable: false
            },
            {
                data: "created_at",
                className: "",
                orderable: true,
                searchable: false
            },
            {
                data: "aksi",
                className: "",
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#status').on('change', function() {
        dataTable.ajax.reload();
    });
});
</script>
@endpush