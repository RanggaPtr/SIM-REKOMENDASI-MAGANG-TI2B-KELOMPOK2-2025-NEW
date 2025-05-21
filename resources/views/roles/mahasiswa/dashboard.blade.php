{{-- filepath: resources/views/roles/mahasiswa/dashboard.blade.php --}}
@extends('layouts.template')

@section('content')
    <div class="container-fluid bg-warning-subtle min-vh-100 py-4">
        <div class="row">
            {{-- Main Content --}}
            <div class="col-md-9">
                <div class="d-flex mb-3">
                    <input type="text" class="form-control me-2" placeholder="Search">
                    <select class="form-select w-auto me-2">
                        <option>Sort by: Newest</option>
                    </select>
                    <button class="btn btn-outline-secondary"><i class="bi bi-bookmark"></i></button>
                </div>
                <div class="row g-3">
                    @foreach ($lowongans as $lowongan)
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0" style="background: #f8f9fa;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span
                                            class="badge bg-light text-dark">{{ $lowongan->tanggal_buka->format('d M, Y') }}</span>
                                        <button class="btn btn-link p-0"><i class="bi bi-bookmark"></i></button>
                                    </div>
                                    <div class="mb-1 text-muted">{{ $lowongan->perusahaan->nama ?? '-' }}</div>
                                    <h5 class="card-title">{{ $lowongan->judul }}</h5>
                                    <div class="mb-2">
                                        @foreach (explode(',', $lowongan->bidang_keahlian) as $skill)
                                            <span class="badge bg-secondary">{{ trim($skill) }}</span>
                                        @endforeach
                                    </div>
                                    <div class="mb-2">
                                        <span class="fw-bold">Rp.
                                            {{ number_format($lowongan->tunjangan ?? 0, 0, ',', '.') }}/Bulan</span>
                                    </div>
                                    <div class="mb-2 text-muted">{{ $lowongan->perusahaan->kota ?? '-' }},
                                        {{ $lowongan->perusahaan->provinsi ?? '-' }}</div>
                                    <a href="#ONGOING" class="btn btn-dark w-100">Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar Filter --}}
            <div class="col-md-3 mb-4">
                <div class="bg-white rounded p-3 shadow-sm">
                    <h5>Kategori</h5>
                    <input type="text" class="form-control mb-2" placeholder="Cari kategori...">
                    <div>
                        <div><input type="checkbox"> React</div>
                        <div><input type="checkbox"> Laravel</div>
                        <div><input type="checkbox"> Next Js</div>
                        <div><input type="checkbox"> Spring</div>
                        <div><input type="checkbox"> Rust</div>
                        <div><input type="checkbox"> Golang</div>
                    </div>
                    <hr>
                    <h6>Tunjangan</h6>
                    <div><input type="checkbox"> Rp. 0</div>
                    <div><input type="checkbox"> Rp. 0 - Rp. 500k</div>
                    <div><input type="checkbox"> Rp. 500k - Rp. 1.500k</div>
                </div>
            </div>
        </div>
    </div>
@endsection
