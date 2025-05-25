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
.role-badge {
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
.badge-admin {
    background-color: #dc3545;
    color: white;
}
.badge-dosen {
    background-color: #007bff;
    color: white;
}
.badge-mahasiswa {
    background-color: #28a745;
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
<h3 class="text-center">LAPORAN DATA PENGGUNA</h3>
<table class="border-all">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Username</th>
            <th>Nama</th>
            <th>NIM/NIK</th>
            <th>Email</th>
            <th class="text-center">Role</th>
            <th>No. Telepon</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        @php
            $nomorIdentitas = $user->nim_nik;
            
            if($user->role == 'mahasiswa' && $user->mahasiswa) {
                $nomorIdentitas = $user->mahasiswa->nim ?? $nomorIdentitas;
            } 
            elseif($user->role == 'dosen' && $user->dosen) {
                $nomorIdentitas = $user->dosen->nik ?? $nomorIdentitas;
            }
            elseif($user->role == 'admin' && $user->admin) {
                $nomorIdentitas = $user->admin->nik ?? $nomorIdentitas;
            }
        @endphp
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->nama }}</td>
            <td>{{ $nomorIdentitas }}</td>
            <td>{{ $user->email }}</td>
            <td class="text-center">
                @if($user->role == 'admin')
                    <span class="role-badge badge-admin">Admin</span>
                @elseif($user->role == 'dosen')
                    <span class="role-badge badge-dosen">Dosen</span>
                @else
                    <span class="role-badge badge-mahasiswa">Mahasiswa</span>
                @endif
            </td>
            <td>{{ $user->no_telepon }}</td>
      
        </tr>
        @endforeach
</table>
</body>
</html>