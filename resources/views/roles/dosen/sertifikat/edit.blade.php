@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Sertifikat</h3>
                    <div class="card-tools">
                        <a href="{{ route('dosen.sertifikat.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dosen.sertifikat.update', $sertifikat->sertifikat_dosen_id) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_sertifikat" class="form-label">
                                        Nama Sertifikat <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nama_sertifikat') is-invalid @enderror" 
                                           id="nama_sertifikat" 
                                           name="nama_sertifikat" 
                                           value="{{ old('nama_sertifikat', $sertifikat->nama_sertifikat) }}" 
                                           required>
                                    @error('nama_sertifikat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="penerbit" class="form-label">
                                        Penerbit <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('penerbit') is-invalid @enderror" 
                                           id="penerbit" 
                                           name="penerbit" 
                                           value="{{ old('penerbit', $sertifikat->penerbit) }}" 
                                           required>
                                    @error('penerbit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_terbit" class="form-label">
                                        Tanggal Terbit <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_terbit') is-invalid @enderror" 
                                           id="tanggal_terbit" 
                                           name="tanggal_terbit" 
                                           value="{{ old('tanggal_terbit', \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('Y-m-d')) }}" 
                                           required>
                                    @error('tanggal_terbit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="file_sertifikat" class="form-label">
                                        Ganti File Sertifikat <small class="text-muted">(opsional)</small>
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('file_sertifikat') is-invalid @enderror" 
                                           id="file_sertifikat" 
                                           name="file_sertifikat" 
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="form-text">
                                        Format yang diizinkan: PDF, JPG, JPEG, PNG. Maksimal 2MB.
                                    </div>
                                    @error('file_sertifikat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($sertifikat->file_sertifikat)
                            <div class="mb-3">
                                <label class="form-label">File Saat Ini:</label>
                                <div class="d-flex align-items-center">
                                    <a href="{{ asset('storage/' . $sertifikat->file_sertifikat) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-info btn-sm me-2">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                    <small class="text-muted">
                                        {{ basename($sertifikat->file_sertifikat) }}
                                    </small>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dosen.sertifikat.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Sertifikat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <style>
        .card {
            box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 600;
        }
        .text-danger {
            font-size: 0.875em;
        }
    </style>
@endpush