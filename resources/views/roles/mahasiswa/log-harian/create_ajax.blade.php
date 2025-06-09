@extends('layouts.template')

@section('title', 'Tambah Log Harian')

@section('content')
    <style>
        .custom-form-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 28px 24px;
            background: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .custom-form-container h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #2c3e50;
        }
        .custom-form-group {
            margin-bottom: 18px;
        }
        .custom-form-label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #34495e;
        }
        .custom-form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            background: #fff;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        .custom-form-control:focus {
            border-color: #007bff;
            outline: none;
        }
        textarea.custom-form-control {
            resize: vertical;
            min-height: 120px;
        }
        .text-danger {
            font-size: 13px;
            margin-top: 5px;
        }
        .custom-btn-primary {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 10px;
            transition: background 0.2s;
        }
        .custom-btn-primary:hover {
            background: #0056b3;
        }
        .custom-btn-primary:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .custom-btn-secondary {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .custom-btn-secondary:hover {
            background: #495057;
            color: #fff;
            text-decoration: none;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        @media (max-width: 600px) {
            .custom-form-container {
                margin: 15px;
                padding: 20px 15px;
            }
        }
    </style>

    <div class="custom-form-container">
        <h2>Tambah Log Harian</h2>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="form-log-harian" action="{{ route('mahasiswa.log-harian.store') }}" method="POST">
            @csrf

            <div class="custom-form-group">
                <label for="aktivitas" class="custom-form-label">Aktivitas <span style="color: red;">*</span></label>
                <textarea class="custom-form-control @error('aktivitas') is-invalid @enderror" 
                          name="aktivitas" 
                          id="aktivitas" 
                          placeholder="Deskripsikan aktivitas yang Anda lakukan hari ini..." 
                          required>{{ old('aktivitas') }}</textarea>
                @error('aktivitas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div id="error-aktivitas" class="text-danger"></div>
            </div>

            <button type="submit" class="custom-btn-primary" id="btn-submit">
                <span id="btn-text">Simpan</span>
                <span id="btn-loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                </span>
            </button>
            <a href="{{ route('mahasiswa.log-harian.index') }}" class="custom-btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Load jQuery jika belum ada di layout -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#form-log-harian').on('submit', function(e) {
                e.preventDefault();

                // Bersihkan error message
                $('.text-danger').text('');
                $('.custom-form-control').removeClass('is-invalid');
                
                // Disable button dan show loading
                $('#btn-submit').prop('disabled', true);
                $('#btn-text').hide();
                $('#btn-loading').show();

                let form = this;
                let formData = $(form).serialize();

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    success: function(response) {
                        // Reset button state
                        $('#btn-submit').prop('disabled', false);
                        $('#btn-text').show();
                        $('#btn-loading').hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Log aktivitas berhasil disimpan',
                            confirmButtonText: 'OK',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = "{{ route('mahasiswa.log-harian.index') }}";
                        });
                    },
                    error: function(xhr) {
                        // Reset button state
                        $('#btn-submit').prop('disabled', false);
                        $('#btn-text').show();
                        $('#btn-loading').hide();

                        if(xhr.status === 422) { 
                            // Validation error
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, messages) {
                                $('#error-' + key).text(messages[0]);
                                $('[name="' + key + '"]').addClass('is-invalid');
                            });
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: 'Silakan periksa input Anda',
                                confirmButtonText: 'OK'
                            });
                        } else if(xhr.status === 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            let errorMessage = 'Terjadi kesalahan';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            // Auto resize textarea
            $('#aktivitas').on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
@endsection