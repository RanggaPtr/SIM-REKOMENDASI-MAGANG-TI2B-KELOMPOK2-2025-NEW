@extends('layouts.template')

@section('content')
<h3>Tambah Sertifikat Baru</h3>

<form action="{{ route('dosen.sertifikat.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="nama_sertifikat" class="form-label">Nama Sertifikat</label>
        <input type="text" class="form-control" id="nama_sertifikat" name="nama_sertifikat" required>
    </div>

    <div class="mb-3">
        <label for="penerbit" class="form-label">Penerbit</label>
        <input type="text" class="form-control" id="penerbit" name="penerbit" required>
    </div>

    <div class="mb-3">
        <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
        <input type="date" class="form-control" id="tanggal_terbit" name="tanggal_terbit" required>
    </div>

    <div class="mb-3">
        <label for="file_sertifikat" class="form-label">File Sertifikat (pdf/jpg/png max 2MB)</label>
        <input type="file" class="form-control" id="file_sertifikat" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
