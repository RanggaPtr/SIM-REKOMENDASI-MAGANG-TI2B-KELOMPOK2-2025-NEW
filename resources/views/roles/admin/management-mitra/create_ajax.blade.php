<form action="{{ url('/admin/management-mitra/ajax') }}" method="POST" id="form-tambah"
    enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Perusahaan Mitra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Perusahaan</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Ringkasan</label>
                    <input type="text" name="ringkasan" id="ringkasan" class="form-control" required>
                    <small id="error-ringkasan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
                    <small id="error-logo" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                    <small id="error-alamat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Wilayah</label>
                    <select name="wilayah_id" id="wilayah_id" class="form-control" required>
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $wilayah)
                            <option value="{{ $wilayah->id }}">{{ $wilayah->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-wilayah_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kontak</label>
                    <input type="text" name="kontak" id="kontak" class="form-control" required>
                    <small id="error-kontak" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bidang Industri</label>
                    <input type="text" name="bidang_industri" id="bidang_industri" class="form-control" required>
                    <small id="error-bidang_industri" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Rating (0-5)</label>
                    <input type="number" name="rating" id="rating" class="form-control" min="0" max="5" step="0.1"
                        required>
                    <small id="error-rating" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi Rating</label>
                    <input type="text" name="deskripsi_rating" id="deskripsi_rating" class="form-control" required>
                    <small id="error-deskripsi_rating" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                nama: { required: true, maxlength: 150 },
                ringkasan: { required: true },
                deskripsi: { required: true },
                alamat: { required: true },
                wilayah_id: { required: true },
                kontak: { required: true },
                bidang_industri: { required: true },
                rating: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 5
                },
                deskripsi_rating: { required: true },
                logo: {
                    extension: "jpg|jpeg|png|gif",
                    filesize: 2 * 1024 * 1024 // 2MB
                }
            },
            messages: {
                nama: {
                    required: "Nama perusahaan wajib diisi",
                    maxlength: "Nama maksimal 150 karakter"
                },
                ringkasan: { required: "Ringkasan wajib diisi" },
                deskripsi: { required: "Deskripsi wajib diisi" },
                alamat: { required: "Alamat wajib diisi" },
                wilayah_id: { required: "Wilayah wajib dipilih" },
                kontak: { required: "Kontak wajib diisi" },
                bidang_industri: { required: "Bidang industri wajib diisi" },
                rating: {
                    required: "Rating wajib diisi",
                    number: "Rating harus berupa angka",
                    min: "Minimal 0",
                    max: "Maksimal 5"
                },
                deskripsi_rating: { required: "Deskripsi rating wajib diisi" },
                logo: {
                    extension: "Format gambar harus jpg, jpeg, png, atau gif",
                    filesize: "Ukuran maksimal gambar 2MB"
                }
            },
            submitHandler: function (form) {
                var formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data perusahaan mitra berhasil disimpan.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                dataPerusahaan.ajax.reload();
                            });
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Gagal menyimpan data.'
                            });
                        }
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON?.errors;
                        $('.error-text').text('');
                        if (errors) {
                            $.each(errors, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal memproses permintaan.'
                            });
                        }
                    }
                });

                return false;
            },
            errorElement: 'small',
            errorClass: 'form-text text-danger error-text',
            errorPlacement: function (error, element) {
                var id = element.attr('id');
                $('#error-' + id).text(error.text());
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });

        // Custom validator for file size
        $.validator.addMethod("filesize", function (value, element, param) {
            if (element.files.length === 0) return true; // ignore if no file
            return this.optional(element) || (element.files[0].size <= param);
        }, "Ukuran file terlalu besar.");
    });
</script>