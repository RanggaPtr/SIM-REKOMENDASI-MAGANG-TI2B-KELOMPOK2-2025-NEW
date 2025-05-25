@extends('layouts.template')

@section('title', 'Formulir Evaluasi Magang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $isEdit ? 'Edit Evaluasi Magang' : 'Form Evaluasi Magang' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ $isEdit ? route('dosen.evaluasi-magang.update', $evaluasi->id) : route('dosen.evaluasi-magang.store') }}" method="POST">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="pengajuan_id">Pengajuan Magang</label>
                                    <select class="form-control @error('pengajuan_id') is-invalid @enderror" id="pengajuan_id" name="pengajuan_id" required>
                                        <option value="">-- Pilih Pengajuan Magang --</option>
                                        @foreach($pengajuanList ?? [] as $pengajuan)
                                            <option value="{{ $pengajuan->id }}" {{ (old('pengajuan_id') == $pengajuan->id || (isset($evaluasi) && $evaluasi->pengajuan_id == $pengajuan->id)) ? 'selected' : '' }}>
                                                {{ $pengajuan->mahasiswa_nama ?? 'Mahasiswa' }} - {{ $pengajuan->perusahaan_nama ?? 'Perusahaan' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pengajuan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nilai">Nilai</label>
                                    <input type="number" class="form-control @error('nilai') is-invalid @enderror" id="nilai" name="nilai" value="{{ old('nilai', isset($evaluasi) ? $evaluasi->nilai : '') }}" min="0" max="100" required>
                                    <small class="form-text text-muted">Masukkan nilai antara 0-100</small>
                                    @error('nilai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="komentar">Komentar/Evaluasi</label>
                                    <textarea class="form-control @error('komentar') is-invalid @enderror" id="komentar" name="komentar" rows="4" required>{{ old('komentar', isset($evaluasi) ? $evaluasi->komentar : '') }}</textarea>
                                    @error('komentar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ $isEdit ? 'Update' : 'Simpan' }} Evaluasi
                                </button>
                                <a href="{{ route('dosen.evaluasi-magang') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pengajuan_id').select2({
            placeholder: "-- Pilih Pengajuan Magang --",
            width: '100%'
        });
    });
</script>
@endpush