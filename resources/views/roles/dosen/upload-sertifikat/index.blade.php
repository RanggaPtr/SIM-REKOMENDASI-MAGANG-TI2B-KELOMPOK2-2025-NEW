@extends('layouts.template')

@section('content')
<h3>Upload Sertifikat Dosen</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('dosen.upload.sertifikat.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="nama_sertifikat" class="form-label">Nama Sertifikat</label>
        <input type="text" class="form-control @error('nama_sertifikat') is-invalid @enderror" id="nama_sertifikat" name="nama_sertifikat" value="{{ old('nama_sertifikat') }}" required>
        @error('nama_sertifikat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="penerbit" class="form-label">Penerbit</label>
        <input type="text" class="form-control @error('penerbit') is-invalid @enderror" id="penerbit" name="penerbit" value="{{ old('penerbit') }}" required>
        @error('penerbit')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
        <input type="date" class="form-control @error('tanggal_terbit') is-invalid @enderror" id="tanggal_terbit" name="tanggal_terbit" value="{{ old('tanggal_terbit') }}" required>
        @error('tanggal_terbit')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="file_sertifikat" class="form-label">File Sertifikat (PDF, JPG, PNG)</label>
        <input type="file" class="form-control @error('file_sertifikat') is-invalid @enderror" id="file_sertifikat" name="file_sertifikat" required>
        @error('file_sertifikat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>
@endsection
