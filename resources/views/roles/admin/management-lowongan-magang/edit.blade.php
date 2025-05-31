@extends('layouts.template')

@section('title', 'Edit Lowongan Magang')

@section('content')
<div class="container mt-4">
    <h2>Edit Lowongan Magang</h2>

    <form action="{{ route('admin.lowongan.update', $lowongan->lowongan_id) }}" method="POST" id="editLowonganForm">
        @csrf
        @method('PUT')

        {{-- Perusahaan --}}
        <div class="mb-3">
            <label for="perusahaan_id" class="form-label">Perusahaan <span class="text-danger">*</span></label>
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

        {{-- Judul --}}
        <div class="mb-3">
            <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $lowongan->judul) }}" required>
            @error('judul')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Periode --}}
        <div class="mb-3">
            <label for="periode_id" class="form-label">Periode Magang <span class="text-danger">*</span></label>
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

        {{-- Skema --}}
        <div class="mb-3">
            <label for="skema_id" class="form-label">Skema Magang <span class="text-danger">*</span></label>
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

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Persyaratan --}}
        <div class="mb-3">
            <label for="persyaratan" class="form-label">Persyaratan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="persyaratan" name="persyaratan" rows="5" required>{{ old('persyaratan', $lowongan->persyaratan) }}</textarea>
            @error('persyaratan')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tunjangan --}}
        <div class="mb-3">
            <label for="tunjangan" class="form-label">Tunjangan (Rp) <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="tunjangan" name="tunjangan" value="{{ old('tunjangan', $lowongan->tunjangan) }}" required>
            @error('tunjangan')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Keahlian (Multiple Select) --}}
        <div class="mb-3">
            <label class="form-label">Keahlian <span class="text-danger">*</span></label>
            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                @php
                    $selectedKeahlian = old('keahlian', $lowongan->lowonganKeahlian->pluck('keahlian_id')->toArray());
                @endphp
                @foreach ($keahlians as $keahlian)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="keahlian[]" 
                               value="{{ $keahlian->keahlian_id }}" 
                               id="keahlian_{{ $keahlian->keahlian_id }}"
                               {{ in_array($keahlian->keahlian_id, $selectedKeahlian) ? 'checked' : '' }}>
                        <label class="form-check-label" for="keahlian_{{ $keahlian->keahlian_id }}">
                            {{ $keahlian->nama }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('keahlian')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kompetensi (Single Select) --}}
        <div class="mb-3">
            <label class="form-label">Kompetensi <span class="text-danger">*</span></label>
            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                @php
                    $selectedKompetensi = old('kompetensi', $lowongan->lowonganKompetensi->pluck('kompetensi_id')->toArray());
                @endphp
                @foreach ($kompetensis as $kompetensi)
                    <div class="form-check">
                        <input class="form-check-input kompetensi-radio" type="radio" name="kompetensi[]" 
                               value="{{ $kompetensi->kompetensi_id }}" 
                               id="kompetensi_{{ $kompetensi->kompetensi_id }}"
                               {{ in_array($kompetensi->kompetensi_id, $selectedKompetensi) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kompetensi_{{ $kompetensi->kompetensi_id }}">
                            {{ $kompetensi->nama }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('kompetensi')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tanggal Buka --}}
        <div class="mb-3">
            <label for="tanggal_buka" class="form-label">Tanggal Buka <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" value="{{ old('tanggal_buka', $lowongan->tanggal_buka->format('Y-m-d')) }}" required>
            @error('tanggal_buka')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tanggal Tutup --}}
        <div class="mb-3">
            <label for="tanggal_tutup" class="form-label">Tanggal Tutup <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" value="{{ old('tanggal_tutup', $lowongan->tanggal_tutup->format('Y-m-d')) }}" required>
            @error('tanggal_tutup')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editLowonganForm');
    const tanggalBuka = document.getElementById('tanggal_buka');
    const tanggalTutup = document.getElementById('tanggal_tutup');
    
    // Set minimum tanggal tutup berdasarkan tanggal buka
    tanggalBuka.addEventListener('change', function() {
        tanggalTutup.min = this.value;
    });

    // Validasi form sebelum submit
    form.addEventListener('submit', function(e) {
        const keahlianChecked = document.querySelectorAll('input[name="keahlian[]"]:checked').length;
        const kompetensiChecked = document.querySelectorAll('input[name="kompetensi[]"]:checked').length;
        
        if (keahlianChecked === 0) {
            e.preventDefault();
            alert('Pilih minimal satu keahlian!');
            return false;
        }
        
        if (kompetensiChecked !== 1) {
            e.preventDefault();
            alert('Pilih tepat satu kompetensi!');
            return false;
        }
        
        if (tanggalTutup.value && tanggalBuka.value && tanggalTutup.value <= tanggalBuka.value) {
            e.preventDefault();
            alert('Tanggal tutup harus setelah tanggal buka!');
            return false;
        }
    });
});
</script>
@endsection