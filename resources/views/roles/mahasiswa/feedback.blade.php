

@extends('layouts.template')

@section('content')
<style>
    .feedback-card {
        border: none;
        border-radius: 12px;
        background: #FFFFFF;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin: 1.5rem auto;
        max-width: 800px;
    }

    .feedback-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #FFEDD5, #FFE5B4);
        color: #333;
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
        border-bottom: 1px solid #FEEBC8;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.4rem;
        margin: 0;
    }

    .card-body {
        padding: 2rem;
        background: #FFF8F1;
        border-radius: 0 0 12px 12px;
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 1.5rem;
        padding: 1rem;
        font-size: 0.95rem;
    }

    .alert-success {
        background: #D4EDDA;
        color: #155724;
    }

    .alert-danger {
        background: #F8D7DA;
        color: #721C24;
    }

    .form-group label {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.6rem;
        font-size: 1rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #F6AD55;
        box-shadow: 0 0 6px rgba(246, 173, 85, 0.3);
        outline: none;
    }

    .textarea {
        resize: vertical;
        min-height: 100px;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: #F6AD55;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.75rem;
        font-weight: 500;
        color: #FFFFFF;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background: #ED8936;
        transform: translateY(-2px);
    }

    .star-rating {
        direction: rtl;
        display: inline-flex;
        font-size: 1.6rem;
        margin-bottom: 1rem;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #E2E8F0;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #FFC107;
    }

    .feedback-display {
        background: #FFFFFF;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #FEEBC8;
        margin-bottom: 1rem;
    }

    .feedback-display .stars {
        color: #FFC107;
        font-size: 1.3rem;
    }

    .feedback-display p {
        color: #444;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    @media (max-width: 576px) {
        .feedback-card {
            margin: 1rem;
        }

        .card-title {
            font-size: 1.2rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .star-rating {
            font-size: 1.4rem;
        }
    }
</style>

<div class="container py-4">
    <div class="card feedback-card">
        <div class="card-header">
            <h3 class="card-title">Feedback Magang</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($pengajuan->feedback_rating)
                <div class="feedback-display">
                    <div class="mb-3">
                        <strong>Rating Anda:</strong>
                        <div class="stars">
                            @for ($i = 0; $i < $pengajuan->feedback_rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @for ($i = $pengajuan->feedback_rating; $i < 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Komentar Anda:</strong>
                        <p class="mb-0">{{ $pengajuan->feedback_deskripsi }}</p>
                    </div>
                </div>
            @else
                <form action="{{ route('mahasiswa.feedback.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->pengajuan_id }}">
                    <input type="hidden" name="rating" id="rating-value">

                    <div class="form-group mb-4">
                        <label for="rating">Rating</label>
                        <div class="star-rating">
                            <input type="radio" name="rating_temp" id="star5" value="5">
                            <label for="star5" class="fas fa-star"></label>
                            <input type="radio" name="rating_temp" id="star4" value="4">
                            <label for="star4" class="fas fa-star"></label>
                            <input type="radio" name="rating_temp" id="star3" value="3">
                            <label for="star3" class="fas fa-star"></label>
                            <input type="radio" name="rating_temp" id="star2" value="2">
                            <label for="star2" class="fas fa-star"></label>
                            <input type="radio" name="rating_temp" id="star1" value="1">
                            <label for="star1" class="fas fa-star"></label>
                        </div>
                        @error('rating')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="deskripsi">Komentar</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control textarea" 
                            placeholder="Bagikan pengalaman magang Anda..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Feedback</button>
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stars = document.querySelectorAll('.star-rating input');
        const ratingInput = document.getElementById('rating-value');

        stars.forEach(star => {
            star.addEventListener('change', () => {
                ratingInput.value = star.value;
            });
        });
    });
</script>
@endpush
@endsection
```