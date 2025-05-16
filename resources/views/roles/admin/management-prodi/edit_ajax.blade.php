@empty($programStudi)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/admin/management-prodi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/admin/management-prodi/' . $programStudi->prodi_id . '/update_ajax') }}" method="POST"
        id="form-edit-program-studi">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Program Studi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <input value="{{ $programStudi->nama }}" type="text" name="nama" id="nama" class="form-control"
                            required>
                        <small id="error-nama" class="error-text form-text text-danger"></small>
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
                $("#form-edit-program-studi").validate({
                    rules: {
                        nama: { required: true, minlength: 3, maxlength: 100 }
                    },
                    messages: {
                        nama: {
                            required: "Nama Program Studi tidak boleh kosong",
                            minlength: "Nama Program Studi harus terdiri dari minimal 3 karakter",
                            maxlength: "Nama Program Studi tidak boleh lebih dari 100 karakter"
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
                                    // Menutup modal setelah berhasil
                                    $('#modal-master').modal('hide');

                                    // Menampilkan SweetAlert dengan pesan sukses
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    }).then(function () {
                                        // Perbarui nama di tabel sesuai dengan input yang diedit
                                        $('#nama-program-studi-' + response.id).text(response.nama);
                                    });
                                } else {
                                    $('.error-text').text('');
                                    $.each(response.msgField, function (prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                    });
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Terjadi Kesalahan',
                                        text: response.message
                                    });
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
                        return false;
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });

    </script>
@endempty