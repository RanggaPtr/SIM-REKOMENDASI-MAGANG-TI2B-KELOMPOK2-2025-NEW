@vite(['resources/css/sidebar.css'])

<aside class="bg-light-softer vh-99" id="sidebar">
    <div class="d-flex justify-content-center my-3 bg-light-softer pb-3">
        <img src="{{ url('/images/logo.png') }}" style="height: 50px;" class="bg-light-softer" alt="">
    </div>

    <ul class="sidebar-nav nav flex-column gap-1">
        <!-- Dashboard (Tampil untuk semua role) -->
        <li class="nav-item bg-light-softer">
            <a href="{{ url( strtolower(Auth::user()->role) . '/dashboard') }}"
                class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'dashboard' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                <i class="fas fa-tachometer-alt me-4"></i><b>Dashboard</b>
            </a>
        </li>

        <!-- Manajemen Magang (Hanya untuk admin) -->
        @if (Auth::user()->role === 'admin')
            <li class="nav-item bg-light-softer">
                <div
                    class="nav-link toggle-submenu d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'manajemenMagang' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-briefcase me-4"></i><b>Manajemen Magang</b> <i class="fas fa-caret-right ms-auto pe-2"></i>
                </div>
                <ul class="nav-submenu ps-5 bg-light-softer">
                    <li><a href="{{ url('/roles/admin/management-lowongan-magang') }}"
                            class="nav-link text-muted bg-light-softer">Lowongan Magang</a></li>
                    <li><a href="{{ url('/roles/admin/management-pengajuan-magang') }}"
                            class="nav-link text-muted bg-light-softer">Pengajuan Magang</a></li>
                </ul>
            </li>
        @endif

        <!-- Manajemen Data (Hanya untuk admin) -->
        @if (Auth::user()->role === 'admin')
            <li class="nav-item bg-light-softer">
                <div
                    class="nav-link toggle-submenu d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'manajemenData' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-database me-4"></i><b>Manajemen Data</b> <i class="fas fa-caret-right ms-auto pe-2"></i>
                </div>
                <ul class="nav-submenu ps-5 bg-light-softer">
                    <li><a href="{{ url('/roles/admin/management-mitra') }}"
                            class="nav-link text-muted bg-light-softer">Mitra</a></li>
                    <li><a href="{{ url('/admin/management-pengguna') }}"
                            class="nav-link text-muted bg-light-softer">Pengguna</a></li>
                    <li><a href="{{ url('/admin/management-periode-magang') }}"
                            class="nav-link text-muted bg-light-softer">Periode</a></li>
                    <li><a href="{{ url('/admin/management-prodi') }}"
                            class="nav-link text-muted bg-light-softer">Program Studi</a></li>
                </ul>
            </li>
        @endif

        <!-- Statistik (Hanya untuk admin) -->
        @if (Auth::user()->role === 'admin')
            <li class="nav-item bg-light-softer">
                <a href="{{ url('/roles/admin/statistik-data-tren') }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'analitik' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-chart-line me-4"></i><b>Statistik Tren</b>
                </a>
            </li>
        @endif

        <!-- Menu untuk Dosen (Monitoring dan Evaluasi) -->
        @if (Auth::user()->role === 'dosen')
            <li class="nav-item bg-light-softer">
                <a href="{{ url('/roles/dosen/monitoring-mahasiswa') }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'monitoringMahasiswa' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-eye me-4"></i><b>Monitoring Mahasiswa</b>
                </a>
            </li>
            <li class="nav-item bg-light-softer">
                <a href="{{ url('/roles/dosen/evaluasi-magang') }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'evaluasiMagang' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                    <i class="fas fa-clipboard-check me-4"></i><b>Evaluasi Magang</b>
                </a>
            </li>
        @endif

        <!-- Menu untuk Mahasiswa (Sesuai Gambar) -->
        @if (Auth::user()->role === 'mahasiswa')
            <li class="nav-item bg-light-softer">
                <a href="{{ url('/roles/mahasiswa/dashboard') }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'beranda' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                    style="padding: 10px 15px;">
                    <i class="fas fa-home me-4"></i><b>Beranda</b>
                </a>
            </li>
            <li class="nav-item bg-light-softer">
                <a href="{{ url('/roles/mahasiswa/pengajuan-magang') }}"
                    class="nav-link d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'pengajuanMagang' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                    style="padding: 10px 15px;">
                    <i class="fas fa-user me-4"></i><b>Pengajuan Magang</b>
                </a>
            </li>
            <li class="nav-item bg-light-softer">
                <div
                    class="nav-link toggle-submenu d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'aktivitasMagang' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                    style="padding: 10px 15px;">
                    <i class="fas fa-building me-4"></i><b>Aktivitas Magang</b> <i class="fas fa-caret-right ms-auto pe-2"></i>
                </div>
                <ul class="nav-submenu ps-5 bg-light-softer">
                    <li><a href="{{ url('/roles/mahasiswa/log-harian') }}"
                            class="nav-link text-muted bg-light-softer">Log Harian</a></li>
                </ul>
            </li>
            <li class="nav-item bg-light-softer">
                <div
                    class="nav-link toggle-submenu d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'sertifikasiFeedback' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}"
                    style="padding: 10px 15px;">
                    <i class="fas fa-file-alt me-4"></i><b>Sertifikasi & Feedback</b> <i class="fas fa-caret-right ms-auto pe-2"></i>
                </div>
                <ul class="nav-submenu ps-5 bg-light-softer">
                    <li><a href="{{ url('/roles/mahasiswa/sertifikat') }}"
                            class="nav-link text-muted bg-light-softer">Sertifikat</a></li>
                    <li><a href="{{ url('/roles/mahasiswa/feedback') }}"
                            class="nav-link text-muted bg-light-softer">Feedback</a></li>
                </ul>
            </li>
        @endif

        <!-- Logout (Tampil untuk semua role) -->
        <li class="nav-item bg-light-softer mt-auto" style="position: absolute; bottom: 10vh;">
            <a href="{{ url('/logout') }}"
                class="nav-link logout d-flex align-items-center {{ isset($activeMenu) && $activeMenu == 'logout' ? 'active bg-primary text-light-softer rounded' : 'text-muted' }}">
                <i class="fas fa-sign-out-alt me-4"></i><b>Logout</b>
            </a>
        </li>
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const headers = document.querySelectorAll('.toggle-submenu');

        headers.forEach(header => {
            header.addEventListener('click', () => {
                const parentLi = header.parentElement;
                const submenu = parentLi.querySelector('.nav-submenu');
                const caretIcon = header.querySelector('.fa-caret-right');

                if (submenu) {
                    submenu.classList.toggle('show');
                    if (caretIcon) {
                        caretIcon.classList.toggle('rotate-90');
                    }
                }
            });
        });

        const toggleButton = document.querySelector('.toggle-sidebar');
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('active');
                sidebar.classList.add('fade');
                setTimeout(() => sidebar.classList.remove('fade'), 300);
            });
        }
    });
</script>