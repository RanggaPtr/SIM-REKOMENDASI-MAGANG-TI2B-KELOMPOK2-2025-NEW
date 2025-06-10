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
                                <option value="">Pilih Dosen (Kompetensi: Tidak Ditentukan)</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->dosen_id }}"
                                        {{ $pengajuan->dosen_id == $dosen->dosen_id ? 'selected' : '' }}
                                        data-kompetensi="{{ $dosen->kompetensi->nama ?? 'Tidak Ada' }}">
                                        {{ $dosen->user->nama }} (Kompetensi: {{ $dosen->kompetensi->nama ?? 'Tidak Ada' }})
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
                                    <p class="mb-1"><strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->nama ?? '-' }}</p>
                                    <p class="mb-1"><strong>Perusahaan:</strong> {{ $pengajuan->lowongan->perusahaan->nama ?? '-' }}</p>
                                    <p class="mb-1"><strong>Lowongan:</strong> {{ $pengajuan->lowongan->judul ?? '-' }}</p>
                                    <p class="mb-1"><strong>Keahlian:</strong>
                                        @if($pengajuan->mahasiswa->mahasiswaKeahlian->count() > 0)
                                            @foreach($pengajuan->mahasiswa->mahasiswaKeahlian as $keahlian)
                                                <span class="badge bg-info me-1 mb-1">{{ $keahlian->keahlian->nama ?? '-' }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada keahlian</span>
                                        @endif
                                    </p>
                                    <p class="mb-1"><strong>Kompetensi:</strong>
                                        @if($pengajuan->mahasiswa->mahasiswaKompetensi->count() > 0)
                                            @foreach($pengajuan->mahasiswa->mahasiswaKompetensi as $kompetensi)
                                                <span class="badge bg-primary me-1 mb-1">{{ $kompetensi->kompetensi->nama ?? '-' }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada kompetensi</span>
                                        @endif
                                    </p>
                                    <p class="mb-0"><strong>File CV:</strong>
                                        @if($pengajuan->mahasiswa->file_cv)
                                            <a href="{{ Storage::url($pengajuan->mahasiswa->file_cv) }}" class="text-primary" target="_blank">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>Unduh CV
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada CV</span>
                                        @endif
                                    </p>
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
        function clearValidationErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
        }

        function showValidationErrors(errors) {
            $.each(errors, function(field, messages) {
                var input = $('#' + field);
                var errorDiv = $('#' + field + '-error');

                input.addClass('is-invalid');
                errorDiv.text(messages[0]);
            });
        }

        function showLoading() {
            $('#loading').show();
            $('#submitBtn').prop('disabled', true);
            $('#submitBtn .spinner-border').show();
        }

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
                        if (typeof window.pengajuanTable !== 'undefined') {
                            window.pengajuanTable.ajax.reload();
                        } else if ($('#pengajuanTable').length > 0) {
                            $('#pengajuanTable').DataTable().ajax.reload();
                        }

                        var modalEl = document.getElementById('myModal');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }

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

        $('#dosen_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var kompetensi = selectedOption.data('kompetensi');

            if (kompetensi && kompetensi !== 'Tidak Ditentukan') {
                console.log('Dosen dengan kompetensi: ' + kompetensi);
            }
        });

        $('#status').on('change', function() {
            var status = $(this).val();
            var dosenSelect = $('#dosen_id');
            var initialStatus = '{{ $pengajuan->status }}';

            // Hanya set required jika status BARU adalah 'diterima'
            if (status === 'diterima') {
                dosenSelect.prop('required', true);
                dosenSelect.closest('.mb-3').find('.form-label').append(' <span class="text-danger">*</span>');
            } else {
                dosenSelect.prop('required', false);
                dosenSelect.closest('.mb-3').find('.text-danger').remove();
            }
        });

        // Atur validasi awal berdasarkan status dan nilai dosen_id
        var initialStatus = '{{ $pengajuan->status }}';
        var dosenSelect = $('#dosen_id');
        if (initialStatus === 'diterima' && dosenSelect.val()) {
            dosenSelect.prop('required', true);
            dosenSelect.closest('.mb-3').find('.form-label').append(' <span class="text-danger">*</span>');
        } else {
            dosenSelect.prop('required', false);
            dosenSelect.closest('.mb-3').find('.text-danger').remove();
        }
    });
</script>