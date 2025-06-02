@extends('layouts.template')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, {{ Auth::user()->nama }}!</p>

    {{-- CARD --}}
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

    {{-- GRAFIK --}}
    <h5 class="mt-4">Grafik Peminatan Bidang Industri</h5>
    <div class="mb-4 p-4" style="border-radius: 12px; background: #fff;">
        <canvas id="kompetensiChart" style="height: 400px;"></canvas>
    </div>

    <h5 class="mt-4">Grafik Rasio Penerimaan Magang</h5>
    <div class="mb-5 d-flex justify-content-center">
        <div
            style="width:400px; height:400px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <canvas id="rasioChart" width="250" height="250"></canvas>
        </div>
        <div class="mt-3 ms-4 align-self-center">
            <span class="badge bg-success">Diterima: {{ $persen_diterima }}%</span>
            <span class="badge bg-danger">Ditolak: {{ $persen_ditolak }}%</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const whiteBg = {
            id: 'whiteBg',
            beforeDraw: (chart) => {
                const ctx = chart.ctx;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };

        // Data untuk grafik kompetensi
        const kompetensiData = @json($data_kompetensi);
        const labelsKompetensi = kompetensiData.map(item => item.nama);
        const dataKompetensi = kompetensiData.map(item => item.total);

        const ctxKompetensi = document.getElementById('kompetensiChart').getContext('2d');
        new Chart(ctxKompetensi, {
            type: 'bar',
            data: {
                labels: labelsKompetensi,
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: dataKompetensi,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Grafik Peminatan Bidang Industri',
                        font: { size: 16 }
                    }
                }
            },
            plugins: [whiteBg]
        });

        // Data untuk grafik rasio penerimaan magang
        let totalDiterima = {{ $total_diterima ?? 0 }};
        let totalDitolak = {{ $total_ditolak ?? 0 }};

        let rasioLabels = [];
        let rasioData = [];
        let rasioColors = [];
        let rasioBorder = [];

        if (totalDiterima === 0 && totalDitolak === 0) {
            rasioLabels = ['Belum Ada Data'];
            rasioData = [1];
            rasioColors = ['rgba(108, 117, 125, 0.7)'];
            rasioBorder = ['rgba(108, 117, 125, 1)'];
        } else {
            if (totalDiterima > 0) {
                rasioLabels.push('Diterima');
                rasioData.push(totalDiterima);
                rasioColors.push('rgba(40, 167, 69, 0.7)');
                rasioBorder.push('rgba(40, 167, 69, 1)');
            }
            if (totalDitolak > 0) {
                rasioLabels.push('Ditolak');
                rasioData.push(totalDitolak);
                rasioColors.push('rgba(220, 53, 69, 0.7)');
                rasioBorder.push('rgba(220, 53, 69, 1)');
            }
        }

        const ctxRasio = document.getElementById('rasioChart').getContext('2d');
        new Chart(ctxRasio, {
            type: 'pie',
            data: {
                labels: rasioLabels,
                datasets: [{
                    data: rasioData,
                    backgroundColor: rasioColors,
                    borderColor: rasioBorder,
                    borderWidth: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'Rasio Penerimaan Magang',
                        font: { size: 16 }
                    }
                }
            }
        });
    </script>
@endpush