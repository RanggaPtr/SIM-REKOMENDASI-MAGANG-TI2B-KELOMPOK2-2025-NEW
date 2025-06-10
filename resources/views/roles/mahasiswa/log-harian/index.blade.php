@extends('layouts.template')

@section('title', 'Log Harian')

@section('content')
<!-- Pastikan CSRF Token tersedia -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Log Aktivitas Harian</h4>
        @if ($hasPengajuanDiterima)
            <button class="btn btn-primary" id="btnTambahLog">
                <i class="fas fa-plus"></i> Tambah Log
            </button>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Anda belum memiliki pengajuan magang yang diterima. Silakan ajukan magang terlebih dahulu.
            </div>
        @endif
    </div>

    @if (!$hasPengajuanDiterima)
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <h5>Belum Ada Pengajuan Magang yang Diterima</h5>
                <p class="text-muted">Untuk dapat mengisi log aktivitas harian, Anda perlu memiliki pengajuan magang yang sudah diterima.</p>
                <a href="{{ route('mahasiswa.pengajuan-magang.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajukan Magang
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table id="logTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Aktivitas</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal tambah/edit -->
<div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modalContent">
            <!-- Content akan diisi oleh create_ajax -->
        </div>
    </div>
</div>

<!-- Debug Info (bisa dihapus setelah selesai debugging) -->
<div class="mt-3" id="debugInfo" style="display: none;">
    <div class="alert alert-info">
        <h6>Debug Information:</h6>
        <p>Has Pengajuan Diterima: <strong>{{ $hasPengajuanDiterima ? 'Ya' : 'Tidak' }}</strong></p>
        <p>User ID: <strong>{{ Auth::id() }}</strong></p>
        <p>Mahasiswa ID: <strong>{{ Auth::user()->mahasiswa->mahasiswa_id ?? 'Null' }}</strong></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    console.log('Document ready, hasPengajuanDiterima: {{ $hasPengajuanDiterima ? 'true' : 'false' }}');
    
    // Hanya inisialisasi DataTable jika ada pengajuan yang diterima
    @if ($hasPengajuanDiterima)
    let table = $('#logTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('mahasiswa.log-harian.list') }}",
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', xhr.responseText);
                alert('Terjadi kesalahan saat memuat data: ' + (xhr.responseJSON?.message || 'Error tidak diketahui'));
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                width: '5%'
            },
            { 
                data: 'aktivitas', 
                name: 'aktivitas',
                width: '60%'
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                width: '20%'
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                width: '15%'
            },
        ],
        order: [[2, 'desc']], // Order by created_at descending
        pageLength: 10,
        responsive: true,
        language: {
            processing: "Memuat data...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            loadingRecords: "Memuat data...",
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Belum ada log aktivitas",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
    @endif

    // Event handler untuk tombol tambah log - DEBUGGING
    $(document).on('click', '#btnTambahLog', function(e) {
        e.preventDefault();
        console.log('Tombol Tambah Log diklik');
        
        // Show loading state
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        
        $.ajax({
            url: "{{ route('mahasiswa.log-harian.create_ajax') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                console.log('Mengirim request ke create_ajax...');
            },
            success: function(response) {
                console.log('Response dari create_ajax:', response);
                
                if (response.success) {
                    $('#modalContent').html(response.data.form_html);
                    $('#logModal').modal('show');
                    
                    // Setup form submit handler setelah modal terbuka
                    setTimeout(function() {
                        setupFormHandler();
                    }, 500);
                } else {
                    console.error('Response error:', response.message);
                    alert(response.message || 'Gagal memuat form');
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error Details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                let message = 'Gagal memuat form tambah log';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    message = 'Route create_ajax tidak ditemukan (404)';
                } else if (xhr.status === 500) {
                    message = 'Server error (500)';
                }
                alert(message);
            },
            complete: function() {
                // Reset button state
                $('#btnTambahLog').prop('disabled', false).html('Tambah Log');
            }
        });
    });

    // Delegasi event untuk tombol edit
    @if ($hasPengajuanDiterima)
    $('#logTable').on('click', '.btn-edit', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        
        $.ajax({
            url: `/mahasiswa/log-harian/${id}/edit`,
            type: 'GET',
            success: function (res) {
                if (res.success) {
                    // Load form template
                    $.get("{{ route('mahasiswa.log-harian.create_ajax') }}", function(viewRes) {
                        if (viewRes.success) {
                            $('#modalContent').html(viewRes.data.form_html);
                            $('#logModal').modal('show');
                            
                            // Modify form for update
                            $('#formLog').attr('data-method', 'PUT');
                            $('#formLog').attr('data-url', `/mahasiswa/log-harian/${id}`);
                            $('#logModalLabel').text('Edit Log Aktivitas');
                            $('#formLog textarea[name="aktivitas"]').val(res.data.aktivitas);
                            
                            // Setup form submit handler
                            setupFormHandler(true, id);
                        }
                    });
                } else {
                    alert(res.message);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                alert('Gagal memuat data log untuk edit');
            }
        });
    });

    // Delegasi event untuk tombol hapus
    $('#logTable').on('click', '.btn-delete', function (e) {
        e.preventDefault();
        if (!confirm("Yakin ingin menghapus log aktivitas ini?")) return;
        
        let id = $(this).data('id');
        
        $.ajax({
            url: `/mahasiswa/log-harian/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.success) {
                    alert(res.message);
                    table.ajax.reload(null, false); // Reload tanpa reset paging
                } else {
                    alert(res.message || 'Gagal menghapus log');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
                let message = xhr.responseJSON?.message || 'Gagal menghapus log aktivitas';
                alert(message);
            }
        });
    });
    @endif

    // Function untuk setup form handler - IMPROVED
    function setupFormHandler(isEdit = false, logId = null) {
        console.log('Setting up form handler, isEdit:', isEdit, 'logId:', logId);
        
        // Wait for modal to be fully shown
        $('#logModal').off('shown.bs.modal').on('shown.bs.modal', function() {
            console.log('Modal fully shown, setting up form handler');
            
            let form = $('#formLog');
            if (form.length === 0) {
                console.error('Form #formLog tidak ditemukan!');
                return;
            }
            
            console.log('Form ditemukan:', form);
            
            // Remove existing handlers to prevent multiple bindings
            form.off('submit.logHandler');
            
            // Add new submit handler
            form.on('submit.logHandler', function(e) {
                e.preventDefault();
                console.log('Form submitted');
                
                let submitBtn = form.find('button[type="submit"]');
                let originalText = submitBtn.html();
                
                // Disable submit button
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                
                let formData = new FormData(this);
                let url = isEdit ? `/mahasiswa/log-harian/${logId}` : "{{ route('mahasiswa.log-harian.store') }}";
                
                // Add method override for PUT
                if (isEdit) {
                    formData.append('_method', 'PUT');
                }
                
                console.log('Submitting to URL:', url);
                console.log('Form data:', Object.fromEntries(formData));

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        console.log('Sending form data...');
                    },
                    success: function(response) {
                        console.log('Form submit success:', response);
                        
                        if (response.success) {
                            $('#logModal').modal('hide');
                            alert(response.message);
                            @if ($hasPengajuanDiterima)
                            if (typeof table !== 'undefined') {
                                table.ajax.reload(null, false);
                            }
                            @endif
                        } else {
                            alert(response.message || 'Gagal menyimpan data');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Form submit error:', {
                            status: xhr.status,
                            responseText: xhr.responseText,
                            error: error
                        });
                        
                        let errors = xhr.responseJSON?.errors;
                        
                        if (errors) {
                            let errorMessages = [];
                            Object.keys(errors).forEach(function(key) {
                                errorMessages.push(errors[key].join(', '));
                            });
                            alert('Validation Error:\n' + errorMessages.join('\n'));
                        } else {
                            alert(xhr.responseJSON?.message || 'Gagal menyimpan data');
                        }
                    },
                    complete: function() {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    }

    // Clear modal when hidden
    $('#logModal').on('hidden.bs.modal', function () {
        $('#modalContent').empty();
    });
});
</script>
@endpush