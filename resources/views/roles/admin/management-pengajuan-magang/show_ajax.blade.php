<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Pengajuan Magang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Informasi!</h5>
                Berikut adalah detail data pengajuan magang:
            </div>
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">ID Pengajuan:</th>
                    <td class="col-9">{{ $pengajuan->pengajuan_id }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Mahasiswa:</th>
                    <td class="col-9">{{ $pengajuan->mahasiswa->user->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">NIM:</th>
                    <td class="col-9">{{ $pengajuan->mahasiswa->nim ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Program Studi:</th>
                    <td class="col-9">{{ $pengajuan->mahasiswa->programStudi->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Perusahaan:</th>
                    <td class="col-9">{{ $pengajuan->lowongan->perusahaan->nama_perusahaan ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Posisi:</th>
                    <td class="col-9">{{ $pengajuan->lowongan->posisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi:</th>
                    <td class="col-9">{{ $pengajuan->lowongan->deskripsi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Dosen Pembimbing:</th>
                    <td class="col-9">{{ $pengajuan->dosen->user->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">NIP Dosen:</th>
                    <td class="col-9">{{ $pengajuan->dosen->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Periode Magang:</th>
                    <td class="col-9">{{ $pengajuan->periode->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Mulai:</th>
                    <td class="col-9">{{ $pengajuan->periode->tanggal_mulai ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Selesai:</th>
                    <td class="col-9">{{ $pengajuan->periode->tanggal_selesai ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Status:</th>
                    <td class="col-9">
                        @php
                            $badgeClass = match($pengajuan->status) {
                                'pending' => 'badge-warning',
                                'approved' => 'badge-success', 
                                'rejected' => 'badge-danger',
                                'completed' => 'badge-info',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($pengajuan->status) }}</span>
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Rating Feedback:</th>
                    <td class="col-9">
                        @if($pengajuan->feedback_rating)
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $pengajuan->feedback_rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                            ({{ $pengajuan->feedback_rating }}/5)
                        @else
                            <span class="text-muted">Belum ada rating</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi Feedback:</th>
                    <td class="col-9">{{ $pengajuan->feedback_deskripsi ?? 'Belum ada feedback' }}</td>
                </tr>