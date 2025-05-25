@push('styles')
    <style>
        .bookmark-hover:hover {
            color: #EEB521 !important;
            font-weight: bold;
            cursor: pointer;
            transform: scale(1.2);
            transition: color 0.2s, transform 0.2s;
            cursor: pointer;
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
            <div class="card shadow-sm border-0 bg-transparent rounded-4" style="height: 40vh;">
                <div class="card-body bg-white rounded-4 p-3 d-flex flex-column justify-content-between h-100">
                    <div class="bg-{{ $cardBg }} p-3 mb-2 rounded-2 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-transparent">
                            <span class="badge bg-white text-dark">{{ $lowongan->tanggal_buka->format('d M, Y') }}</span>
                            <i class="fa-regular fa-bookmark bookmark-hover bg-transparent"
                                style="font-size:1.2rem; transition: color 0.2s, transform 0.2s; margin-right:2%"></i>
                        </div>
                        <div class="mb-1 text-dark bg-transparent">{{ $lowongan->perusahaan->nama ?? '-' }}</div>
                        <h5 class="card-title bg-transparent">{{ $lowongan->judul }}</h5>
                        <div class="mb-2 bg-transparent">
                            @foreach (explode(',', $lowongan->bidang_keahlian) as $skill)
                                <span class="badge bg-secondary">{{ trim($skill) }}</span>
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
