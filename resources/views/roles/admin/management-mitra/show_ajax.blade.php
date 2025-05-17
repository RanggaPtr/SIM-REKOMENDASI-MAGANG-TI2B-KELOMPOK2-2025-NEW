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
                    Data yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/admin/management-mitra') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Data Perusahaan Mitra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th class="col-3">ID</th>
                        <td class="col-9">{{ $perusahaan->perusahaan_id }}</td>
                    </tr>
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
                        <td>{{$perusahaan->deskripsi}}</td>
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
                        <th>Bidang Industri</th>
                        <td>{{ $perusahaan->bidang_industri }}</td>
                    </tr>
                    <tr>
                        <th>Rating</th>
                        <td>
                            @if ($perusahaan->rating)
                                <span class="badge bg-info">{{ $perusahaan->rating }} / 5</span>
                            @else
                                <em>(Belum ada rating)</em>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi Rating</th>
                        <td>{{ $perusahaan->deskripsi_rating }}</td>
                    </tr>

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty