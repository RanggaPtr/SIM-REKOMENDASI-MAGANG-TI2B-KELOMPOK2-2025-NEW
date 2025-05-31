@push('styles')
    <style>
        .skills-multiline-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-height: 3.2em;
            /* adjust if needed for your font-size */
        }
    </style>
@endpush

<div class="row g-3">
    @foreach ($lowongans as $idx => $lowongan)
        @php
            // There are 6 card colors, cycle through them
            $cardBg = 'card-' . (($idx % 6) + 1);
        @endphp
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-transparent rounded-4" style="height: 48vh;">
                <div class="card-body bg-white rounded-3 p-3 d-flex flex-column justify-content-between h-100">
                    <div class="bg-{{ $cardBg }} px-3 pt-3 pb2 mb-2 rounded-2 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-transparent">
                            <span class="badge bg-white text-dark">{{ $lowongan->tanggal_buka->format('d M, Y') }}</span>
                            <i class="fa-regular fa-bookmark bg-transparent"
                                style="font-size:1.2rem; transition: color 0.2s, transform 0.2s; margin-right:2%;cursor:pointer;"></i>
                        </div>
                        <div class="text-dark bg-transparent">{{ $lowongan->perusahaan->nama ?? '-' }}</div>
                        <h5 class="card-title bg-transparent fw-bold fs-4" style="margin-bottom:0rem">{{ $lowongan->judul }}</h5>
                        <h9 class="mb-auto bg-transparent" style="font-size: 0.8rem">
                            <i class="fa-solid fa-star text-warning bg-transparent"></i>
                            {{ number_format(($lowongan->perusahaan->calculated_rating ?? 0) / 20, 1) }}
                        </h9>
                        <div class="bg-transparent skills-container mb-3">
                            @foreach ($lowongan->lowonganKompetensi as $lk)
                                @if ($lk->kompetensi)
                                    <span
                                        class="badge bg-transparent text-dark border border-dark rounded-pill mb-1">{{ $lk->kompetensi->nama }}</span>
                                @endif
                            @endforeach
                            @foreach ($lowongan->lowonganKeahlian as $lk)
                                @if ($lk->keahlian)
                                    <span
                                        class="badge bg-transparent text-dark border border-dark rounded-pill mb-1">{{ $lk->keahlian->nama }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="row bg-transparent">
                        <div class="d-flex justify-content-between align-items-center w-100 bg-transparent">
                            <div class="bg-transparent">
                                <div class="bg-transparent">
                                    <span class="fw-bold bg-transparent">Rp.
                                        {{ number_format($lowongan->tunjangan ?? 0, 0, ',', '.') }}/Bulan
                                    </span>
                                </div>
                                <div class="text-muted small" style="font-size: 0.85rem; background: transparent;">
                                    {{ $lowongan->perusahaan && $lowongan->perusahaan->lokasi
                                        ? ucwords(strtolower($lowongan->perusahaan->lokasi->nama))
                                        : '-' }}
                                </div>
                            </div>
                            <a href="#ONGOING" class="btn btn-dark">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
