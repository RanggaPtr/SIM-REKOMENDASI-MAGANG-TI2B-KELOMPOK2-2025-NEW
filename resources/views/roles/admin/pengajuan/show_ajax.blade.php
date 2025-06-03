<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Detail Pengajuan Magang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <p><strong>ID Pengajuan:</strong> {{ $pengajuan->pengajuan_id }}</p>
        <p><strong>Nama Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? '-' }}</p>
        <p><strong>Perusahaan:</strong> {{ $pengajuan->lowongan->perusahaan->nama ?? '-' }}</p>
        <p><strong>Judul Lowongan:</strong> {{ $pengajuan->lowongan->judul ?? '-' }}</p>
        <p><strong>Dosen Pembimbing:</strong> {{ $pengajuan->dosen->user->name ?? 'Belum Ditugaskan' }}</p>
        <p><strong>Periode:</strong> {{ $pengajuan->periode->nama ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($pengajuan->status) }}</p>
        <p><strong>Feedback Rating:</strong> {{ $pengajuan->feedback_rating ?? '-' }}</p>
        <p><strong>Feedback Deskripsi:</strong> {{ $pengajuan->feedback_deskripsi ?? '-' }}</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
</div>