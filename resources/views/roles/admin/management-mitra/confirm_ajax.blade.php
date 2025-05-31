@empty($perusahaan)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Data perusahaan tidak ditemukan.</div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/admin/management-mitra/' . $perusahaan->perusahaan_id . '/delete_ajax') }}" method="POST"
        id="form-delete-perusahaan">
        @csrf
        @method('DELETE')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Perusahaan Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        Apakah Anda yakin ingin menghapus data perusahaan berikut?
                    </div>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $perusahaan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Ringkasan</th>
                            <td>{{ $perusahaan->ringkasan }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $perusahaan->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th>Bidang Industri</th>
                            <td>{{ $perusahaan->bidang_industri }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $perusahaan->alamat }}</td>
                        </tr>
                        <tr>
                            <th>Wilayah</th>
                            <td>{{ $perusahaan->lokasi->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kontak</th>
                            <td>{{ $perusahaan->kontak }}</td>
                        </tr>
                        <tr>
                            <th>Rating</th>
                            <td>{{ $perusahaan->rating }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi Rating</th>
                            <td>{{ $perusahaan->deskripsi_rating }}</td>
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
            $("#form-delete-perusahaan").on('submit', function (e) {
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
                            if (window.dataPerusahaan) window.dataPerusahaan.ajax.reload(null, false);
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField ?? {}, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON?.errors ?? {};
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