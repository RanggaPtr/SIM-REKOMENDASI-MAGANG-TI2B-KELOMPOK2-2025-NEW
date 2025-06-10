<div class="modal-dialog modal-lg">
    <div class="modal-content shadow-xl border-0 rounded-4 overflow-hidden">
        <div class="modal-header position-relative" style="background: linear-gradient(135deg, #e7e8ec 0%, #edeaf0 100%); padding: 1.5rem;">
            <div class="d-flex align-items-center">
                <div class="bg-bg-opacity-20 rounded-circle p-2 me-3">
                    <i class="bi bi-file-earmark-text-fill text-white fs-4"></i>
                </div>
                <div>
                    <h4 class="modal-title text-black mb-1 fw-bold">Detail Pengajuan Magang</h4>
                    <p class="text-black-50 mb-0 small">Informasi lengkap pengajuan internship</p>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
        </div>

        <div class="modal-body p-0">
            <div class="px-4 pt-4 pb-2">
                <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #06111b 0%, #030609 100%);">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1 text-dark fw-semibold">
                                    <i class="bi bi-hash text-primary me-2"></i>{{ $pengajuan->pengajuan_id }}
                                </h6>
                                <p class="mb-0 text-muted small">{{ $pengajuan->mahasiswa->user->nama ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                @php
                                    $statusConfig = match($pengajuan->status) {
                                        'diterima', 'approved' => ['color' => 'success', 'icon' => 'bi-check-circle-fill'],
                                        'ditolak', 'rejected' => ['color' => 'danger', 'icon' => 'bi-x-circle-fill'],
                                        default => ['color' => 'warning', 'icon' => 'bi-clock-fill'],
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusConfig['color'] }} px-3 py-2 rounded-pill fs-6">
                                    <i class="{{ $statusConfig['icon'] }} me-1"></i>
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 pb-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                            <div class="card-header bg-transparent border-0 pb-2">
                                <h6 class="mb-0 text-primary fw-semibold">
                                    <i class="bi bi-building-fill me-2"></i>Informasi Perusahaan
                                </h6>
                            </div>
                            <div class="card-body pt-2">
                                <div class="mb-3">
                                    <label class="small text-muted fw-medium">Nama Perusahaan</label>
                                    <p class="mb-0 fw-semibold">{{ $pengajuan->lowongan->perusahaan->nama ?? '-' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted fw-medium">Posisi Magang</label>
                                    <p class="mb-0">{{ $pengajuan->lowongan->judul ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="small text-muted fw-medium">Periode</label>
                                    <p class="mb-0">
                                        <i class="bi bi-calendar3 text-primary me-1"></i>
                                        {{ $pengajuan->lowongan->periode->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                            <div class="card-header bg-transparent border-0 pb-2">
                                <h6 class="mb-0 text-success fw-semibold">
                                    <i class="bi bi-mortarboard-fill me-2"></i>Informasi Akademik & Kualifikasi
                                </h6>
                            </div>
                            <div class="card-body pt-2">
                                <div class="mb-3">
                                    <label class="small text-muted fw-medium">Dosen Pembimbing</label>
                                    <p class="mb-0 fw-semibold">
                                        @if($pengajuan->dosen->user->nama ?? null)
                                            <i class="bi bi-person-check-fill text-success me-1"></i>
                                            {{ $pengajuan->dosen->user->nama }}
                                        @else
                                            <i class="bi bi-person-dash text-warning me-1"></i>
                                            <span class="text-warning">Belum Ditugaskan</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted fw-medium">Keahlian</label>
                                    <p class="mb-0">
                                        @if($pengajuan->mahasiswa->mahasiswaKeahlian->count() > 0)
                                            @foreach($pengajuan->mahasiswa->mahasiswaKeahlian as $keahlian)
                                                <span class="badge bg-info me-1 mb-1">{{ $keahlian->keahlian->nama ?? '-' }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada keahlian</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted fw-medium">Kompetensi</label>
                                    <p class="mb-0">
                                        @if($pengajuan->mahasiswa->mahasiswaKompetensi->count() > 0)
                                            @foreach($pengajuan->mahasiswa->mahasiswaKompetensi as $kompetensi)
                                                <span class="badge bg-primary me-1 mb-1">{{ $kompetensi->kompetensi->nama ?? '-' }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Tidak ada kompetensi</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="small text-muted fw-medium">File CV</label>
                                    <p class="mb-0">
                                        @if($pengajuan->mahasiswa->file_cv)
                                            <a href="{{ Storage::url($pengajuan->mahasiswa->file_cv) }}" class="text-primary" target="_blank">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>Unduh CV
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada CV</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($pengajuan->feedback_deskripsi)
                    <div class="mt-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-gradient" style="background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-chat-quote-fill text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Feedback & Catatan</h6>
                                        <small class="text-muted">Komentar dari pembimbing atau perusahaan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="position-relative">
                                    <i class="bi bi-quote text-primary position-absolute" style="font-size: 3rem; opacity: 0.1; top: -10px; left: -5px;"></i>
                                    <blockquote class="blockquote mb-0 position-relative ps-4">
                                        <p class="mb-0 text-dark lh-lg fst-italic">{{ $pengajuan->feedback_deskripsi }}</p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="modal-footer bg-light border-0 px-4 py-3">
            <div class="d-flex justify-content-between align-items-center w-100">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Data terakhir diperbarui: {{ now()->format('d M Y, H:i') }}
                </small>
                <button type="button" class="btn btn-outline-primary rounded-pill px-4 py-2 transition-all hover-lift"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>