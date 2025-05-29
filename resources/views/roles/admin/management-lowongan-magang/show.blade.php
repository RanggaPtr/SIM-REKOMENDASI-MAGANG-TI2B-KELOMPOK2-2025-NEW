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
            <div class="mb-2"><strong>Perusahaan:</strong> {{ $lowongan->perusahaan->nama }}</div>
            <div class="mb-2"><strong>Periode:</strong> {{ $lowongan->periode->nama }}</div>
            <div class="mb-2"><strong>Skema:</strong> {{ $lowongan->skema->nama }}</div>
            <div class="mb-2"><strong>Minimal IPK:</strong> {{ $lowongan->minimal_ipk }}</div>
            <div class="mb-2"><strong>Bidang Keahlian Utama:</strong> {{ $lowongan->bidang_keahlian }}</div>
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
                <strong>Bidang Keahlian Tambahan:</strong>
                @if($lowongan->keahlian->count() > 0)
                    <ul class="mb-0">
                        @foreach($lowongan->keahlian as $k)
                            <li>{{ $k->nama }}</li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">Tidak ada bidang keahlian tambahan</span>
                @endif
            </div>
            
            <div class="mb-3">
                <strong>Kompetensi:</strong>
                @if($lowongan->kompetensi->count() > 0)
                    <ul class="mb-0">
                        @foreach($lowongan->kompetensi as $k)
                            <li>{{ $k->nama }}</li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">Tidak ada kompetensi</span>
                @endif
            </div>
            
            <div class="mb-2"><strong>Tanggal Buka:</strong> {{ $lowongan->tanggal_buka->format('d M Y') }}</div>
            <div class="mb-2"><strong>Tanggal Tutup:</strong> {{ $lowongan->tanggal_tutup->format('d M Y') }}</div>
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