<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body {
    font-family: "Times New Roman", Times, serif;
    margin: 6px 20px 5px 20px;
    line-height: 15px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
td, th {
    padding: 4px 3px;
    border: 1px solid #ddd;
}
th {
    text-align: left;
    background-color: #f2f2f2;
}
.d-block {
    display: block;
}
img.image {
    width: auto;
    height: 80px;
    max-width: 150px;
    max-height: 150px;
}
.text-right {
    text-align: right;
}
.text-center {
    text-align: center;
}
.p-1 {
    padding: 5px 1px 5px 1px;
}
.font-10 {
    font-size: 10pt;
}
.font-11 {
    font-size: 11pt;
}
.font-12 {
    font-size: 12pt;
}
.font-13 {
    font-size: 13pt;
}
.border-bottom-header {
    border-bottom: 1px solid;
}
.border-all, .border-all th, .border-all td {
    border: 1px solid;
}
.status-badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}
.badge-diajukan {
    background-color: #ffc107;
    color: black;
}
.badge-diterima {
    background-color: #28a745;
    color: white;
}
.badge-ditolak {
    background-color: #dc3545;
    color: white;
}
.badge-selesai {
    background-color: #007bff;
    color: white;
}
</style>
</head>
<body>
<table class="border-bottom-header">
    <tr>
        <td width="15%" class="text-center">
            <img src="{{ public_path('polinema-bw.jpeg') }}" class="image">
        </td>
        <td width="85%">
            <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
            <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
            <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
            <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
            <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
        </td>
    </tr>
</table>
<h3 class="text-center">LAPORAN DATA PENGAJUAN MAGANG</h3>
<table class="border-all">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Nama Mahasiswa</th>
            <th>Judul Lowongan</th>
            <th>Perusahaan</th>
            <th>Periode</th>
            <th>Dosen Pembimbing</th>
            <th class="text-center">Status</th>
            <th class="text-center">Tanggal Pengajuan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengajuans as $pengajuan)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $pengajuan->mahasiswa && $pengajuan->mahasiswa->user ? $pengajuan->mahasiswa->user->nama : '-' }}</td>
            <td>{{ $pengajuan->lowongan ? $pengajuan->lowongan->judul : 'Belum ditentukan' }}</td>
            <td>{{ $pengajuan->lowongan && $pengajuan->lowongan->perusahaan ? $pengajuan->lowongan->perusahaan->nama : 'Belum ditentukan' }}</td>
            <td>{{ $pengajuan->lowongan && $pengajuan->lowongan->periode ? $pengajuan->lowongan->periode->nama : 'Belum ditentukan' }}</td>
            <td>{{ $pengajuan->dosen && $pengajuan->dosen->user ? $pengajuan->dosen->user->nama : 'Belum ditentukan' }}</td>
            <td class="text-center">
                @if($pengajuan->status == 'diajukan')
                    <span class="status-badge badge-diajukan">Diajukan</span>
                @elseif($pengajuan->status == 'diterima')
                    <span class="status-badge badge-diterima">Diterima</span>
                @elseif($pengajuan->status == 'ditolak')
                    <span class="status-badge badge-ditolak">Ditolak</span>
                @else
                    <span class="status-badge badge-selesai">Selesai</span>
                @endif
            </td>
            <td class="text-center">{{ $pengajuan->created_at ? $pengajuan->created_at->format('d-m-Y H:i') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="text-right" style="margin-top: 20px;">
    <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
</div>
</body>
</html>