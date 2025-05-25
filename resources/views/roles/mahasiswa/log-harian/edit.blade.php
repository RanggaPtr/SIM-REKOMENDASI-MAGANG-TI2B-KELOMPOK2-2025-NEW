@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Edit Log Harian</h2>

    <form action="{{ route('mahasiswa.log-harian.update', $logHarian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $logHarian->tanggal) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="kegiatan" class="form-label">Kegiatan</label>
            <input type="text" name="kegiatan" value="{{ old('kegiatan', $logHarian->kegiatan) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ old('keterangan', $logHarian->keterangan) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('mahasiswa.log-harian.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
