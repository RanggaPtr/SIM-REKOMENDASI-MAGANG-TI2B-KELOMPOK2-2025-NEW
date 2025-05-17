@empty($perusahaan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/admin/management-mitra') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/admin/management-mitra/' . $perusahaan->perusahaan_id . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div id="modal-master" class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Perusahaan Mitra</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Perusahaan</label>
                                <input type="text" name="nama" class="form-control" value="{{ $perusahaan->nama }}">
                            </div>
                            <div class="form-group">
                                <label for="ringkasan">Ringkasan</label>
                                <textarea name="ringkasan" id="ringkasan"
                                    class="form-control">{{ old('ringkasan', $perusahaan->ringkasan ?? '') }}</textarea>
                                <small id="error-ringkasan" class="error-text form-text text-danger"></small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi"
                                    class="form-control">{{ old('deskripsi', $perusahaan->deskripsi ?? '') }}</textarea>
                                <small id="error-deskripsi" class="error-text form-text text-danger"></small>                      </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control">{{ $perusahaan->alamat }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Kontak</label>
                                <input type="text" name="kontak" class="form-control" value="{{ $perusahaan->kontak }}">
                            </div>
                            <div class="form-group">
                                <label>Wilayah</label>
                                <select name="wilayah_id" class="form-control">
                                    @foreach($wilayah as $w)
                                        <option value="{{ $w->wilayah_id }}" {{ $perusahaan->wilayah_id == $w->wilayah_id ? 'selected' : '' }}>
                                            {{ $w->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Logo (Opsional)</label>
                                <input type="file" name="logo" class="form-control">
                                @if($perusahaan->logo)
                                    <img src="{{ asset('uploads/logo_perusahaan/' . $perusahaan->logo) }}" height="50">
                                @endif              </div>
                            <div class="form-group">
                                <label for="bidang_industri">Bidang Industri</label>
                                <input type="text" name="bidang_industri" id="bidang_industri" class="form-control" maxlength="100"
                                    value="{{ old('bidang_industri', $perusahaan->bidang_industri) }}">
                                <small id="error-bidang_industri" class="error-text form-text text-danger"></small>
                            </div>

                            <div class="form-group">
                                <label for="rating">Rating (0-5)</label>
                                <input type="number" name="rating" id="rating" class="form-control" min="0" max="5" step="0.1"
                                    value="{{ old('rating', $perusahaan->rating) }}">
                                <small id="error-rating" class="error-text form-text text-danger"></small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi_rating">Deskripsi Rating</label>
                                <textarea name="deskripsi_rating" id="deskripsi_rating"
                                    class="form-control">{{ old('deskripsi_rating', $perusahaan->deskripsi_rating) }}</textarea>
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
                    $('#form-edit').submit(function (e) {
                        e.preventDefault();

                        var formData = new FormData(this);
                        $.ajax({
                            type: 'POST',
                            url: $(this).attr('action'),
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status) {
                                    alert(response.message);
                                    window.location.href = "{{ url('/admin/management-mitra') }}";
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function (xhr) {
                                alert('Terjadi kesalahan saat mengirim data.');
                            }
                        });
                    });
                });
            </script>
@endempty