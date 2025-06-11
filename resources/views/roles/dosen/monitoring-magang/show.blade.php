@extends('layouts.template')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="card shadow-lg mb-4 border-0 overflow-hidden" style="border-left: 5px solid #FFC107;">
        <div class="card-header bg-gradient-warning text-white d-flex align-items-center position-relative">
            <div class="header-overlay"></div>
            <div class="d-flex align-items-center w-100 position-relative z-index-2 bg-transparent">
                <div class="header-icon me-3" style="background-color: rgba(255,193,7,0.3);">
                    <i class="fas fa-user-graduate fs-4 text-white"></i>
                </div>
                <h4 class="mb-0 fw-bold bg-transparent">Detail Mahasiswa</h4>
                <a href="{{ route('dosen.monitoring.mahasiswa') }}" class="btn btn-light btn-sm ms-auto rounded-pill shadow-sm hover-lift" style="background-color: white; color: #FFC107;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body p-4" style="background-color: #FFF9E6;">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-item mb-3">
                            <i class="fas fa-user text-warning me-2"></i>
                            <strong>Nama:</strong> 
                            <span class="ms-2 text-dark">{{ $pengajuan->mahasiswa->user->nama ?? $pengajuan->mahasiswa->nim ?? 'Nama Tidak Tersedia' }}</span>
                        </div>
                        <div class="info-item mb-3">
                            <i class="fas fa-id-badge text-warning me-2"></i>
                            <strong>NIM:</strong> 
                            <span class="ms-2 text-dark">{{ $pengajuan->mahasiswa->nim ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group">
                        <div class="info-item mb-3">
                            <i class="fas fa-briefcase text-warning me-2"></i>
                            <strong>Lowongan:</strong> 
                            <span class="ms-2 text-dark">{{ $pengajuan->lowongan->judul ?? '-' }}</span>
                        </div>
                        <div class="info-item mb-3">
                            <i class="fas fa-calendar text-warning me-2"></i>
                            <strong>Periode:</strong> 
                            <span class="ms-2 text-dark">{{ $pengajuan->lowongan->periode->nama ?? 'Tidak Tersedia' }}</span>
                        </div>
                        <div class="info-item mb-0">
                            <i class="fas fa-flag text-warning me-2"></i>
                            <strong>Status:</strong> 
                            <span class="badge ms-2 {{ $pengajuan->status == 'diterima' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3 py-2 shadow-sm">
                                <i class="fas {{ $pengajuan->status == 'diterima' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                {{ $pengajuan->status ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="timeline-header mb-4">
        <div class="d-flex align-items-center">
            <div class="timeline-header-icon me-3" style="background-color: #FFC107;">
                <i class="fas fa-history text-white"></i>
            </div>
            <h4 class="mb-0 fw-bold text-dark">Log Aktivitas</h4>
            <div class="timeline-header-line flex-grow-1 ms-4" style="background: linear-gradient(90deg, #FFC107 0%, transparent 100%);"></div>
        </div>
    </div>

    <div class="timeline">
        @forelse($logs as $index => $log)
            <div class="timeline-item mb-4 fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="timeline-connector" style="background-color: #FFC107;"></div>
                <div class="timeline-icon">
                    <div class="timeline-icon-inner" style="background-color: #FFC107; border-color: #FFF9E6;">
                        <i class="fas fa-circle-notch"></i>
                    </div>
                </div>
                <div class="timeline-content card shadow-lg border-0 hover-lift" style="border-left: 3px solid #FFC107;">
                    <div class="card-body p-4" style="background-color: #FFFDF5;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0 fw-bold" style="color: #FF9800;">
                                <i class="fas fa-tasks me-2"></i>
                                {{ $log->aktivitas ?? 'Tidak Ada Aktivitas' }}
                            </h5>
                            <div class="timeline-date">
                                <i class="fas fa-clock text-warning me-1"></i>
                                <small class="text-muted">{{ $log->feedback ? $log->feedback->created_at->format('d M Y') : '-' }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-divider" style="background: linear-gradient(90deg, #FFC107, transparent);"></div>
                        
                        <div class="feedback-section">
                            <h6 class="feedback-title" style="color: #FF9800;">
                                <i class="fas fa-comments text-warning me-2"></i>
                                Feedback
                            </h6>
                            
                            @if($log->feedback)
                                <div class="feedback-content">
                                    <div class="feedback-box border-start border-4 border-warning ps-4 mb-3 position-relative" style="background-color: #FFF8E1;">
                                        <div class="feedback-avatar" style="background-color: #FFC107;">
                                            <i class="fas fa-user-tie text-white"></i>
                                        </div>
                                        <p class="mb-2">
                                            <strong style="color: #FF9800;">{{ $log->feedback->dosen->user->nama ?? 'Dosen' }}:</strong> 
                                            <span class="ms-2">{{ $log->feedback->komentar ?? 'Tidak Ada Komentar' }}</span>
                                        </p>
                                        @if($log->feedback->nilai)
                                            <div class="score-badge">
                                                <i class="fas fa-star text-warning me-1"></i>
                                                <strong>Nilai:</strong> 
                                                <span class="badge bg-warning rounded-pill px-3 py-2 ms-1">{{ $log->feedback->nilai }}/100</span>
                                            </div>
                                        @endif
                                        <div class="feedback-timestamp">
                                            <i class="fas fa-clock text-warning me-1"></i>
                                            <small class="text-muted fst-italic">{{ $log->feedback->created_at->format('d M Y H:i') ?? '-' }}</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="no-feedback mb-4">
                                    <div class="alert alert-light border-0 d-flex align-items-center" style="background-color: #FFF8E1;">
                                        <i class="fas fa-info-circle text-warning me-2"></i>
                                        <span class="text-muted">Belum ada feedback untuk aktivitas ini.</span>
                                    </div>
                                </div>
                                
                                <div class="feedback-form">
                                    <form action="{{ route('dosen.monitoring.feedback.store', $log->log_id) }}" method="POST" class="feedback-form-container" style="background-color: #FFF8E1; border-color: #FFC107;">
                                        @csrf
                                        <div class="form-header mb-3" style="border-bottom-color: #FFC107;">
                                            <h6 class="fw-bold" style="color: #FF9800;">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Tambah Feedback
                                            </h6>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="komentar_{{ $log->log_id }}" class="form-label fw-semibold" style="color: #FF9800;">
                                                <i class="fas fa-comment-dots me-1"></i>
                                                Komentar Feedback
                                            </label>
                                            <textarea 
                                                name="komentar" 
                                                id="komentar_{{ $log->log_id }}" 
                                                class="form-control form-control-modern" 
                                                rows="4" 
                                                required
                                                placeholder="Berikan feedback untuk aktivitas mahasiswa..."
                                                style="border-color: #FFC107;"
                                            ></textarea>
                                            @error('komentar')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="nilai_{{ $log->log_id }}" class="form-label fw-semibold" style="color: #FF9800;">
                                                <i class="fas fa-star me-1"></i>
                                                Nilai (0-100, opsional)
                                            </label>
                                            <div class="input-group">
                                                <input 
                                                    type="number" 
                                                    name="nilai" 
                                                    id="nilai_{{ $log->log_id }}" 
                                                    min="0" 
                                                    max="100" 
                                                    class="form-control form-control-modern" 
                                                    value="{{ old('nilai') }}"
                                                    placeholder="Masukkan nilai"
                                                    style="border-color: #FFC107;"
                                                >
                                                <span class="input-group-text" style="background-color: #FFF8E1;">
                                                    <i class="fas fa-percentage text-warning"></i>
                                                </span>
                                            </div>
                                            @error('nilai')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-actions">
                                            <button type="submit" class="btn rounded-pill px-4 py-2 hover-lift" style="background-color: #FFC107; color: white;">
                                                <i class="fas fa-save me-2"></i>
                                                Simpan Feedback
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center p-4" style="background-color: #FFF8E1;">
                    <div class="empty-icon me-3" style="background-color: rgba(255,193,7,0.2);">
                        <i class="fas fa-inbox fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold" style="color: #FF9800;">Belum Ada Log Aktivitas</h6>
                        <p class="mb-0 text-muted">Belum ada log aktivitas untuk mahasiswa ini.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('css')
<style>
    :root {
        --yellow-500: #FFC107;
        --yellow-600: #FFA000;
        --yellow-700: #FF8F00;
        --yellow-50: #FFF8E1;
        --yellow-100: #FFF9C4;
        --shadow-soft: 0 5px 15px rgba(255,193,7,0.1);
        --shadow-hover: 0 10px 25px rgba(255,193,7,0.2);
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Header Enhancements */
    .bg-gradient-warning {
        background: linear-gradient(135deg, #FFC107 0%, #FFA000 100%) !important;
        position: relative;
        overflow: hidden;
    }

    .header-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.1"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><rect width="100" height="20" fill="url(%23a)"/></svg>');
        opacity: 0.3;
    }

    .z-index-2 {
        z-index: 2;
    }

    .hover-lift {
        transition: var(--transition);
    }

    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    /* Info Section */
    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,193,7,0.1);
        transition: var(--transition);
    }

    .info-item:hover {
        padding-left: 10px;
        border-left: 3px solid var(--yellow-500);
        background: rgba(255,193,7,0.05);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    /* Timeline Enhancements */
    .timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 3px;
        background: linear-gradient(to bottom, var(--yellow-500), var(--yellow-600));
        border-radius: 2px;
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.4);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        opacity: 0;
        transform: translateX(20px);
        animation: slideInFromRight 0.6s ease-out forwards;
    }

    @keyframes slideInFromRight {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .fade-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .timeline-icon-inner {
        width: 40px;
        height: 40px;
        background: var(--yellow-500);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
        }
        50% {
            box-shadow: 0 0 25px rgba(255, 193, 7, 0.8);
        }
        100% {
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
        }
    }

    .timeline-content {
        background: white;
        border-radius: var(--border-radius);
        transition: var(--transition);
        border: 1px solid rgba(255,193,7,0.1);
    }

    .timeline-content:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .timeline-date {
        padding: 8px 12px;
        background: rgba(255,193,7,0.1);
        border-radius: 20px;
        white-space: nowrap;
    }

    .timeline-divider {
        height: 2px;
        margin: 15px 0;
        border-radius: 1px;
    }

    /* Feedback Section */
    .feedback-box {
        border-radius: var(--border-radius);
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        transition: var(--transition);
    }

    .feedback-box:hover {
        transform: translateX(5px);
    }

    .feedback-avatar {
        position: absolute;
        top: -10px;
        left: -15px;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        box-shadow: var(--shadow-soft);
        border: 2px solid white;
    }

    /* Form Enhancements */
    .form-control-modern {
        border: 2px solid rgba(255,193,7,0.3);
        border-radius: 10px;
        padding: 12px 16px;
        transition: var(--transition);
        background: white;
    }

    .form-control-modern:focus {
        border-color: var(--yellow-500);
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15);
        transform: translateY(-1px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .timeline {
            padding-left: 25px;
        }
        
        .timeline::before {
            left: 12px;
        }
        
        .timeline-icon {
            left: -25px;
        }
        
        .timeline-icon-inner {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced timeline interactions
        const timelineContents = document.querySelectorAll('.timeline-content');
        
        timelineContents.forEach((item, index) => {
            // Add staggered entrance animation
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Smooth scroll for timeline items
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateX(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        document.querySelectorAll('.timeline-item').forEach(item => {
            observer.observe(item);
        });
    });
</script>
@endpush