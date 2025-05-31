@extends('layouts.template')

@section('title', 'Detail Lowongan Magang')

@section('content')
<div class="container mt-4">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Detail Lowongan Magang</h3>
        </div>
        <div class="card-body">
            <h4 class="mb-3">{{ $lowongan->judul }}</h4>
            <div class="mb-2"><strong>Perusahaan:</strong> {{ $lowongan->perusahaan->nama ?? 'Tidak ada perusahaan' }}</div>
            <div class="mb-2"><strong>Periode:</strong> {{ $lowongan->periode->nama ?? 'Tidak ada periode' }}</div>
            <div class="mb-2"><strong>Skema:</strong> {{ $lowongan->skema->nama ?? 'Tidak ada skema' }}</div>
            <div class="mb-2"><strong>Tunjangan:</strong> Rp {{ number_format($lowongan->tunjangan, 0, ',', '.') }}</div>
            
            <div class="mb-3">
                <strong>Deskripsi:</strong>
                <p class="text-break">{!! nl2br(e($lowongan->deskripsi)) !!}</p>
            </div>
            
            <div class="mb-3">
                <strong>Persyaratan:</strong>
                <p class="text-break">{!! nl2br(e($lowongan->persyaratan)) !!}</p>
            </div>
            
            <div class="mb-3">
                <strong>Bidang Keahlian:</strong>
                {{-- Debug info --}}
                @if(config('app.debug'))
                    <small class="text-muted d-block">Total keahlian = {{ $lowongan->lowonganKeahlian->count() }}</small>
                @endif
                
                @if($lowongan->lowonganKeahlian->count() > 0)
                    <ul class="mb-0">
                        @foreach($lowongan->lowonganKeahlian as $item)
                            <li>
                                {{ $item->keahlian->nama ?? 'Nama keahlian tidak ditemukan' }}
                                @if(config('app.debug'))
                                    <small class="text-muted">(ID: {{ $item->keahlian_id }})</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">Tidak ada bidang keahlian yang dipilih</span>
                @endif
            </div>

            <div class="mb-3">
                <strong>Kompetensi:</strong>
                {{-- Debug info --}}
                @if(config('app.debug'))
                    <small class="text-muted d-block">Total kompetensi = {{ $lowongan->lowonganKompetensi->count() }}</small>
                @endif
                
                @if($lowongan->lowonganKompetensi->count() > 0)
                    <ul class="mb-0">
                        @foreach($lowongan->lowonganKompetensi as $item)
                            <li>
                                {{ $item->kompetensi->nama ?? 'Nama kompetensi tidak ditemukan' }}
                                @if(config('app.debug'))
                                    <small class="text-muted">(ID: {{ $item->kompetensi_id }})</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">Tidak ada kompetensi yang dipilih</span>
                @endif
            </div>
            
            <div class="mb-2"><strong>Tanggal Buka:</strong> {{ $lowongan->tanggal_buka->format('d M Y') }}</div>
            <div class="mb-2"><strong>Tanggal Tutup:</strong> {{ $lowongan->tanggal_tutup->format('d M Y') }}</div>
            
            {{-- Debug section (hanya tampil jika APP_DEBUG=true) --}}
            @if(config('app.debug'))
                <hr>
                <div class="alert alert-info">
                    <h6>Detail Information:</h6>
                    <p><strong>Lowongan ID:</strong> {{ $lowongan->lowongan_id }}</p>
                    <p><strong>Keahlian Relations:</strong> {{ $lowongan->lowonganKeahlian->pluck('keahlian_id')->implode(', ') }}</p>
                    <p><strong>Kompetensi Relations:</strong> {{ $lowongan->lowonganKompetensi->pluck('kompetensi_id')->implode(', ') }}</p>
                </div>
            @endif
        </div>
        
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.lowongan.edit', $lowongan->lowongan_id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection