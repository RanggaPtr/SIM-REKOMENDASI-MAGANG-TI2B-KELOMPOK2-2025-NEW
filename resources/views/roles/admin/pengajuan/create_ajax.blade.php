<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Data Pengajuan Magang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ url('/admin/management-pengajuan-magang/ajax') }}" method="POST" id="form-tambah">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mahasiswa_id" class="form-label">Mahasiswa <span class="text-danger">*</span></label>
                            <select name="mahasiswa_id" id="mahasiswa_id" class="form-select" required>
                                <option value="">- Pilih Mahasiswa -</option>
                                @foreach($mahasiswa as $m)
                                    <option value="{{ $m->mahasiswa_id }}">
                                        {{ $m->user->name ?? $m->user->nama }} - {{ $m->nim }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-mahasiswa_id"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="lowongan_id" class="form-label">Lowongan Magang <span class="text-danger">*</span></label>
                            <select name="lowongan_id" id="lowongan_id" class="form-select" required>
                                <option value="">- Pilih Lowongan -</option>
                                @foreach($lowongan as $l)
                                    <option value="{{ $l->lowongan_id }}" 
                                            data-perusahaan="{{ $l->perusahaan->nama ?? $l->perusahaan->nama_perusahaan }}"
                                            data-kompetensi="{{ $l->kompetensis->pluck('nama')->implode(', ') }}">
                                        {{ $l->judul ?? $l->posisi }} - {{ $l->perusahaan->nama ?? $l->perusahaan->nama_perusahaan }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-lowongan_id"></div>
                            <small class="form-text text-muted" id="lowongan-info" style="display: none;"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="periode_id" class="form-label">Periode Magang <span class="text-danger">*</span></label>
                            <select name="periode_id" id="periode_id" class="form-select" required>
                                <option value="">- Pilih Periode -</option>
                                @foreach($periode as $p)
                                    <option value="{{ $p->periode_id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-periode_id"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="dosen_id" class="form-label">Dosen Pembimbing</label>
                            <select name="dosen_id" id="dosen_id" class="form-select">
                                <option value="">- Pilih Dosen (Opsional) -</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d->dosen_id }}" 
                                            data-kompetensi="{{ $d->kompetensi->nama ?? 'Umum' }}"
                                            data-nip="{{ $d->nip }}">
                                        {{ $d->user->name ?? $d->user->nama }} - {{ $d->kompetensi->nama ?? 'Umum' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-dosen_id"></div>
                            <small class="form-text text-info">
                                <i class="fas fa-info-circle"></i> Dosen akan otomatis dipilih berdasarkan kompetensi jika tidak diisi
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">- Pilih Status -</option>
                                <option value="diajukan" selected>Diajukan</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <div class="invalid-feedback" id="error-status"></div>
                        </div>
                        
                        <!-- Info Panel -->
                        <div class="mb-3">
                            <div class="card border-info" id="info-panel" style="display: none;">
                                <div class="card-header bg-light">
                                    <small class="fw-bold">Informasi Lowongan</small>
                                </div>
                                <div class="card-body p-2">
                                    <div id="lowongan-details"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loading indicator -->
                <div id="loading" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Menyimpan data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
                    <i class="fas fa-save me-1"></i> Simpan
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
        $('.error-text').empty();
    }
    
    // Show validation errors
    function showValidationErrors(errors) {
        $.each(errors, function(field, messages) {
            var input = $('#' + field);
            var errorDiv = $('#error-' + field);
            
            input.addClass('is-invalid');
            errorDiv.text(messages[0]);
        });
    }
    
    // Show loading state
    function showLoading() {
        $('#loading').show();
        $('#submitBtn').prop('disabled', true);
        $('#submitBtn .spinner-border').show();
        $('.modal-body').css('opacity', '0.7');
    }
    
    // Hide loading state
    function hideLoading() {
        $('#loading').hide();
        $('#submitBtn').prop('disabled', false);
        $('#submitBtn .spinner-border').hide();
        $('.modal-body').css('opacity', '1');
    }
    
    // Enhanced form validation with jQuery Validate
    $("#form-tambah").validate({
        rules: {
            mahasiswa_id: {
                required: true,
                number: true
            },
            lowongan_id: {
                required: true,
                number: true
            },
            periode_id: {
                required: true,
                number: true
            },
            status: {
                required: true
            },
            dosen_id: {
                number: true
            }
        },
        messages: {
            mahasiswa_id: {
                required: "Silakan pilih mahasiswa",
                number: "ID mahasiswa harus berupa angka"
            },
            lowongan_id: {
                required: "Silakan pilih lowongan magang",
                number: "ID lowongan harus berupa angka"
            },
            periode_id: {
                required: "Silakan pilih periode magang",
                number: "ID periode harus berupa angka"
            },
            status: {
                required: "Silakan pilih status"
            }
        },
        submitHandler: function(form) {
            clearValidationErrors();
            showLoading();
            
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    hideLoading();
                    
                    if (response.status || response.success) {
                        // Hide modal
                        var modalEl = document.getElementById('myModal');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data pengajuan berhasil ditambahkan',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Reload DataTable
                        if (typeof window.pengajuanTable !== 'undefined') {
                            window.pengajuanTable.ajax.reload();
                        } else if (typeof dataTable !== 'undefined') {
                            dataTable.ajax.reload();
                        } else if ($('#pengajuanTable').length > 0) {
                            $('#pengajuanTable').DataTable().ajax.reload();
                        }
                        
                        // Reset form
                        form.reset();
                        $('#info-panel').hide();
                        
                    } else {
                        // Handle validation errors
                        if (response.msgField) {
                            showValidationErrors(response.msgField);
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message || 'Gagal menyimpan data'
                        });
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    
                    if (xhr.status === 422) {
                        // Laravel validation errors
                        var errors = xhr.responseJSON.errors;
                        showValidationErrors(errors);
                    } else {
                        var message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.addClass('is-invalid');
            element.closest('.mb-3').find('.invalid-feedback').first().html(error.html());
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
    
    // Lowongan selection handler
    $('#lowongan_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var perusahaan = selectedOption.data('perusahaan');
        var kompetensi = selectedOption.data('kompetensi');
        
        if ($(this).val()) {
            var infoHtml = '<small><strong>Perusahaan:</strong> ' + perusahaan + '</small><br>';
            if (kompetensi) {
                infoHtml += '<small><strong>Kompetensi:</strong> ' + kompetensi + '</small>';
            }
            
            $('#lowongan-details').html(infoHtml);
            $('#info-panel').show();
            
            // Auto-suggest dosen based on competency
            suggestDosen(kompetensi);
        } else {
            $('#info-panel').hide();
        }
    });
    
    // Auto-suggest dosen based on competency
    function suggestDosen(kompetensiLowongan) {
        if (!kompetensiLowongan) return;
        
        $('#dosen_id option').each(function() {
            var dosenKompetensi = $(this).data('kompetensi');
            if (dosenKompetensi && kompetensiLowongan.includes(dosenKompetensi)) {
                $(this).css('background-color', '#e8f5e8');
                $(this).prepend('⭐ ');
            }
        });
    }
    
    // Status change handler
    $('#status').on('change', function() {
        var status = $(this).val();
        var dosenSelect = $('#dosen_id');
        
        if (status === 'diterima') {
            dosenSelect.prop('required', true);
            dosenSelect.closest('.mb-3').find('.form-label').html('Dosen Pembimbing <span class="text-danger">*</span>');
        } else {
            dosenSelect.prop('required', false);
            dosenSelect.closest('.mb-3').find('.form-label').html('Dosen Pembimbing');
        }
    });
    
    // Initialize
    $('#status').trigger('change');
});
</script>