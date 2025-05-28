@extends('layouts.template')
@section('title', 'Edit Log Harian')

@section('content')
<div class="container">
    <h2>Edit Log Harian</h2>

    <form action="{{ route('mahasiswa.log-harian.update', ['log_harian' => $logHarian->log_id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="pengajuan_id" class="form-label">Pengajuan</label>
            <input type="number" name="pengajuan_id" value="{{ old('pengajuan_id', $logHarian->pengajuan_id) }}" class="form-control" required>
            @error('pengajuan_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="aktivitas" class="form-label">Aktivitas</label>
            <textarea name="aktivitas" class="form-control" required>{{ old('aktivitas', $logHarian->aktivitas) }}</textarea>
            @error('aktivitas')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('mahasiswa.log-harian.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
