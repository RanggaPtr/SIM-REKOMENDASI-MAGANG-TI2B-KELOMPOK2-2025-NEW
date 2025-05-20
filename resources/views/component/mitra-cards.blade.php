<div class="row g-4 justify-content-center" style="background: transparent;">
    @forelse($companies as $company)
        <div class="col-md-4" style="background: transparent;">
                <div class="shadow-sm card-body bg-primary" style="border-radius: 1rem; height: 50vh">
                    <div class="d-flex align-items-start justify-content-between mb-3" style="background: transparent;">
                        <div>
                            <h5 class="card-title fw-bold text-dark mb-1" style="background: transparent;">{{ $company->nama }}</h5>
                        </div>
                        <img src="{{ url($company->logo ?? '/images/default-logo.png') }}"
                            alt="Logo {{ $company->nama }}" class="ms-2 rounded"
                            style="width:48px; height:48px; object-fit:contain; background:#f8f9fa;">
                    </div>
                    <div class="text-secondary mb-2" style="font-size: 0.98rem; background: transparent;">
                        {{ $company->lokasi->nama ?? '' }}
                    </div>
                    <p class="card-text text-dark" style="font-size: 0.97rem; background: transparent;">
                        {{ $company->bidang->nama ?? '' }}
                        {{ \Illuminate\Support\Str::limit($company->deskripsi, 90) }}
                    </p>
                </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted">Belum ada data perusahaan.</div>
    @endforelse
</div>

<div class="d-flex justify-content-end mt-3" style="background: transparent;">
    {{ $companies->withQueryString()->links('pagination::bootstrap-5') }}
</div>
