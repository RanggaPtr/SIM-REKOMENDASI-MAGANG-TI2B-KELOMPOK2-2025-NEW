<form action="{{ url('/mahasiswa/pengajuan-magang/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajukan Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Lowongan Magang</label>
                    <select name="lowongan_id" id="lowongan_id" class="form-control" required>
                        <option value="">- Pilih Lowongan -</option>
                        @foreach($lowongan as $item)
                            <option value="{{ $item->lowongan_id }}">{{ $item->judul }} ({{ $item->perusahaan->nama_perusahaan }})</option>
                        @endforeach
                    </select>
                    <small id="error-lowongan_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Dosen Pembimbing</label>
                    <select name="dosen_id" id="dosen_id" class="form-control" required>
                        <option value="">- Pilih Dosen -</option>
                        @foreach($dosen as $item)
                            <option value="{{ $item->dosen_id }}">{{ $item->user->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-dosen_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Periode Magang</label>
                    <select name="periode_id" id="periode_id" class="form-control" required>
                        <option value="">- Pilih Periode -</option>
                        @foreach($periode as $item)
                            <option value="{{ $item->periode_id }}">{{ $item->nama }} ({{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }})</option>
                        @endforeach
                    </select>
                    <small id="error-periode_id" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Ajukan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-tambah").on('submit', function (e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        $('.modal').modal('hide');
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                        tablePengajuan.ajax.reload(null, false);
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function (prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    $('.error-text').text('');
                    $.each(errors, function (prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
                }
            });
        });
    });
</script>