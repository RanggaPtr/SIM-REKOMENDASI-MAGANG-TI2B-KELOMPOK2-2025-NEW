<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Import Data Program Studi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ url('/admin/management-prodi/import_ajax') }}" method="POST" id="form-import-prodi"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Petunjuk Import</h5>
                    <ol>
                        <li>Download template Excel terlebih dahulu</li>
                        <li>Isi data sesuai dengan kolom yang tersedia</li>
                        <li>Pastikan format file adalah .xlsx</li>
                        <li>Maksimal ukuran file: 1MB</li>
                    </ol>
                    <a href="{{ asset('template_program_studi.xlsx') }}"
                        class="btn btn-sm btn-outline-primary">Download Template</a>
                </div>

                <div class="form-group mb-3">
                    <label for="file_prodi">File Excel</label>
                    <input type="file" name="file_prodi" id="file_prodi" class="form-control" accept=".xlsx" required>
                    <small id="error-file_prodi" class="error-text form-text text-danger"></small>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Kolom</th>
                                <th>Keterangan</th>
                                <th>Contoh</th>
                                <th>Wajib</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A</td>
                                <td>Nama Program Studi</td>
                                <td>Teknik Informatika</td>
                                <td><span class="badge bg-success">Ya</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Import Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#form-import-prodi").on('submit', function (e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.btn-primary').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
                },
                success: function (response) {
                    $('.btn-primary').prop('disabled', false).html('Import Data');
                    if (response.status) {
                        $('.modal').modal('hide');
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            if (window.dataProdi) {
                                window.dataProdi.ajax.reload(null, false);
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            title: 'Gagal',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function (xhr) {
                    $('.btn-primary').prop('disabled', false).html('Import Data');
                    var errors = xhr.responseJSON?.errors;
                    $('.error-text').text('');
                    if (errors) {
                        $.each(errors, function (prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengimport data',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>