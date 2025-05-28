@extends('layouts.template')

@section('title', 'Edit Lowongan Magang')

@section('content')
<div class="container mt-4">
    <h2>Edit Lowongan Magang</h2>

    <form action="{{ route('admin.lowongan.update', $lowongan->lowongan_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="perusahaan_id" class="form-label">Perusahaan</label>
            <select class="form-control" id="perusahaan_id" name="perusahaan_id" required>
                <option value="">Pilih Perusahaan</option>
                @foreach ($perusahaans as $perusahaan)
                    <option value="{{ $perusahaan->perusahaan_id }}" {{ old('perusahaan_id', $lowongan->perusahaan_id) == $perusahaan->perusahaan_id ? 'selected' : '' }}>
                        {{ $perusahaan->nama }}
                    </option>
                @endforeach
            </select>
            @error('perusahaan_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $lowongan->judul) }}" required>
            @error('judul')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="periode_id" class="form-label">Periode Magang</label>
            <select class="form-control" id="periode_id" name="periode_id" required>
                <option value="">Pilih Periode</option>
                @foreach ($periodes as $periode)
                    <option value="{{ $periode->periode_id }}" {{ old('periode_id', $lowongan->periode_id) == $periode->periode_id ? 'selected' : '' }}>
                        {{ $periode->nama }}
                    </option>
                @endforeach
            </select>
            @error('periode_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="skema_id" class="form-label">Skema Magang</label>
            <select class="form-control" id="skema_id" name="skema_id" required>
                <option value="">Pilih Skema</option>
                @foreach ($skemas as $skema)
                    <option value="{{ $skema->skema_id }}" {{ old('skema_id', $lowongan->skema_id) == $skema->skema_id ? 'selected' : '' }}>
                        {{ $skema->nama }}
                    </option>
                @endforeach
            </select>
            @error('skema_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="persyaratan" class="form-label">Persyaratan</label>
            <textarea class="form-control" id="persyaratan" name="persyaratan" rows="5" required>{{ old('persyaratan', $lowongan->persyaratan) }}</textarea>
            @error('persyaratan')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
            <input type="text" class="form-control" id="bidang_keahlian" name="bidang_keahlian" value="{{ old('bidang_keahlian', $lowongan->bidang_keahlian) }}" required>
            @error('bidang_keahlian')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_buka" class="form-label">Tanggal Buka</label>
            <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" value="{{ old('tanggal_buka', $lowongan->tanggal_buka->format('Y-m-d')) }}" required>
            @error('tanggal_buka')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_tutup" class="form-label">Tanggal Tutup</label>
            <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" value="{{ old('tanggal_tutup', $lowongan->tanggal_tutup->format('Y-m-d')) }}" required>
            @error('tanggal_tutup')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection