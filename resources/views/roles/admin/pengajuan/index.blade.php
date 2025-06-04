@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Manajemen Pengajuan Magang</h3>
            <!-- <div>
                <button onclick="modalAction('{{ url('/admin/management-pengajuan-magang/create_ajax') }}')" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Tambah Pengajuan
                </button>
                <button onclick="modalAction('{{ url('/admin/management-pengajuan-magang/import') }}')" class="btn btn-success btn-sm">
                    <i class="fa fa-file-import"></i> Import Data
                </button>
                <a href="{{ url('/admin/management-pengajuan-magang/export_excel') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ url('/admin/management-pengajuan-magang/export_pdf') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-pdf"></i> Export PDF
                </a>
            </div> -->
        </div>
        <div class="card-body">
            <table id="pengajuanTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Mahasiswa</th>
                        <th>Perusahaan</th>
                        <th>Judul Lowongan</th>
                        <th>Dosen Pembimbing</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
    </div>
@endsection

@push('css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
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
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            window.pengajuanTable = $('#pengajuanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.pengajuan.list") }}',
                    type: 'POST'
                },
                columns: [
                    { data: 'pengajuan_id', name: 'pengajuan_id' },
                    { data: 'mahasiswa_name', name: 'mahasiswa_name' },
                    { data: 'perusahaan_name', name: 'perusahaan_name' },
                    { data: 'lowongan_judul', name: 'lowongan_judul' },
                    { data: 'dosen_name', name: 'dosen_name' },
                    { data: 'periode_name', name: 'periode_name' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ]
            });
        });
    </script>
@endpush