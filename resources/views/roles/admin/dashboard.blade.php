@extends('layouts.template')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>Dashboard Admin </h1>
    <p>Selamat datang, {{ Auth::user()->nama }}!</p>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow text-center" style="background: #e3fcec; border-left: 8px solid #28a745;">
                    <div class="card-body">
                        <h5 class="card-title" style="color:#218838;">Jumlah Dosen</h5>
                        <h2 class="card-text" style="color:#218838;">{{ $jumlah_dosen }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow text-center" style="background: #e9ecef; border-left: 8px solid #007bff;">
                    <div class="card-body">
                        <h5 class="card-title" style="color:#0056b3;">Jumlah Mahasiswa</h5>
                        <h2 class="card-text" style="color:#0056b3;">{{ $jumlah_mahasiswa }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow text-center" style="background: #fff3cd; border-left: 8px solid #ffc107;">
                    <div class="card-body">
                        <h5 class="card-title" style="color:#856404;">Mahasiswa Sudah Magang</h5>
                        <h2 class="card-text" style="color:#856404;">{{ $jumlah_magang }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endpush