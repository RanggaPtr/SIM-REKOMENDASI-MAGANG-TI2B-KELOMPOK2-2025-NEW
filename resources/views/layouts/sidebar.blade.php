<aside class="sidebar {{ request()->is('logout') || request()->is('roles/*') ? '' : 'fade' }}" id="sidebar">
    <ul class="sidebar-nav">
        @if(Auth::user()->role === 'admin')
            <li><a href="{{ url('/roles/admin/dashboard') }}" class="{{ request()->is('roles/admin/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-header">Manajemen Magang</li>
            <li><a href="{{ url('/roles/admin/management-lowongan-magang') }}" class="{{ request()->is('roles/admin/management-lowongan-magang') ? 'active' : '' }}"><i class="fas fa-briefcase"></i> Lowongan Magang</a></li>
            <li><a href="{{ url('/roles/admin/management-pengajuan-magang') }}" class="{{ request()->is('roles/admin/management-pengajuan-magang') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Pengajuan Magang</a></li>
            <li class="nav-header">Manajemen Data</li>
            <li><a href="{{ url('/roles/admin/management-mitra') }}" class="{{ request()->is('roles/admin/management-mitra') ? 'active' : '' }}"><i class="fas fa-handshake"></i> Mitra</a></li>
            <li><a href="{{ url('/roles/admin/management-pengguna') }}" class="{{ request()->is('roles/admin/management-pengguna') ? 'active' : '' }}"><i class="fas fa-users"></i> Pengguna</a></li>
            <li><a href="{{ url('/roles/admin/management-periode') }}" class="{{ request()->is('roles/admin/management-periode') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Periode</a></li>
            <li><a href="{{ url('/roles/admin/management-prodi') }}" class="{{ request()->is('roles/admin/management-prodi') ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i> Program Studi</a></li>
            <li class="nav-header">Analitik</li>
            <li><a href="{{ url('/roles/admin/statistik-data-tren') }}" class="{{ request()->is('roles/admin/statistik-data-tren') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Statistik Tren</a></li>
        @elseif(Auth::user()->role === 'dosen')
            <li><a href="{{ url('/roles/dosen/dashboard') }}" class="{{ request()->is('roles/dosen/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-header">Akun</li>
            <li><a href="{{ url('/roles/dosen/management-akun-&-profile') }}" class="{{ request()->is('roles/dosen/management-akun-&-profile') ? 'active' : '' }}"><i class="fas fa-user"></i> Akun & Profil</a></li>
            <li class="nav-header">Bimbingan</li>
            <li><a href="{{ url('/roles/dosen/management-mahasiswa-bimbingan') }}" class="{{ request()->is('roles/dosen/management-mahasiswa-bimbingan') ? 'active' : '' }}"><i class="fas fa-user-graduate"></i> Mahasiswa Bimbingan</a></li>
        @elseif(Auth::user()->role === 'mahasiswa')
            <li><a href="{{ url('/roles/mahasiswa/dashboard') }}" class="{{ request()->is('roles/mahasiswa/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-header">Magang</li>
            <li><a href="{{ url('/roles/mahasiswa/log-harian') }}" class="{{ request()->is('roles/mahasiswa/log-harian') ? 'active' : '' }}"><i class="fas fa-book"></i> Log Harian</a></li>
            <li><a href="{{ url('/roles/mahasiswa/pengajuan-magang') }}" class="{{ request()->is('roles/mahasiswa/pengajuan-magang') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Pengajuan Magang</a></li>
            <li><a href="{{ url('/roles/mahasiswa/rekomendasi-magang') }}" class="{{ request()->is('roles/mahasiswa/rekomendasi-magang') ? 'active' : '' }}"><i class="fas fa-lightbulb"></i> Rekomendasi Magang</a></li>
            <li><a href="{{ url('/roles/mahasiswa/sertifikasi-feedback-magang') }}" class="{{ request()->is('roles/mahasiswa/sertifikasi-feedback-magang') ? 'active' : '' }}"><i class="fas fa-certificate"></i> Sertifikasi & Feedback</a></li>
            <li class="nav-header">Akun</li>
            <li><a href="{{ url('/roles/mahasiswa/management-akun-&-profile') }}" class="{{ request()->is('roles/mahasiswa/management-akun-&-profile') ? 'active' : '' }}"><i class="fas fa-user"></i> Akun & Profil</a></li>
        @endif
        <li class="nav-header">Lainnya</li>
        <li><a href="{{ url('/logout') }}" class="{{ request()->is('logout') ? 'active' : '' }}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.querySelector('.toggle-sidebar'); // Pastikan ada tombol toggle

        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebar.classList.add('fade'); // Tambahkan efek fade saat toggle
            setTimeout(() => sidebar.classList.remove('fade'), 300); // Hapus fade setelah 300ms
        });
    });
</script>