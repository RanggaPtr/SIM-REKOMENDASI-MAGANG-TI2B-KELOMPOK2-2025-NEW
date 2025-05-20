@extends('layouts.template')

@section('title', 'Manajemen Akun & Profil Mahasiswa')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h1 class="fw-bold">Dashboard Mahasiswa</h1>
                <p>Selamat datang, <span class="text-primary">{{ Auth::user()->nama }}</span>!</p>
            </div>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

       <div class="row g-4">
    <!-- Manajemen Akun -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-square bg-primary text-white rounded-3 me-3 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-circle fs-4"></i>
                    </div>
                    <h5 class="card-title mb-0">Manajemen Akun</h5>
                </div>
                <p class="text-muted mb-3">Kelola data akun dan keamanan Anda.</p>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-person me-2 text-primary"></i> Lihat Profil
                    </a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-pencil-square me-2 text-warning"></i> Edit Profil
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bi bi-lock-fill me-2 text-danger"></i> Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Akademik -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-square bg-secondary text-white rounded-3 me-3 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-mortarboard fs-4"></i>
                    </div>
                    <h5 class="card-title mb-0">Informasi Akademik</h5>
                </div>
                <p class="text-muted fs-6">Silahkan pilih menu manajemen akun untuk mengatur informasi akun Anda. Data akademik akan ditampilkan di halaman terpisah atau diintegrasikan di sini jika dibutuhkan.</p>
                <!-- Tambahkan komponen akademik lain di sini -->
            </div>
        </div>
    </div>
</div>


    <!-- Modal Ubah Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.change_password') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .hover-highlight:hover {
            background-color: #f0f8ff;
            cursor: pointer;
        }
    </style>
@endpush

@push('styles')
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('js/mahasiswa.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
