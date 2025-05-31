<form action="{{ url('/mahasiswa/pengajuan-magang/' . $pengajuan->pengajuan_id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengajuan Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Lowongan Magang</label>
                    <select name="lowongan_id" id="lowongan_id" class="form-control" required>
                        <option value="">- Pilih Lowongan -</option>
                        @foreach($lowongan as $item)
                            <option value="{{ $item->lowongan_id }}" {{ $pengajuan->lowongan_id == $item->lowongan_id ? 'selected' : '' }}>
                                {{ $item->judul }} - {{ $item->perusahaan->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-lowongan_id" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group mb-3">
                    <label>Dosen Pembimbing</label>
                    <select name="dosen_id" id="dosen_id" class="form-control" required>
                        <option value="">- Pilih Dosen -</option>
                        @foreach($dosen as $item)
                            <option value="{{ $item->dosen_id }}" {{ $pengajuan->dosen_id == $item->dosen_id ? 'selected' : '' }}>
                                {{ $item->user->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-dosen_id" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group mb-3">
                    <label>Periode Magang</label>
                    <select name="periode_id" id="periode_id" class="form-control" required>
                        <option value="">- Pilih Periode -</option>
                        @foreach($periode as $item)
                            <option value="{{ $item->periode_id }}" {{ $pengajuan->periode_id == $item->periode_id ? 'selected' : '' }}>
                                {{ $item->nama }} ({{ $item->tanggal_mulai }} s/d {{ $item->tanggal_selesai }})
                            </option>
                        @endforeach
                    </select>
                    <small id="error-periode_id" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-edit").on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: form.action,
                type: 'POST', // Karena Laravel tidak bisa langsung menggunakan PUT
                data: $(form).serialize() + '&_method=PUT',
                success: function(response) {
                    if (response.status) {
                        $('.modal').modal('hide');
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                        $('#table_pengajuan').DataTable().ajax.reload(null, false);
                    } else {
                        $('.error-text').text('');
                        $.each(response.errors, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    $('.error-text').text('');
                    $.each(errors, function(prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
                }
            });
        });
    });
</script>