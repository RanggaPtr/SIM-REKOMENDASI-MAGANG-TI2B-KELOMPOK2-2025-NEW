<form action="{{ url('/mahasiswa/pengajuan-magang/' . $pengajuan->pengajuan_id) }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Pengajuan Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    Apakah Anda yakin ingin menghapus pengajuan magang ini?
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>ID Pengajuan</th>
                        <td>{{ $pengajuan->pengajuan_id }}</td>
                    </tr>
                    <tr>
                        <th>Lowongan</th>
                        <td>{{ $pengajuan->lowongan->judul }}</td>
                    </tr>
                    <tr>
                        <th>Perusahaan</th>
                        <td>{{ $pengajuan->lowongan->perusahaan->nama }}</td>
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
    $(document).ready(function() {
        $("#form-delete").on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: form.action,
                type: 'POST', // Karena Laravel tidak bisa langsung menggunakan DELETE
                data: $(form).serialize() + '&_method=DELETE',
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
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data', 'error');
                }
            });
        });
    });
</script>