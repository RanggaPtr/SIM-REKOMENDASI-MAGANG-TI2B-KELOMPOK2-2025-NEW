<div class="row g-4 justify-content-center bg-transparent">
    @forelse($companies as $company)
        <div class="col-md-4 rounded-6">
            <div class="bg-primary p-4 shadow-sm" style="border-radius: 2rem;">
                <div class="p-4 bg-primary" style="border-radius: 1.5rem; min-height: 320px; box-shadow: 0 2px 8px #0001; border: 4px solid #fff;">
                    <div class="d-flex align-items-start justify-content-between mb-3 bg-transparent">
                        <div class="bg-transparent gap-1 d-flex flex-column">
                            <h2 class="fw-bold text-white mb-0 bg-transparent" style="font-size: 2rem; line-height: 1.1;">
                                {{ strtoupper($company->nama) }}
                            </h2>
                            <div class="mb-2 bg-transparent" style="color: #fffbe0; font-size: 0.9rem;">
                                {{ $company->lokasi->nama ?? '' }}
                            </div>
                        </div>
                        <img src="{{ url($company->logo ?? '/images/default_logo1.png') }}" alt="Logo {{ $company->nama }}"
                            class="ms-2 rounded-circle border border-2 border-white bg-transparent"
                            style="width:70px; height:70px; object-fit:cover; background: #fff;">
                    </div>
                    <div class="text-white bg-transparent" style="font-size: 1.1rem;">
                        {{ \Illuminate\Support\Str::limit($company->deskripsi, 180) }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted">Belum ada data perusahaan.</div>
    @endforelse
</div>

<div class="d-flex justify-content-end mt-3 bg-transparent">
    {{ $companies->withQueryString()->links('pagination::bootstrap-5') }}
</div>
