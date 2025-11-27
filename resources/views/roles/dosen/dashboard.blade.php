@extends('layouts.template')

@section('title', 'Dosen Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="card shadow-lg mb-4 border-0">
            <div class="card-header bg-primary text-white">
                <!-- <h3 class="mb-0">Dashboard Dosen Pembimbing</h3> -->
            </div>
            <div class="card-body">
                <h4 class="mb-3">Selamat datang, {{ Auth::user()->nama }}!</h4>

                <p class="text-muted mb-0">Anda masuk sebagai Dosen Pembimbing.</p>

                <hr>
                <!-- title ini adalah list sertifikat anda bold -->
                <h5 class="text-bold">Sertifikat Anda:</h5>
                <!-- Sertifikat Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        @if ($sertifikats->isEmpty())
                            <div class="alert alert-info">
                                Anda belum memiliki sertifikat.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($sertifikats->take(3) as $sertifikat)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">{{ $sertifikat->nama_sertifikat }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    @if (pathinfo($sertifikat->file_sertifikat, PATHINFO_EXTENSION) === 'pdf')
                                                        <i class="far fa-file-pdf fa-4x text-danger"></i>
                                                    @else
                                                        <img src="{{ asset('storage/' . $sertifikat->file_sertifikat) }}"
                                                            class="img-fluid rounded" style="max-height: 150px;"
                                                            alt="{{ $sertifikat->nama_sertifikat }}">
                                                    @endif
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <strong>Penerbit:</strong> {{ $sertifikat->penerbit }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>Tanggal:</strong>
                                                        {{ $sertifikat->tanggal_terbit->format('d M Y') }}
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-footer bg-white">
                                                <a href="{{ asset('storage/' . $sertifikat->file_sertifikat) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($sertifikats->count() > 3)
                                <div class="text-center mt-3">
                                    <a href="{{ route('dosen.sertifikat.index') }}" class="btn btn-link">
                                        Lihat semua sertifikat <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .list-group-item {
            padding: 0.75rem 1.25rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/dosen.js') }}"></script>
@endpush
