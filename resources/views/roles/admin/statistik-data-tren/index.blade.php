@extends('layouts.template')
@section('title', 'Statistik Magang')
@section('content')
    <div class="container mt-4">
        <h2>Statistik Monitoring Magang</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6>Mahasiswa Sudah Magang</h6>
                        <h3>{{ $jumlah_mahasiswa_magang }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6>Jumlah Dosen Pembimbing</h6>
                        <h3>{{ $jumlah_dosen }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6>Rasio Dosen Terhadap Peserta Magang</h6>
                        <h3>{{ $rasio }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <h5 class="mt-4">Tren Peminatan Mahasiswa Magang (Bidang Industri)</h5>
        <canvas id="trenIndustriChart"></canvas>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('trenIndustriChart').getContext('2d');
        const trenIndustriChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($tren_industri->pluck('bidang_industri')) !!},
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: {!! json_encode($tren_industri->pluck('total')) !!},
                    backgroundColor: '#4e73df'
                }]
            }
        });
    </script>
@endpush