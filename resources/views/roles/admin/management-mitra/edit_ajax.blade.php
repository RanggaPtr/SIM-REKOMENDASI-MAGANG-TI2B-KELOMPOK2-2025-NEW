@empty($perusahaan)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Data tidak ditemukan</div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/admin/management-mitra/' . $perusahaan->perusahaan_id . '/update_ajax') }}" method="POST"
        id="form-edit-perusahaan" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Perusahaan Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Logo Perusahaan</label>
                        <input type="file" name="logo" class="form-control">
                        @if($perusahaan->logo)
                            <img src="{{ asset($perusahaan->logo) }}" alt="Logo Perusahaan" height="60" class="mt-2">
                        @endif              
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Perusahaan</label>
                        <input type="text" name="nama" class="form-control" value="{{ $perusahaan->nama }}">
                        <small id="error-nama" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Ringkasan</label>
                        <textarea name="ringkasan"
                            class="form-control">{{ old('ringkasan', $perusahaan->ringkasan) }}</textarea>
                        <small id="error-ringkasan" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi"
                            class="form-control">{{ old('deskripsi', $perusahaan->deskripsi) }}</textarea>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control">{{ $perusahaan->alamat }}</textarea>
                        <small id="error-alamat" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Kontak</label>
                        <input type="text" name="kontak" class="form-control" value="{{ $perusahaan->kontak }}">
                        <small id="error-kontak" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Wilayah</label>
                        <select name="wilayah_id" class="form-control">
                            @foreach($wilayah as $w)
                                <option value="{{ $w->wilayah_id }}" {{ $perusahaan->wilayah_id == $w->wilayah_id ? 'selected' : '' }}>{{ $w->nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-wilayah_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Bidang Industri</label>
                        <input type="text" name="bidang_industri" class="form-control" maxlength="100"
                            value="{{ old('bidang_industri', $perusahaan->bidang_industri) }}">
                        <small id="error-bidang_industri" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Rating (0-5)</label>
                        <input type="number" name="rating" class="form-control" min="0" max="5" step="0.1"
                            value="{{ old('rating', $perusahaan->rating) }}">
                        <small id="error-rating" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Deskripsi Rating</label>
                        <textarea name="deskripsi_rating"
                            class="form-control">{{ old('deskripsi_rating', $perusahaan->deskripsi_rating) }}</textarea>
                        <small id="error-deskripsi_rating" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-edit-perusahaan").on('submit', function (e) {
                e.preventDefault();
                let form = this;
                let formData = new FormData(form);

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
                                text: 'Data berhasil diupdate',
                                confirmButtonText: 'OK'
                            });
                            tablePerusahaan.ajax.reload(null, false);
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
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