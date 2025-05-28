@extends('layouts.template')
@section('title', 'Statistik Magang')

@section('content')
    <div class="container mt-4">
        <h2>Statistik Monitoring Magang</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6>Mahasiswa Sudah Magang</h6>
                        <h3>{{ $jumlah_mahasiswa_magang }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6>Jumlah Dosen Pembimbing</h6>
                        <h3>{{ $jumlah_dosen }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6>Rasio Dosen Terhadap Mahasiswa Magang</h6>
                        <h3>{{ $rasio }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mt-4">Tren Peminatan Mahasiswa Magang (Bidang Industri)</h5>
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <canvas id="trenIndustriChart" style="height: 400px;"></canvas>
            </div>
        </div>
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
                    backgroundColor: 'rgba(78, 115, 223, 0.7)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Mahasiswa'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bidang Industri'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.parsed.y + ' mahasiswa';
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Grafik Tren Peminatan Mahasiswa Magang',
                        font: {
                            size: 16
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
    </script>
@endpush