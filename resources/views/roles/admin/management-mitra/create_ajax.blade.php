<form action="{{ url('/admin/management-mitra/ajax') }}" method="POST" id="form-tambah-mitra"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Perusahaan Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Ringkasan</label>
                    <input type="text" name="ringkasan" id="ringkasan" class="form-control">
                    <small id="error-ringkasan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Bidang Industri</label>
                    <input type="text" name="bidang_industri" id="bidang_industri" class="form-control">
                    <small id="error-bidang_industri" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Alamat <span class="text-danger">*</span></label>
                    <input type="text" name="alamat" id="alamat" class="form-control" required>
                    <small id="error-alamat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Wilayah <span class="text-danger">*</span></label>
                    <select name="wilayah_id" id="wilayah_id" class="form-control" required>
                        <option value="">- Pilih Wilayah -</option>
                        @foreach($wilayahs as $wilayah)
                            <option value="{{ $wilayah->wilayah_id }}">{{ $wilayah->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-wilayah_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Kontak</label>
                    <input type="text" name="kontak" id="kontak" class="form-control">
                    <small id="error-kontak" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Rating</label>
                    <input type="number" name="rating" id="rating" class="form-control" min="0" max="5" step="0.1">
                    <small id="error-rating" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Deskripsi Rating</label>
                    <input type="text" name="deskripsi_rating" id="deskripsi_rating" class="form-control">
                    <small id="error-deskripsi_rating" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control">
                    <small id="error-logo" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#form-tambah-mitra').on('submit', function (e) {
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
                            text: 'Data berhasil ditambahkan',
                            confirmButtonText: 'OK'
                        });
                        if (window.dataPerusahaan) window.dataPerusahaan.ajax.reload(null, false);
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField || response.errors || {}, function (key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors || {};
                    $('.error-text').text('');
                    $.each(errors, function (key, val) {
                        $('#error-' + key).text(val[0]);
                    });
                }
            });
        });
    });
</script>