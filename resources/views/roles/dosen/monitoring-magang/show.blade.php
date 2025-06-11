@extends('layouts.template')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h4 class="mb-0">Detail Mahasiswa</h4>
            <a href="{{ route('dosen.monitoring.mahasiswa') }}" class="btn btn-light btn-sm ms-auto">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ $pengajuan->mahasiswa->user->nama ?? $pengajuan->mahasiswa->nim ?? 'Nama Tidak Tersedia' }}</p>
                    <p><strong>NIM:</strong> {{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Lowongan:</strong> {{ $pengajuan->lowongan->judul ?? '-' }}</p>
                    <p><strong>Periode:</strong> {{ $pengajuan->lowongan->periode->nama ?? 'Tidak Tersedia' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge {{ $pengajuan->status == 'diterima' ? 'bg-success' : 'bg-warning' }}">
                            {{ $pengajuan->status ?? '-' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <h4 class="mb-4">Log Aktivitas</h4>
    <div class="timeline">
        @forelse($logs as $log)
            <div class="timeline-item mb-4">
                <div class="timeline-icon">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="timeline-content card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">{{ $log->aktivitas ?? 'Tidak Ada Aktivitas' }}</h5>
                            <small class="text-muted">{{ $log->feedback ? $log->feedback->created_at->format('d M Y') : '-' }}</small>
                        </div>
                        <hr>
                        <h6>Feedback</h6>
                        @if($log->feedback)
                            <div class="border-start border-primary ps-3 mb-3">
                                <p><strong>{{ $log->feedback->dosen->user->nama ?? 'Dosen' }}:</strong> {{ $log->feedback->komentar ?? 'Tidak Ada Komentar' }}</p>
                                @if($log->feedback->nilai)
                                    <p><strong>Nilai:</strong> {{ $log->feedback->nilai }}/100</p>
                                @endif
                                <small class="text-muted"><i>{{ $log->feedback->created_at->format('d M Y H:i') ?? '-' }}</i></small>
                            </div>
                        @else
                            <p class="text-muted">Belum ada feedback.</p>
                            <form action="{{ route('dosen.monitoring.feedback.store', $log->log_id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="komentar_{{ $log->log_id }}" class="form-label">Tambah Feedback</label>
                                    <textarea name="komentar" id="komentar_{{ $log->log_id }}" class="form-control" rows="4" required></textarea>
                                    @error('komentar')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nilai_{{ $log->log_id }}" class="form-label">Nilai (0-100, opsional)</label>
                                    <input type="number" name="nilai" id="nilai_{{ $log->log_id }}" min="0" max="100" class="form-control" value="{{ old('nilai') }}">
                                    @error('nilai')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">Simpan Feedback</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Belum ada log aktivitas untuk mahasiswa ini.</div>
        @endforelse
    </div>
</div>
@endsection

@push('css')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 15px;
        width: 4px;
        background: #007bff;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-icon {
        position: absolute;
        left: -30px;
        top: 10px;
        color: #007bff;
        font-size: 18px;
    }
    .timeline-content {
        background: #fff;
        border-radius: 8px;
        transition: transform 0.2s;
    }
    .timeline-content:hover {
        transform: translateY(-5px);
    }
    .badge {
        font-size: 0.9em;
        padding: 5px 10px;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    // Optional: Tambahkan animasi smooth scroll atau interaksi lainnya jika diperlukan
    document.querySelectorAll('.timeline-content').forEach(item => {
        item.addEventListener('click', () => {
            item.classList.toggle('shadow-lg');
        });
    });
</script>
@endpush