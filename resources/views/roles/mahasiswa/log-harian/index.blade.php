@extends('layouts.template')

@section('title', 'Log Harian')

@section('content')
<!-- Pastikan CSRF Token tersedia -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card card-outline card-primary">
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                background: '#f0fff0',
                timer: 5000,
                timerProgressBar: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
            });
        </script>
    @endif

    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">{{ $page->title ?? 'Log Aktivitas Harian' }}</h3>
        @if ($hasPengajuanDiterima)
            <button class="btn btn-primary" id="btnTambahLog">
                <i class="fas fa-plus"></i> Tambah Log
            </button>
        @endif
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (!$hasPengajuanDiterima)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Anda belum memiliki pengajuan magang yang diterima. Silakan ajukan magang terlebih dahulu.
            </div>
            
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
            <table class="table table-bordered table-striped table-hover table-sm" id="logTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Aktivitas</th>
                        <th>Tanggal</th>
                        <th>Feedback Dosen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        @endif
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
</div>

<!-- Modal tambah/edit -->
<div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modalContent">
            <!-- Content akan diisi oleh create_ajax -->
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan feedback -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Feedback dari Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Aktivitas:</label>
                    <div class="p-2 bg-light rounded">
                        <p id="feedbackAktivitas" class="mb-0"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Feedback:</label>
                    <div class="p-2 bg-light rounded">
                        <p id="feedbackContent" class="mb-0"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Feedback:</label>
                    <div class="p-2 bg-light rounded">
                        <p id="feedbackDate" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Debug Info (bisa dihapus setelah selesai debugging) -->
@if(config('app.debug'))
<div class="mt-3" id="debugInfo" style="display: none;">
    <div class="alert alert-info">
        <h6>Debug Information:</h6>
        <p>Has Pengajuan Diterima: <strong>{{ $hasPengajuanDiterima ? 'Ya' : 'Tidak' }}</strong></p>
        <p>User ID: <strong>{{ Auth::id() }}</strong></p>
        <p>Mahasiswa ID: <strong>{{ Auth::user()->mahasiswa->mahasiswa_id ?? 'Null' }}</strong></p>
    </div>
</div>
@endif

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
$(document).ready(function () {
    console.log('Document ready, hasPengajuanDiterima: {{ $hasPengajuanDiterima ? 'true' : 'false' }}');
    
    // Global variable untuk table
    var table;
    
    // Hanya inisialisasi DataTable jika ada pengajuan yang diterima
    @if ($hasPengajuanDiterima)
    table = $('#logTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('mahasiswa.log-harian.list') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', xhr.responseText);
                
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memuat data: ' + (xhr.responseJSON?.message || 'Error tidak diketahui'),
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                className: "text-center",
                orderable: false, 
                searchable: false
            },
            { 
                data: 'aktivitas', 
                render: function(data, type, row) {
                    if (type === 'display' && data && data.length > 100) {
                        return data.substr(0, 100) + '...';
                    }
                    return data ? data : '-';
                }
            },
            { 
                data: 'created_at',
                render: function(data, type, row) {
                    return data ? data : '-';
                }
            },
            { 
                data: null,
                orderable: false, 
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    // Cek apakah ada feedback dari dosen
                    if (row.feedback && row.feedback.trim() !== '') {
                        return '<button class="btn btn-sm btn-info btn-feedback" data-id="' + row.log_id + '" title="Lihat Feedback">' +
                               '<i class="fas fa-comment"></i> Lihat Feedback</button>';
                    } else {
                        return '<span class="badge bg-secondary">Belum ada feedback</span>';
                    }
                }
            },
            { 
                data: 'action',
                className: 'text-center',
                orderable: false, 
                searchable: false
            },
        ]
    });
    @endif

    // Function untuk modal action (mengikuti pola pengajuan-magang)
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        });
    }

    // Event handler untuk tombol tambah log
    $(document).on('click', '#btnTambahLog', function(e) {
        e.preventDefault();
        console.log('Tombol Tambah Log diklik');
        
        var $btn = $(this);
        var originalText = $btn.html();
        
        // Show loading state
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        
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
                    setupFormHandler(false);
                } else {
                    console.error('Response error:', response.message);
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'Gagal memuat form',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
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
                } else if (xhr.status === 403) {
                    message = 'Tidak memiliki akses (403)';
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                // Reset button state
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delegasi event untuk tombol edit
    @if ($hasPengajuanDiterima)
    $('#logTable').on('click', '.btn-edit', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        
        var $btn = $(this);
        var originalText = $btn.html();
        
        // Show loading state
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: `/mahasiswa/log-harian/${id}/edit_ajax`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.success) {
                    $('#modalContent').html(res.data.form_html);
                    $('#logModal').modal('show');
                    
                    // Setup form submit handler untuk edit
                    setupFormHandler(true, id);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: res.message,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal memuat data log untuk edit',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                // Reset button state
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delegasi event untuk tombol hapus
    $('#logTable').on('click', '.btn-delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Yakin ingin menghapus log aktivitas ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var $btn = $(this);
                var originalText = $btn.html();
                
                // Show loading state
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: `/mahasiswa/log-harian/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                timer: 3000
                            });
                            
                            // Reload table
                            if (typeof table !== 'undefined') {
                                table.ajax.reload(null, false);
                            }
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: res.message || 'Gagal menghapus log',
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseText);
                        let message = xhr.responseJSON?.message || 'Gagal menghapus log aktivitas';
                        
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    },
                    complete: function() {
                        // Reset button state
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            }
        });
    });

    // Delegasi event untuk tombol feedback
    $('#logTable').on('click', '.btn-feedback', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        
        var $btn = $(this);
        var originalText = $btn.html();
        
        // Show loading state
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: `/mahasiswa/log-harian/${id}/feedback`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                console.log('Mengirim request untuk feedback log ID:', id);
            },
            success: function (res) {
                console.log('Response feedback:', res);
                if (res.success && res.data) {
                    // Isi modal dengan data feedback
                    $('#feedbackAktivitas').text(res.data.aktivitas || 'Tidak ada aktivitas');
                    $('#feedbackContent').text(res.data.feedback || 'Tidak ada feedback');
                    $('#feedbackDate').text(res.data.feedback_created_at || 'Tidak diketahui');
                    $('#feedbackModal').modal('show');
                } else {
                    Swal.fire({
                        title: 'Informasi',
                        text: res.message || 'Belum ada feedback dari dosen',
                        icon: 'info',
                        confirmButtonColor: '#3085d6'
                    });
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
                let message = xhr.responseJSON?.message || 'Gagal memuat feedback dari dosen';
                
                Swal.fire({
                    title: 'Error',
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                // Reset button state
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
    @endif

    // Function untuk setup form handler
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
                        // Clear previous validation errors
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                    },
                    success: function(response) {
                        console.log('Form submit success:', response);
                        
                        if (response.success) {
                            $('#logModal').modal('hide');
                            
                            // Show success message
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            // Reload table
                            @if ($hasPengajuanDiterima)
                            if (typeof table !== 'undefined') {
                                table.ajax.reload(null, false);
                            }
                            @endif
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Gagal menyimpan data',
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
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
                            // Show validation errors
                            Object.keys(errors).forEach(function(key) {
                                let field = form.find(`[name="${key}"]`);
                                let errorDiv = form.find(`#${key}-error`);
                                
                                field.addClass('is-invalid');
                                errorDiv.text(errors[key].join(', '));
                            });
                            
                            // Also show in alert
                            let errorMessages = [];
                            Object.keys(errors).forEach(function(key) {
                                errorMessages.push(errors[key].join(', '));
                            });
                            
                            Swal.fire({
                                title: 'Validation Error!',
                                text: errorMessages.join('\n'),
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Gagal menyimpan data',
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
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
        $(this).off('shown.bs.modal'); // Remove event handler
    });

    // Clear feedback modal when hidden
    $('#feedbackModal').on('hidden.bs.modal', function () {
        $('#feedbackAktivitas').text('');
        $('#feedbackContent').text('');
        $('#feedbackDate').text('');
    });

    // Debug toggle (jika dalam mode debug)
    @if(config('app.debug'))
    $(document).on('dblclick', '.card-title', function() {
        $('#debugInfo').toggle();
    });
    @endif

    // Initialize tooltips
    $('[title]').tooltip();
});
</script>
@endpush