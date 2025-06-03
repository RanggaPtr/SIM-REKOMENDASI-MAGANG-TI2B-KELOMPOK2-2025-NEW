<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Pengajuan Magang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editPengajuanForm" action="{{ route('admin.pengajuan.update_ajax', $pengajuan->pengajuan_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="diajukan" {{ $pengajuan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="diterima" {{ $pengajuan->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="selesai" {{ $pengajuan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <div class="invalid-feedback" id="status-error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dosen_id" class="form-label">Dosen Pembimbing</label>
                            <select name="dosen_id" id="dosen_id" class="form-select">
                                <option value="">Pilih Dosen</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->dosen_id }}"
                                        {{ $pengajuan->dosen_id == $dosen->dosen_id ? 'selected' : '' }}
                                        data-kompetensi="{{ $dosen->kompetensi->nama ?? 'Tidak Ada' }}">
                                        {{ $dosen->user->name }} ({{ $dosen->kompetensi->nama ?? 'Tidak Ada' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="dosen_id-error"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Info Pengajuan</label>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? '-' }}</p>
                                    <p class="mb-1"><strong>Perusahaan:</strong> {{ $pengajuan->lowongan->perusahaan->nama ?? '-' }}</p>
                                    <p class="mb-0"><strong>Lowongan:</strong> {{ $pengajuan->lowongan->judul ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Kompetensi Lowongan</label>
                            <div>
                                @if($pengajuan->lowongan->kompetensis->count() > 0)
                                    @foreach ($pengajuan->lowongan->kompetensis as $kompetensi)
                                        <span class="badge bg-info me-1 mb-1">{{ $kompetensi->nama }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">Tidak Ada Kompetensi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loading indicator -->
                <div id="loading" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Clear previous validation errors
        function clearValidationErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
        }
        
        // Show validation errors
        function showValidationErrors(errors) {
            $.each(errors, function(field, messages) {
                var input = $('#' + field);
                var errorDiv = $('#' + field + '-error');
                
                input.addClass('is-invalid');
                errorDiv.text(messages[0]);
            });
        }
        
        // Show loading state
        function showLoading() {
            $('#loading').show();
            $('#submitBtn').prop('disabled', true);
            $('#submitBtn .spinner-border').show();
        }
        
        // Hide loading state
        function hideLoading() {
            $('#loading').hide();
            $('#submitBtn').prop('disabled', false);
            $('#submitBtn .spinner-border').hide();
        }
        
        $('#editPengajuanForm').on('submit', function(e) {
            e.preventDefault();
            
            clearValidationErrors();
            showLoading();
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    hideLoading();
                    
                    if (response.success) {
                        // Reload DataTable if exists
                        if (typeof window.pengajuanTable !== 'undefined') {
                            window.pengajuanTable.ajax.reload();
                        } else if ($('#pengajuanTable').length > 0) {
                            $('#pengajuanTable').DataTable().ajax.reload();
                        }
                        
                        // Hide modal
                        var modalEl = document.getElementById('myModal');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }
                        
                        // Show success message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            alert(response.message);
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        var errors = xhr.responseJSON.errors;
                        showValidationErrors(errors);
                    } else {
                        var message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: message
                            });
                        } else {
                            alert('Terjadi kesalahan: ' + message);
                        }
                    }
                }
            });
        });
        
        // Enhanced dosen selection with kompetensi matching
        $('#dosen_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var kompetensi = selectedOption.data('kompetensi');
            
            // You can add logic here to highlight matching competencies
            if (kompetensi && kompetensi !== 'Tidak Ada') {
                console.log('Dosen dengan kompetensi: ' + kompetensi);
            }
        });
        
        // Status change handler
        $('#status').on('change', function() {
            var status = $(this).val();
            var dosenSelect = $('#dosen_id');
            
            // If status is 'diterima', make dosen selection required
            if (status === 'diterima') {
                dosenSelect.prop('required', true);
                dosenSelect.closest('.mb-3').find('.form-label').append(' <span class="text-danger">*</span>');
            } else {
                dosenSelect.prop('required', false);
                dosenSelect.closest('.mb-3').find('.text-danger').remove();
            }
        });
        
        // Trigger status change on load
        $('#status').trigger('change');
    });
</script>