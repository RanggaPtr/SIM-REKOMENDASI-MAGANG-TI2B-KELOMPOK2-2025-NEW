@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="status" name="status">
                            <option value="">- Semua Status -</option>
                            <option value="pending">Diajukan</option>
                            <option value="approved">Diterima</option>
                            <option value="rejected">Ditolak</option>
                            <option value="completed">Selesai</option>
                        </select>
                        <small class="form-text text-muted">Status Pengajuan</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm" id="table_pengajuan">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Mahasiswa</th>
                        <th>Perusahaan</th>
                        <th>Posisi</th>
                        <th>Dosen Pembimbing</th>
                        <th>Periode</th>
                        <th width="10%">Status</th>
                        <th width="10%">Rating</th>
                        <th width="12%">Tanggal Daftar</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    .table th {
        text-align: center;
        vertical-align: middle;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endpush

@push('js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
// Setup CSRF token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function modalAction(url = '') {
    $('#myModal').load(url, function() {
        $('#myModal').modal('show');
    });
}

var dataTable;

// Tambahkan di bagian JavaScript view Anda
$(document).ready(function() {
    // Inisialisasi DataTable dengan error handling yang lebih baik
    dataTable = $('#table_pengajuan').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('admin/management-pengajuan-magang/list') }}",
            type: "POST",
            data: function(d) {
                d.status = $('#status').val();
                console.log('Sending data:', d);
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', xhr);
                console.error('Response Text:', xhr.responseText);
                console.error('Status:', xhr.status);
                console.error('Error:', error);
                console.error('Thrown:', thrown);
                
                // Tampilkan pesan error yang lebih detail
                let errorMessage = 'Error memuat data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += ': ' + xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage += ': ' + xhr.responseJSON.error;
                } else {
                    errorMessage += ': ' + (xhr.status + ' ' + xhr.statusText);
                }
                
                alert(errorMessage);
                
                // Tampilkan error di tabel
                $('#table_pengajuan tbody').html(
                    '<tr><td colspan="10" class="text-center text-danger">Error: ' + errorMessage + '</td></tr>'
                );
            },
            success: function(data) {
                console.log('DataTable Success:', data);
                if (data.error) {
                    console.error('Server Error:', data.error);
                    alert('Server Error: ' + data.error);
                }
            }
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "text-center"
            },
            {
                data: "mahasiswa_nama",
                name: "mahasiswa_nama",
                defaultContent: "Data tidak tersedia"
            },
            {
                data: "perusahaan_nama", 
                name: "perusahaan_nama",
                defaultContent: "Data tidak tersedia"
            },
            {
                data: "lowongan_posisi",
                name: "lowongan_posisi",
                defaultContent: "Data tidak tersedia"
            },
            {
                data: "dosen_nama",
                name: "dosen_nama",
                defaultContent: "Data tidak tersedia"
            },
            {
                data: "periode_nama",
                name: "periode_nama",
                defaultContent: "Data tidak tersedia"
            },
            {
                data: "status_badge",
                name: "status",
                orderable: true,
                searchable: false,
                className: "text-center",
                defaultContent: "-"
            },
            {
                data: "feedback_rating",
                name: "feedback_rating",
                orderable: true,
                searchable: false,
                className: "text-center",
                defaultContent: "-"
            },
            {
                data: "created_at",
                name: "created_at",
                defaultContent: "-"
            },
            {
                data: "aksi",
                name: "aksi",
                orderable: false,
                searchable: false,
                className: "text-center",
                defaultContent: "-"
            }
        ],
        order: [[8, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: "Memuat data...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            loadingRecords: "Memuat...",
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Tidak ada data pengajuan magang",
            paginate: {
                first: "Pertama",
                last: "Terakhir", 
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Event handler untuk filter status
    $('#status').on('change', function() {
        console.log('Status filter changed to:', $(this).val());
        dataTable.ajax.reload();
    });
});