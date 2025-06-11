<!-- resources/views/roles/admin/management-pengguna/import.blade.php -->

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Import Data Periode</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ url('/admin/management-periode-magang/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
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
                  <a href="{{ asset('template_periode.xlsx') }}" class="btn btn-sm btn-outline-primary" download>Download Template</a>
                </div>

                <div class="form-group mb-3">
                    <label for="file_periode">File Excel</label>
                    <input type="file" name="file_periode" id="file_periode" class="form-control" accept=".xlsx" required>
                    <small id="error-file_periode" class="error-text form-text text-danger"></small>
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
                                <td>Nama Periode</td>
                                <td>Semester Genap</td>
                                <td><span class="badge bg-success">Ya</span></td>
                            </tr>
                            <tr>
                                <td>B</td>
                                <td>Tanggal Mulai</td>
                                <td>2025-04-13</td>
                                <td><span class="badge bg-success">Ya</span></td>
                            </tr>
                            <tr>
                                <td>C</td>
                                <td>Tanggal Selesai</td>
                                <td>2025-05-13</td>
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
    $(document).ready(function() {
        $("#form-import").on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(form);
            
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.btn-primary').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
                },
                success: function(response) {
                    $('.btn-primary').prop('disabled', false).html('Import Data');
                    
                    if (response.status) {
                        $('.modal').modal('hide');
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        tablePeriode.ajax.reload(null, false);
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            title: 'Gagal',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    $('.btn-primary').prop('disabled', false).html('Import Data');
                    var errors = xhr.responseJSON.errors;
                    $('.error-text').text('');
                    $.each(errors, function(prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
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