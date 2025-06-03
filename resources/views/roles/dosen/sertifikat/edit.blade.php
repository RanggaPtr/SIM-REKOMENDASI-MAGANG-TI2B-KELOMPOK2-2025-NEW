@extends('layouts.template')

@section('content')
<h3>Edit Sertifikat</h3>

<form action="{{ route('dosen.sertifikat.update', $sertifikat->sertifikat_dosen_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="nama_sertifikat" class="form-label">Nama Sertifikat</label>
        <input type="text" class="form-control" id="nama_sertifikat" name="nama_sertifikat" value="{{ $sertifikat->nama_sertifikat }}" required>
    </div>

    <div class="mb-3">
        <label for="penerbit" class="form-label">Penerbit</label>
        <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ $sertifikat->penerbit }}" required>
    </div>

    <div class="mb-3">
        <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
        <input type="date" class="form-control" id="tanggal_terbit" name="tanggal_terbit" value="{{ $sertifikat->tanggal_terbit->format('Y-m-d') }}" required>
    </div>

    <div class="mb-3">
        <label for="file_sertifikat" class="form-label">Ganti File Sertifikat (opsional)</label>
        <input type="file" class="form-control" id="file_sertifikat" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png">
        @if($sertifikat->file_sertifikat)
            <small>File saat ini: <a href="{{ asset('storage/' . $sertifikat->file_sertifikat) }}" target="_blank">Lihat</a></small>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
