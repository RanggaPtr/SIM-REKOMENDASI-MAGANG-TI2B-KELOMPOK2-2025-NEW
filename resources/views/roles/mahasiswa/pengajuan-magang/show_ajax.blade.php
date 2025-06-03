<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Pengajuan Magang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID Pengajuan</th>
                    <td>{{ $pengajuan->pengajuan_id }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($pengajuan->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($pengajuan->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-warning">DIajukan</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Mahasiswa</th>
                    <td>{{ $pengajuan->mahasiswa->user->nama }} ({{ $pengajuan->mahasiswa->nim }})</td>
                </tr>
                <tr>
                    <th>Lowongan</th>
                    <td>{{ $pengajuan->lowongan->judul }} - {{ $pengajuan->lowongan->perusahaan->nama }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <td>{{ $pengajuan->created_at }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>