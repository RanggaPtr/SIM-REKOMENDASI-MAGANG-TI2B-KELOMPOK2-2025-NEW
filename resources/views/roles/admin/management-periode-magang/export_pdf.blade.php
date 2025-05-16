<!DOCTYPE html>
<html>
<head>
    <title>Export Periode Magang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .date { font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Periode Magang</div>
        <div class="date">Dibuat pada: {{ now()->format('d-m-Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Periode</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($periodes as $index => $periode)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $periode->nama }}</td>
                <td>{{ $periode->tanggal_mulai->format('d-m-Y') }}</td>
                <td>{{ $periode->tanggal_selesai->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>