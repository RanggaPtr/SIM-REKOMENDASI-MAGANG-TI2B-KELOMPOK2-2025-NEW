<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Pengajuan Magang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Pengajuan:</strong> {{ $pengajuan->pengajuan_id }}</p>
                    <p><strong>Nama Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? '-' }}</p>
                    <p><strong>Perusahaan:</strong> {{ $pengajuan->lowongan->perusahaan->nama ?? '-' }}</p>
                    <p><strong>Judul Lowongan:</strong> {{ $pengajuan->lowongan->judul ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Dosen Pembimbing:</strong> {{ $pengajuan->dosen->user->name ?? 'Belum Ditugaskan' }}</p>
                    <p><strong>Periode:</strong> {{ $pengajuan->periode->nama ?? '-' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $pengajuan->status == 'approved' ? 'success' : ($pengajuan->status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($pengajuan->status) }}
                        </span>
                    </p>
                    <p><strong>Feedback Rating:</strong> {{ $pengajuan->feedback_rating ?? '-' }}</p>
                </div>
            </div>
            @if($pengajuan->feedback_deskripsi)
            <div class="row mt-3">
                <div class="col-12">
                    <p><strong>Feedback Deskripsi:</strong></p>
                    <div class="alert alert-info">
                        {{ $pengajuan->feedback_deskripsi }}
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>