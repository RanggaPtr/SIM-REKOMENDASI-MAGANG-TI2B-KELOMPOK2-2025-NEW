@push('styles')
    <style>
        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem 0.6rem;
            line-height: 1.5rem;
            /* Explicit line-height for JS calculation */
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
                    <div class="bg-{{ $cardBg }} px-3 pt-3 pb2 mb-2 rounded-2 d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 bg-transparent">
                            <span class="badge bg-white text-dark">{{ $lowongan->tanggal_buka->format('d M, Y') }}</span>
                            <i class="fa-regular fa-bookmark bg-transparent"
                                style="font-size:1.2rem; transition: color 0.2s, transform 0.2s; margin-right:2%;cursor:pointer;"></i>
                        </div>
                        <div class="text-dark bg-transparent">{{ $lowongan->perusahaan->nama ?? '-' }}</div>
                        <h5 class="card-title bg-transparent fw-bold fs-4" style="margin-bottom:0rem">
                            {{ $lowongan->judul }}</h5>
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

                            <div class="d-flex">
                                {{-- Tombol Ajukan langsung ke store --}}
                                @if (Auth::user()->role === 'mahasiswa')
                                    <form action="{{ route('mahasiswa.pengajuan-magang.store') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="lowongan_id" value="{{ $lowongan->lowongan_id }}">
                                        <button type="submit" class="btn btn-primary me-2">Ajukan</button>
                                    </form>
                                @else
                                    <div class="alert alert-warning me-2">
                                        Lengkapi profil mahasiswa terlebih dahulu sebelum mengajukan magang
                                    </div>
                                @endif

                                {{-- Tombol Detail --}}
                                <a href="#ongoing" class="btn btn-dark">Detail</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
    <script>
        window.addEventListener("load", function() {
            const containers = document.querySelectorAll(".skills-container");

            containers.forEach(container => {
                const maxLines = 2;
                const lineHeight = parseFloat(getComputedStyle(container).lineHeight);
                const maxHeight = lineHeight * maxLines;

                let badges = Array.from(container.children);
                let baseline = null;

                for (let i = 0; i < badges.length; i++) {
                    let badge = badges[i];

                    // Ambil posisi vertikal relatif terhadap container
                    let top = badge.offsetTop;

                    // Simpan top pertama sebagai baseline (baris pertama)
                    if (baseline === null) {
                        baseline = top;
                    }

                    // Jika badge sudah melewati 2 baris, sembunyikan sisanya
                    if (top - baseline >= maxHeight) {
                        // Hide this and the rest
                        for (let j = i; j < badges.length; j++) {
                            badges[j].style.display = "none";
                        }
                        break;
                    }
                }
            });
        });
    </script>
@endpush
