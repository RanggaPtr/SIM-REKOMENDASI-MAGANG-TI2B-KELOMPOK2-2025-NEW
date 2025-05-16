@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('/admin/management-periode-magang/export_excel') }}" class="btn btn-warning">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/admin/management-periode-magang/export_pdf') }}" class="btn btn-warning">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>
            <button onclick="modalAction('{{ url('/admin/management-periode-magang/create_ajax') }}')" 
                class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Periode
            </button>
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
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Cari nama periode">
                        <small class="form-text text-muted">Nama Periode</small>
                    </div>
                    <div class="col-3">
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
                        <small class="form-text text-muted">Tanggal Mulai</small>
                    </div>
                    <div class="col-3">
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                        <small class="form-text text-muted">Tanggal Selesai</small>
                    </div>
                </div>
            </div>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <table class="table table-bordered table-striped table-hover table-sm table-responsive" id="table_periode">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Periode</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <!-- Modal -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
        data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>
</div>
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            var dataPeriode = $('#table_periode').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('admin/management-periode-magang/list') }}",
                    type: 'POST',
                    data: function(d) {
                        d.nama = $('#nama').val();
                        d.tanggal_mulai = $('#tanggal_mulai').val();
                        d.tanggal_selesai = $('#tanggal_selesai').val();
                        d._token = '{{ csrf_token() }}';
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables error:', xhr.responseText);
                    }
                },
                columns: [
                    { 
                        data: "DT_RowIndex", 
                        className: "text-center", 
                        orderable: false, 
                        searchable: false 
                    },
                    { 
                        data: "nama", 
                        className: "", 
                        orderable: true, 
                        searchable: true 
                    },
                    { 
                        data: "tanggal_mulai", 
                        className: "", 
                        orderable: true, 
                        searchable: false,
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                        }
                    },
                    { 
                        data: "tanggal_selesai", 
                        className: "", 
                        orderable: true, 
                        searchable: false,
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                        }
                    },
                    { 
                        data: "status", 
                        className: "text-center", 
                        orderable: false, 
                        searchable: false,
                        render: function(data, type, row) {
                            const today = new Date();
                            const start = new Date(row.tanggal_mulai);
                            const end = new Date(row.tanggal_selesai);
                            
                            if (today < start) {
                                return '<span class="badge bg-info">Akan Datang</span>';
                            } else if (today >= start && today <= end) {
                                return '<span class="badge bg-success">Berlangsung</span>';
                            } else {
                                return '<span class="badge bg-secondary">Selesai</span>';
                            }
                        }
                    },
                    { 
                        data: "aksi", 
                        className: "text-center", 
                        orderable: false, 
                        searchable: false 
                    }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari..."
                }
            });

            // Filter event listeners
            $('#nama, #tanggal_mulai, #tanggal_selesai').on('change', function() {
                dataPeriode.ajax.reload();
            });

            $('#table_periode_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    dataPeriode.search(this.value).draw();
                }
            });

            // SweetAlert for delete confirmation
            $(document).on('submit', '#deleteForm', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Terhapus!',
                                        response.message,
                                        'success'
                                    );
                                    dataPeriode.ajax.reload();
                                    $('#myModal').modal('hide');
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush