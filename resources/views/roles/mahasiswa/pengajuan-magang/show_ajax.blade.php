<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Pengajuan Magang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <th class="col-3">ID Pengajuan</th>
                    <td>{{ $pengajuan->pengajuan_id }}</td>
                </tr>
                <tr>
                    <th>Lowongan Magang</th>
                    <td>{{ $pengajuan->lowongan->judul }} ({{ $pengajuan->lowongan->perusahaan->nama_perusahaan }})</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing</th>
                    <td>{{ $pengajuan->dosen->user->nama }}</td>
                </tr>
                <tr>
                    <th>Periode Magang</th>
                    <td>{{ $pengajuan->periode->nama }} ({{ \Carbon\Carbon::parse($pengajuan->periode->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($pengajuan->periode->tanggal_selesai)->format('d/m/Y') }})</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @php
                            $badgeClass = [
                                'pending' => 'bg-warning',
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger'
                            ][$pengajuan->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($pengajuan->status) }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>