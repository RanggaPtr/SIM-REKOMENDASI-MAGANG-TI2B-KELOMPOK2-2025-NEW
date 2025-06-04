<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Konfirmasi Hapus Data
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 4rem; opacity: 0.7;"></i>
                </div>
                <h4 class="text-danger mb-3">Apakah Anda Yakin?</h4>
                <p class="text-muted mb-0">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            
            <!-- Detail data yang akan dihapus -->
            <div class="card border-danger">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-danger">
                        <i class="fas fa-info-circle me-1"></i>
                        Detail Pengajuan yang akan dihapus:
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4"><strong>ID Pengajuan:</strong></div>
                        <div class="col-sm-8">{{ $pengajuan->pengajuan_id }}</div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-4"><strong>Mahasiswa:</strong></div>
                        <div class="col-sm-8">{{ $pengajuan->mahasiswa->user->name ?? '-' }}</div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-4"><strong>NIM:</strong></div>
                        <div class="col-sm-8">{{ $pengajuan->mahasiswa->nim ?? '-' }}</div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-4"><strong>Perusahaan:</strong></div>
                        <div class="col-sm-8">{{ $pengajuan->lowongan->perusahaan->nama ?? $pengajuan->lowongan->perusahaan->nama_perusahaan ?? '-' }}</div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-4"><strong>Lowongan:</strong></div>
                        <div class="col-sm-8">{{ $pengajuan->lowongan->judul ?? $pengajuan->lowongan->posisi ?? '-' }}</div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-4"><strong>Status:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $pengajuan->status == 'diterima' ? 'success' : ($pengajuan->status == 'ditolak' ? 'danger' : ($pengajuan->status == 'selesai' ? 'info' : 'warning')) }}">
                                {{ ucfirst($pengajuan->status) }}
                            </span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-sm-4"><strong>Tanggal Dibuat:</strong></div>
                        <div class="col-sm-8">{{ $pengajuan->created_at ? $pengajuan->created_at->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Warning message -->
            <div class="alert alert-warning mt-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Peringatan:</strong> 
                Menghapus data pengajuan akan menghapus semua data terkait termasuk riwayat dan feedback yang sudah diberikan.
            </div>
        </div>
        <div class="modal-footer">
            <form id="deleteForm" action="{{ route('admin.pengajuan.delete_ajax', $pengajuan->pengajuan_id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <button type="submit" class="btn btn-danger" id="deleteBtn">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
                    <i class="fas fa-trash me-1"></i>
                    Ya, Hapus Data
                </button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Show loading state
    function showLoading() {
        $('#deleteBtn').prop('disabled', true);
        $('#deleteBtn .spinner-border').show();
        $('#deleteBtn').html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...');
    }
    
    // Hide loading state
    function hideLoading() {
        $('#deleteBtn').prop('disabled', false);
        $('#deleteBtn .spinner-border').hide();
        $('#deleteBtn').html('<i class="fas fa-trash me-1"></i>Ya, Hapus Data');
    }
    
    // Handle form submission
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        
        showLoading();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                hideLoading();
                
                if (response.success || response.status) {
                    // Hide modal
                    var modalEl = document.getElementById('myModal');
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Show success message with SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Dihapus!',
                        text: response.message || 'Data pengajuan berhasil dihapus',
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        // Reload DataTable after success message
                        if (typeof window.pengajuanTable !== 'undefined') {
                            window.pengajuanTable.ajax.reload();
                        } else if (typeof dataTable !== 'undefined') {
                            dataTable.ajax.reload();
                        } else if ($('#pengajuanTable').length > 0) {
                            $('#pengajuanTable').DataTable().ajax.reload();
                        }
                    });
                    
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus',
                        text: response.message || 'Terjadi kesalahan saat menghapus data',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                hideLoading();
                
                var message = 'Terjadi kesalahan pada server';
                
                if (xhr.responseJSON) {
                    message = xhr.responseJSON.message || message;
                }
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    confirmButtonText: 'OK'
                });
                
                console.error('Delete error:', xhr);
            }
        });
    });
    
    // Add keyboard support (Enter to confirm, Escape to cancel)
    $(document).on('keydown', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $('#deleteForm').submit();
        } else if (e.which === 27) { // Escape key
            var modalEl = document.getElementById('myModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        }
    });
    
    // Auto focus on cancel button for better UX
    setTimeout(function() {
        $('button[data-bs-dismiss="modal"]').focus();
    }, 300);
});
</script>