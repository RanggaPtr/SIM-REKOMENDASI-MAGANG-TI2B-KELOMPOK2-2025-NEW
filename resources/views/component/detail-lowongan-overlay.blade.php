{{-- resources/views/component/detail-lowongan-overlay.blade.php --}}
<style>
    .skills-container-overlay {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem 0.3rem;
        line-height: 1.5rem;
        /* Explicit line-height for JS calculation */
    }

    .padding-left {
        padding-left: 2.7rem;
    }
</style>

<div class="row g-4 bg-transparent" style="width: 100%; height: 100%;">
    <!-- Kiri: Detail Lowongan -->
    <div class="col-md-9 bg-transparent pt-4 pe-0 ps-0">
        <div class="d-flex justify-content-between align-items-center bg-transparent padding-left">
            <h2 class="fw-bold mb-0 flex-grow-1 bg-transparent" style="font-size:2rem;">{{ $lowongan->judul }}</h2>
            <span class="text-dark bg-transparent fw-bold pe-4" style="font-size:1.3rem"><i
                    class="fa-solid fa-star text-warning bg-transparent"></i>
                {{ number_format($lowongan->perusahaan->rating, 1) }}</span>
        </div>
        <div class="d-flex flex-wrap gap-3 mb-4 text-dark bg-transparent padding-left" style="font-size:1rem;">
            <span class="bg-transparent">Di posting {{ $lowongan->selisih_hari }} hari yang lalu</span>
            <span class="bg-transparent">Rp. {{ number_format($lowongan->tunjangan, 0, ',', '.') }}/bulan</span>
            <span class="bg-transparent"><i class="fa-solid fa-location-dot"></i>
                {{ ucwords(strtolower($lowongan->perusahaan->lokasi->nama)) ?? '-' }}</span>
        </div>
        <div class="mb-3 bg-transparent text-dark padding-left">
            <p class="bg-transparent mb-0 pb-0">Deskripsi</p>
            <p class="bg-transparent ">{{ $lowongan->deskripsi }}</p>
        </div>
        <div class="bg-transparent skills-container-overlay pb-3 padding-left">
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
        <div class="w-100 mb-3" style="border-bottom: 1px solid #e0e0e0;"></div>
        <div class="mb-3 padding-left bg-transparent">
            <strong class="bg-translate mb-0 pb-0 bg-transparent" style="font-size: 1.2rem">Persyaratan:</strong>
            <p class="bg-transparent">{!! nl2br(e($lowongan->persyaratan)) !!}</p>
        </div>
        <div class="w-100 mb-3" style="border-bottom: 1px solid #e0e0e0;"></div>
        <div class="mb-3 padding-left bg-transparent">
            <strong class="bg-translate mb-0 pb-0 bg-transparent" style="font-size: 1.2rem">Riwayat Perusahaan:</strong>
            {{-- <div class="w-auto border border-primary h-auto p-3 bg-dark rounded" style="margin-right:2.7rem">

            </div> --}}
        </div>
    </div>

    <!-- Kanan: Info Perusahaan & Review -->
    <div class="col-md-3 bg-transparent pt-4"
        style="padding-left: 1.7rem; padding-right:1.2rem; height:100%; border-left: 1px solid #e0e0e0;">
        <div class="bg-transparent mb-3">
            {{-- Tombol Ajukan langsung ke store --}}
            @if (Auth::user()->role === 'mahasiswa')
                @php
                    // Cek apakah sudah pernah mengajukan atau sudah ada magang disetujui/selesai
                    $mahasiswa = \App\Models\MahasiswaModel::where('user_id', Auth::id())->first();
                    $sudahAjukan = false;
                    $sudahMagang = false;
                    $isBookmarked = false;
                    if ($mahasiswa) {
                        $sudahAjukan = \App\Models\PengajuanMagangModel::where([
                            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                            'lowongan_id' => $lowongan->lowongan_id
                        ])->exists();
                        $sudahMagang = \App\Models\PengajuanMagangModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                            ->whereIn('status', ['disetujui', 'selesai'])
                            ->exists();
                        $isBookmarked = \App\Models\BookmarkModel::where([
                            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                            'lowongan_id' => $lowongan->lowongan_id
                        ])->exists();
                    }
                @endphp
                <form action="{{ route('mahasiswa.pengajuan-magang.store') }}" method="POST"
                    class="d-inline bg-transparent">
                    @csrf
                    <input type="hidden" name="lowongan_id" value="{{ $lowongan->lowongan_id }}">
                    <button type="submit" class="mt-2 btn btn-primary text-white me-2 mb-1 w-100"
                        @if ($sudahAjukan || $sudahMagang) disabled @endif>
                        @if ($sudahAjukan)
                            Sudah Diajukan
                        @elseif ($sudahMagang)
                            Tidak Bisa Ajukan
                        @else
                            Ajukan
                        @endif
                    </button>
                </form>
            @else
                <div class="alert alert-warning me-2 mt-2 bg-transparent">
                    Lengkapi profil mahasiswa terlebih dahulu sebelum mengajukan magang
                </div>
            @endif

            {{-- Bookmark Button --}}
            <button type="button"
                class="mt-2 btn btn-outline-primary me-2 mb-4 w-100 d-flex align-items-center justify-content-center"
                style="gap: 0.5rem;">
                <i class="fa{{ $isBookmarked ? '-solid' : '-regular' }} fa-bookmark bookmark-icon bg-transparent"
                   data-id="{{ $lowongan->lowongan_id }}"
                   style="color:{{ $isBookmarked ? '#ffc107' : '' }}; font-size:1.2rem;"></i>
                <span class="bg-transparent">Bookmark</span>
            </button>
            <div class="mb-3" style="border-bottom: 1px solid #e0e0e0;margin-left: -1.7rem; margin-right:-1.8rem;">
            </div>
            <div class="d-flex align-items-center mb-4 bg-transparent">
                @if ($lowongan->perusahaan->logo)
                    <img src="{{ asset($lowongan->perusahaan->logo) }}" alt="Logo" class="me-2 bg-transparent"
                        style="height:48px;width:48px;object-fit:cover;border-radius:8px;">
                @endif
                <div class="bg-transparent">
                    <div class="fw-bold bg-transparent">{{ $lowongan->perusahaan->nama }}</div>
                    <div class="text-muted bg-transparent" style="font-size:0.95rem;">
                        {{ $lowongan->perusahaan->bidang_industri }}</div>
                </div>
            </div>
            <div class="mb-2 bg-transparent">
                <strong class="bg-transparent">Alamat:</strong> <br>{{ $lowongan->perusahaan->alamat }}
            </div>
            <div class="mb-2 bg-transparent">
                <strong class="bg-transparent">Kontak:</strong> <br>{{ $lowongan->perusahaan->kontak }}
            </div>
            <div class="mb-2 bg-transparent">
                <strong class="bg-transparent">Skema:</strong> <br>{{ $lowongan->skema->nama ?? '-' }}
            </div>
            <div class="mb-2 bg-transparent">
                <strong class="bg-transparent">Periode:</strong> <br>{{ $lowongan->periode->nama ?? '-' }}
            </div>
        </div>
        <div class="mb-3" style="border-bottom: 1px solid #e0e0e0;margin-left: -1.7rem; margin-right:-1.8rem;"></div>
        <!-- Riwayat/Review Perusahaan -->
        <div class="bg-white rounded-3 bg-transparent">
            <div class="fw-bold mb-2 bg-transparent"><i class="fa-regular fa-circle-check bg-transparent"></i> Rating
                Admin | <i class="fa-solid fa-star text-warning bg-transparent"></i>
                {{ $lowongan->perusahaan->rating }}</div>
            @if ($lowongan->perusahaan->rating && $lowongan->perusahaan->deskripsi_rating != null)
                <div class="bg-transparent text-dark">{{ $lowongan->perusahaan->deskripsi_rating }}</div>
            @else
                <div class="text-muted">Belum ada riwayat/testimoni magang.</div>
            @endif
        </div>
    </div>
</div>
