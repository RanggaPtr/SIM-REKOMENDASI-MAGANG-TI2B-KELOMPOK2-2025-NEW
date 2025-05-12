<aside class="sidebar">
    <ul class="sidebar-nav">
        @if(Auth::user()->role === 'admin')
            <li><a href="{{ url('/roles/admin/dashboard') }}">Dashboard</a></li>
            <li><a href="{{ url('/roles/admin/management-lowongan-magang') }}">Lowongan Magang</a></li>
            <li><a href="{{ url('/roles/admin/management-mitra') }}">Mitra</a></li>
            <li><a href="{{ url('/roles/admin/management-pengajuan-magang') }}">Pengajuan Magang</a></li>
            <li><a href="{{ url('/roles/admin/management-pengguna') }}">Pengguna</a></li>
            <li><a href="{{ url('/roles/admin/management-periode') }}">Periode</a></li>
            <li><a href="{{ url('/roles/admin/management-prodi') }}">Program Studi</a></li>
            <li><a href="{{ url('/roles/admin/statistik-data-tren') }}">Statistik Tren</a></li>
        @elseif(Auth::user()->role === 'dosen')
            <li><a href="{{ url('/roles/dosen/dashboard') }}">Dashboard</a></li>
            <li><a href="{{ url('/roles/dosen/management-akun-&-profile') }}">Akun & Profil</a></li>
            <li><a href="{{ url('/roles/dosen/management-mahasiswa-bimbingan') }}">Mahasiswa Bimbingan</a></li>
        @elseif(Auth::user()->role === 'mahasiswa')
            <li><a href="{{ url('/roles/mahasiswa/dashboard') }}">Dashboard</a></li>
            <li><a href="{{ url('/roles/mahasiswa/log-harian') }}">Log Harian</a></li>
            <li><a href="{{ url('/roles/mahasiswa/management-akun-&-profile') }}">Akun & Profil</a></li>
            <li><a href="{{ url('/roles/mahasiswa/pengajuan-magang') }}">Pengajuan Magang</a></li>
            <li><a href="{{ url('/roles/mahasiswa/rekomendasi-magang') }}">Rekomendasi Magang</a></li>
            <li><a href="{{ url('/roles/mahasiswa/sertifikasi-feedback-magang') }}">Sertifikasi & Feedback</a></li>
        @endif
        <li><a href="{{ url('/logout') }}">Logout</a></li>
    </ul>
</aside>