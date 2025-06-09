@extends('layouts.template')

@section('title', 'Log Harian Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Log Harian Magang</h3>
                </div>
                <div class="card-body">
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Main Content --}}
                    @if($hasPengajuanDiterima)
                        <div class="mb-3">
                            <a href="{{ route('mahasiswa.log-harian.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Log Aktivitas
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="logTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Tanggal</th>
                                        <th width="55%">Aktivitas</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan dimuat via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <h5 class="alert-heading">Pengajuan Magang Belum Diterima</h5>
                            <p>Anda belum memiliki pengajuan magang yang diterima. Silakan tunggu persetujuan pengajuan magang Anda terlebih dahulu.</p>
                            <hr>
                            <p class="mb-0">
                                <a href="{{ route('mahasiswa.pengajuan-magang.index') }}" class="btn btn-outline-primary">
                                    Lihat Status Pengajuan
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .table th {
        vertical-align: middle;
        text-align: center;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .aktivitas-text {
        max-width: 400px;
        word-wrap: break-word;
        white-space: pre-wrap;
    }
    
    .btn-action {
        margin: 2px;
    }
    
    .alert-dismissible .btn-close {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        padding: 1.25rem 1rem;
    }
</style>
@endpush

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    console.log('Document ready');
    console.log('Has pengajuan diterima: {{ $hasPengajuanDiterima ? "true" : "false" }}');
    
    @if($hasPengajuanDiterima)
        console.log('Initializing DataTable...');
        
        // Initialize DataTable
        var table = $('#logTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('mahasiswa.log-harian.list') }}",
                type: "GET",
                error: function(xhr, error, code) {
                    console.error('AJAX Error:', xhr.responseText);
                    alert('Error loading data: ' + xhr.responseText);
                }
            },
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center'
                },
                { 
                    data: 'tanggal', 
                    name: 'tanggal',
                    className: 'text-center'
                },
                { 
                    data: 'aktivitas', 
                    name: 'aktivitas',
                    className: 'aktivitas-text'
                },
                { 
                    data: 'aksi', 
                    name: 'aksi', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [[1, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                processing: "Memuat data...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data log aktivitas",
                zeroRecords: "Tidak ditemukan data yang sesuai"
            }
        });

        console.log('DataTable initialized');

        // Event handler untuk tombol hapus
        $('#logTable').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            
            console.log('Delete button clicked');

            if (!confirm('Apakah Anda yakin ingin menghapus log aktivitas ini?')) {
                return;
            }

            var url = $(this).data('url');
            var button = $(this);
            
            console.log('Delete URL:', url);

            // Disable button
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: url,
                type: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Delete success:', response);
                    
                    if (response.success) {
                        // Show success message
                        showAlert('success', response.message || 'Log aktivitas berhasil dihapus');
                        
                        // Reload table
                        table.ajax.reload(null, false);
                    } else {
                        showAlert('danger', response.message || 'Gagal menghapus log aktivitas');
                    }
                },
                error: function(xhr) {
                    console.error('Delete error:', xhr.responseText);
                    
                    let message = 'Gagal menghapus log aktivitas';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    
                    showAlert('danger', message);
                },
                complete: function() {
                    // Re-enable button
                    button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        });

        // Function to show alert messages
        function showAlert(type, message) {
            var alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('.card-body').prepend(alertHtml);
            
            // Auto hide after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }

    @else
        console.log('No pengajuan diterima, skipping DataTable initialization');
    @endif
});
</script>
@endpush