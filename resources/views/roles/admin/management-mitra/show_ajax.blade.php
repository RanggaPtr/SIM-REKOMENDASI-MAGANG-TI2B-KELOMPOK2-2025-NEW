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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Data Perusahaan Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <div>
                        <label>Logo Perusahaan:</label><br>
                        @if($perusahaan->logo)
                            <img src="{{ asset($perusahaan->logo) }}" alt="Logo Perusahaan" height="60">
                        @endif             
                    </div>
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
                <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty