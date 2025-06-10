@extends('layouts.template')

@section('title', 'Tambah Lowongan Magang')

@section('content')
<div class="container mt-4">
    <h2>Tambah Lowongan Magang</h2>

    <form action="{{ route('admin.lowongan.store') }}" method="POST" id="createLowonganForm" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="perusahaan_id" class="form-label">Perusahaan <span class="text-danger">*</span></label>
            <select class="form-control" id="perusahaan_id" name="perusahaan_id" required>
                <option value="">Pilih Perusahaan</option>
                @foreach ($perusahaans as $perusahaan)
                    <option value="{{ $perusahaan->perusahaan_id }}" {{ old('perusahaan_id') == $perusahaan->perusahaan_id ? 'selected' : '' }}>
                        {{ $perusahaan->nama }}
                    </option>
                @endforeach
            </select>
            @error('perusahaan_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
            @error('judul')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="periode_id" class="form-label">Periode Magang <span class="text-danger">*</span></label>
            <select class="form-control" id="periode_id" name="periode_id" required>
                <option value="">Pilih Periode</option>
                @foreach ($periodes as $periode)
                    <option value="{{ $periode->periode_id }}" {{ old('periode_id') == $periode->periode_id ? 'selected' : '' }}>
                        {{ $periode->nama }}
                    </option>
                @endforeach
            </select>
            @error('periode_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="skema_id" class="form-label">Skema Magang <span class="text-danger">*</span></label>
            <select class="form-control" id="skema_id" name="skema_id" required>
                <option value="">Pilih Skema</option>
                @foreach ($skemas as $skema)
                    <option value="{{ $skema->skema_id }}" {{ old('skema_id') == $skema->skema_id ? 'selected' : '' }}>
                        {{ $skema->nama }}
                    </option>
                @endforeach
            </select>
            @error('skema_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="persyaratan" class="form-label">Persyaratan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="persyaratan" name="persyaratan" rows="5" required>{{ old('persyaratan') }}</textarea>
            @error('persyaratan')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tunjangan <span class="text-danger">*</span></label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tunjangan" value="1" id="tunjangan_berbayar" {{ old('tunjangan', 1) == 1 ? 'checked' : '' }} required>
                <label class="form-check-label" for="tunjangan_berbayar">Berbayar</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tunjangan" value="0" id="tunjangan_tidak_berbayar" {{ old('tunjangan', 1) == 0 ? 'checked' : '' }} required>
                <label class="form-check-label" for="tunjangan_tidak_berbayar">Tidak Berbayar</label>
            </div>
            @error('tunjangan')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kuota" class="form-label">Kuota <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="kuota" name="kuota" value="{{ old('kuota') }}" min="1" required>
            @error('kuota')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="silabus_path" class="form-label">Silabus (PDF, max 2MB)</label>
            <input type="file" class="form-control" id="silabus_path" name="silabus_path" accept=".pdf">
            @error('silabus_path')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Keahlian <span class="text-danger">*</span></label>
            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                @foreach ($keahlians as $keahlian)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="keahlian[]" 
                               value="{{ $keahlian->keahlian_id }}" 
                               id="keahlian_{{ $keahlian->keahlian_id }}"
                               {{ in_array($keahlian->keahlian_id, old('keahlian', [])) ? 'checked' : '' }}>
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

        <div class="mb-3">
            <label class="form-label">Kompetensi <span class="text-danger">*</span></label>
            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                @foreach ($kompetensis as $kompetensi)
                    <div class="form-check">
                        <input class="form-check-input kompetensi-radio" type="radio" name="kompetensi[]" 
                               value="{{ $kompetensi->kompetensi_id }}" 
                               id="kompetensi_{{ $kompetensi->kompetensi_id }}"
                               {{ in_array($kompetensi->kompetensi_id, old('kompetensi', [])) ? 'checked' : '' }}>
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

        <div class="mb-3">
            <label for="tanggal_buka" class="form-label">Tanggal Buka <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" value="{{ old('tanggal_buka') }}" required>
            @error('tanggal_buka')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_tutup" class="form-label">Tanggal Tutup <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" value="{{ old('tanggal_tutup') }}" required>
            @error('tanggal_tutup')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createLowonganForm');
    const tanggalBuka = document.getElementById('tanggal_buka');
    const tanggalTutup = document.getElementById('tanggal_tutup');
    
    tanggalBuka.addEventListener('change', function() {
        tanggalTutup.min = this.value;
    });

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