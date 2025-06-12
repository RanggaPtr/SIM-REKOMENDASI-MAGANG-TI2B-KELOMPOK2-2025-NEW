@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Manajemen Pengajuan Magang</h3>
            <div>
                <a href="{{ url('/admin/management-pengajuan-magang/export_excel') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ url('/admin/management-pengajuan-magang/export_pdf') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Status -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Filter Status:</label>
                    <select id="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="ajukan">Ajukan</option>
                        <option value="terima">Terima</option>
                        <option value="tolak">Tolak</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>

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
    <style>
        /* Sembunyikan default length menu */
        .dataTables_length {
            display: none !important;
        }
        
        /* Style untuk filter status */
        #statusFilter {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
    </style>
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
                    type: 'POST',
                    data: function(d) {
                        d.status_filter = $('#statusFilter').val();
                    }
                },
                columns: [
                    { data: 'pengajuan_id', name: 'pengajuan_id' },
                    { data: 'mahasiswa_name', name: 'mahasiswa_name' },
                    { data: 'perusahaan_name', name: 'perusahaan_name' },
                    { data: 'lowongan_judul', name: 'lowongan_judul' },
                    { data: 'dosen_name', name: 'dosen_name' },
                    { data: 'periode_name', name: 'periode_name' },
                    { data: 'status', name: 'status' },
                    {
                         data: 'action',
                         name: 'action',
                         orderable: false,
                         searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                dom: 'Bfrtip', // Menghilangkan 'l' untuk length menu
                language: {
                    processing: "Memproses...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan data yang sesuai",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Event listener untuk filter status
            $('#statusFilter').on('change', function() {
                pengajuanTable.ajax.reload();
            });
        });
    </script>
@endpush