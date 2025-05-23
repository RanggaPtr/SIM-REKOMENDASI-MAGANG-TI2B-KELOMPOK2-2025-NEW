<form action="{{ url('/mahasiswa/pengajuan-magang/' . $pengajuan->pengajuan_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    Apakah Anda yakin ingin menghapus pengajuan magang berikut?
                </div>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>Lowongan:</th>
                        <td>{{ $pengajuan->lowongan->judul }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing:</th>
                        <td>{{ $pengajuan->dosen->user->nama }}</td>
                    </tr>
                    <tr>
                        <th>Periode:</th>
                        <td>{{ $pengajuan->periode->nama }}</td>
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
        $("#form-delete").on('submit', function (e) {
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
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
                }
            });
        });
    });
</script>