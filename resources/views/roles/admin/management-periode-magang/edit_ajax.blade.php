@empty($periode)
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
                <a href="{{ url('/admin/management-periode-magang') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/admin/management-periode-magang/' . $periode->id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Periode Magang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Periode</label>
                        <input value="{{ $periode->nama }}" type="text" name="nama" id="nama" class="form-control" required>
                        <small id="error-nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input value="{{ $periode->tanggal_mulai }}" type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                        <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input value="{{ $periode->tanggal_selesai }}" type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                        <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
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
            $("#form-edit").validate({
                rules: {
                    nama: { required: true, maxlength: 100 },
                    tanggal_mulai: { required: true, date: true },
                    tanggal_selesai: { 
                        required: true, 
                        date: true,
                        greaterThan: "#tanggal_mulai"
                    }
                },
                messages: {
                    tanggal_selesai: {
                        greaterThan: "Tanggal selesai harus setelah tanggal mulai"
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataPeriode.ajax.reload();
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
                        error: function(xhr) {
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

            $.validator.addMethod("greaterThan", 
                function(value, element, params) {
                    if (!/Invalid|NaN/.test(new Date(value))) {
                        return new Date(value) > new Date($(params).val());
                    }
                    return isNaN(value) && isNaN($(params).val()) 
                        || (Number(value) > Number($(params).val())); 
                }, 'Must be greater than {0}.');
        });
    </script>
@endempty