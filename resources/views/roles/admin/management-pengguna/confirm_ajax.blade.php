@empty($user)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Data user tidak ditemukan.</div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/admin/management-pengguna/' . $user->user_id . '/delete_ajax') }}" method="POST" id="form-delete-user">
        @csrf
        @method('DELETE')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        Apakah Anda yakin ingin menghapus data user berikut?
                    </div>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Role:</th>
                            <td>{{ ucfirst($user->role) }}</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Nama:</th>
                            <td>{{ $user->nama }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $("#form-delete-user").on('submit', function (e) {
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
                                text: 'Data berhasil dihapus',
                                confirmButtonText: 'OK'
                            });
                            dataUser.ajax.reload(null, false);
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
@endempty